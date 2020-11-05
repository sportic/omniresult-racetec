<?php

namespace Sportic\Omniresult\RaceTec\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;

/**
 * Class ResultsPage
 * @package Sportic\Omniresult\RaceTec\Parsers
 *
 * @method \Sportic\Omniresult\RaceTec\Scrapers\ResultsPage getScraper()
 */
class ResultsPage extends AbstractParser
{
    protected $returnContent = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $this->returnContent['races'] = $this->parseRaces();
        $this->returnContent['results']['header'] = $this->parseResultsHeader();
        $this->returnContent['records'] = $this->parseResultsTable();
        $this->returnContent['pagination'] = $this->parseResultsPagination();

        return $this->returnContent;
    }

    /**
     * @return array
     */
    protected function parseRaces()
    {
        $return = [];
        $eventMenu = $this->getCrawler()->filter('#ctl00_Content_Main_pnlEventMenu');
        if ($eventMenu->count() > 0) {
            $raceLinks = $eventMenu->filter('div.tab > a');
            foreach ($raceLinks as $link) {
                $parameters = [
                    'name' => $link->nodeValue,
                    'href' => $link->getAttribute('href')
                ];
                $return[] = new Race($parameters);
            }
        }

        return $return;
    }

    /**
     * @return array
     */
    protected function parseResultsTable()
    {
        $return = [];
        $resultsRows = $this->getResultsRows();
        if ($resultsRows->count() > 0) {
            foreach ($resultsRows as $resultRow) {
                if ($this->isResultRow($resultRow)) {
                    $result = $this->parseResultsRow($resultRow);
                    if ($result) {
                        $return[] = $result;
                    }
                }
            }
        }

        return $return;
    }

    /**
     * @param DOMElement $resultRow
     * @return bool
     */
    protected function isResultRow($resultRow)
    {
        $firstChild = $resultRow->childNodes->item(1);
        if ($firstChild instanceof DOMElement && $firstChild->tagName != 'td') {
            return false;
        }
        $value = $resultRow->textContent;
        foreach (['Race No', 'Cat Pos', 'Gen Pos', 'accordian-body'] as $key) {
            if (strpos($value, $key) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getResultsRows()
    {
        $resultsTable = $this->getCrawler()->filter(
            '#ctl00_Content_Main_divGrid  > table > tbody > tr'
        );
        $resultsRows = $resultsTable;
        return $resultsRows;
    }

    /**
     * @return array
     */
    protected function parseResultsHeader()
    {
        $return = [];

        $resultsTable = $this->getCrawler()->filter(
            '#ctl00_Content_Main_divGrid table'
        )->first();
        $firstRow = $resultsTable->filter('tr')->first();
        $fields = $firstRow->children();
        if ($fields->count() > 0) {
            $colNum = 0;
            foreach ($fields as $field) {
                $headerFind = $this->parseResultsHeaderRow($field);
                if ($headerFind) {
                    $return[$colNum] = $headerFind;
                }
                $colNum++;
            }
        }

        return $return;
    }

    /**
     * @param $field
     * @return array|bool|Split
     */
    protected function parseResultsHeaderRow($field)
    {
        $classes = $field->getAttribute('class');
        if (strpos($classes, 'd-sm-none') !== false) {
            return false;
        }
        $fieldMap = self::getLabelMaps();
        $fieldName = $field->nodeValue;
        $labelFind = isset($fieldMap[$fieldName]) ? $fieldMap[$fieldName] : null;
        if ($labelFind) {
            return $labelFind;
        } else {
            $splitSearch = $this->parseResultsHeaderRowSplit($fieldName);
            if ($splitSearch) {
                return $splitSearch;
            }
        }
        return false;
    }

    /**
     * @param $fieldName
     * @return bool|Split
     */
    protected function parseResultsHeaderRowSplit($fieldName)
    {
        $needles = ['lap', 'km'];
        $haystack = strtolower($fieldName);
        if (in_array($haystack, ['laps', 'fastest lap', 'slowest lap', 'average lap'])) {
            return false;
        }
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return new Split(['name' => $fieldName]);
            }
        }
        return false;
    }

    /**
     * @param DOMElement $row
     *
     * @return bool|Result
     */
    protected function parseResultsRow(DOMElement $row)
    {
        $parameters = [];
        $colNum = 0;
        foreach ($row->childNodes as $cell) {
            if ($cell instanceof DOMElement) {
                $parameters = $this->parseResultsRowCell($colNum, $cell, $parameters);
                $colNum++;
            }
        }
        if (count($parameters)) {
            if ($this->getScraper()->isGenderCategoryMerge()) {
                $gender = isset($parameters['gender']) ? $parameters['gender'] : '';
                $category = isset($parameters['category']) ? $parameters['category'] : '';
                $parameters['category'] = trim($gender . ' ' . $category);
            }
            return new Result($parameters);
        }

        return false;
    }

    /**
     * @param int $colCount
     * @param DOMElement $cell
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function parseResultsRowCell($colCount, DOMElement $cell, $parameters = [])
    {
        if (isset($this->returnContent['results']['header'][$colCount])) {
            $field = $this->returnContent['results']['header'][$colCount];
            if ($field instanceof Split) {
                $split = clone $field;
                $split->setParameters(['time' => trim($cell->nodeValue)]);
                $parameters['splits'][] = $split;
            } elseif ($field == 'fullName') {
                $links = $cell->getElementsByTagName('a');
                $parameters['href'] = $links->item(0)->getAttribute('href');

                parse_str(parse_url($parameters['href'], PHP_URL_QUERY), $urlParameters);
                $parameters['id'] = isset($urlParameters['uid']) ? $urlParameters['uid'] : '';
                $passedParams = ['genderCategoryMerge' => $this->getScraper()->isGenderCategoryMerge() ? '1' : '0'];
                $parameters['id'] .= '::' . base64_encode(serialize($passedParams));

                $parameters[$field] = trim($cell->nodeValue);
            } elseif ($field == 'laps') {
                $parameters['notes'] = trim($cell->nodeValue) . ' laps';
            } else {
                $parameters[$field] = trim($cell->nodeValue);
            }
        }

        return $parameters;
    }

    /**
     * @return array
     */
    protected function parseResultsPagination()
    {
        $return = [
            'current' => 1,
            'all' => 1,
            'items' => 1,
        ];

        $paginationObject = $this->getCrawler()->filter(
            '#ctl00_Content_Main_lblTopPager'
        );

        if ($paginationObject->count() > 0) {
            $elements = explode(' ', $paginationObject->html());
            $return['current'] = intval($elements[1]);
            $return['all'] = intval($elements[3]);
            $return['items'] = intval(str_replace('(', '', $elements[4]));
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'Pos' => 'posGen',
            'Race No' => 'bib',
            'Name' => 'fullName',
            'Time' => 'time',
            'Net Time' => 'time',
            'Category' => 'category',
            'Cat Pos' => 'posCategory',
            'Gender' => 'gender',
            'Gen Pos' => 'posGender',
            'Laps' => 'laps'
        ];
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return ListContent::class;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Result::class;
    }
}
