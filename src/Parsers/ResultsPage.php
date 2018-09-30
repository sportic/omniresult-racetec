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
                if ($resultRow->getAttribute('id') !== 'ctl00_Content_Main_grdNew_DXHeadersRow') {
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
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getResultsRows()
    {
        $resultsTable = $this->getCrawler()->filter(
            '#ctl00_Content_Main_grdNew_DXMainTable'
        )->children();
        $resultsRows = $resultsTable->nodeName() == 'tbody' ?
            $resultsTable->children()
            : $resultsTable;
        return $resultsRows;
    }

    /**
     * @return array
     */
    protected function parseResultsHeader()
    {
        $return = [];

        $fields = $this->getCrawler()->filter(
            '#ctl00_Content_Main_grdNew_DXHeadersRow table td a'
        );
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
        $fieldMap = self::getLabelMaps();
        $fieldName = $field->nodeValue;
        $labelFind = array_search($fieldName, $fieldMap);
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
        $needles = ['lap'];
        $haystack = strtolower($fieldName);
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
                $parameters['href'] = $cell->lastChild->getAttribute('href');

                parse_str(parse_url($parameters['href'], PHP_URL_QUERY), $urlParameters);
                $parameters['id'] = isset($urlParameters['uid']) ? $urlParameters['uid'] : '';
                $parameters[$field] = trim($cell->nodeValue);
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
            'posGen' => 'Pos',
            'bib' => 'Race No',
            'fullName' => 'Name',
            'time' => 'Time',
            'category' => 'Category',
            'posCategory' => 'Cat Pos',
            'gender' => 'Gender',
            'posGender' => 'Gen Pos'
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
