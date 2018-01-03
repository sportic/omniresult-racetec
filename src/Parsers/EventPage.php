<?php

namespace Sportic\Timing\RaceTecClient\Parsers;

use DOMElement;
use Sportic\Timing\RaceTecClient\Models\Race;
use Sportic\Timing\RaceTecClient\Models\Result;

/**
 * Class EventPage
 * @package Sportic\Timing\RaceTecClient\Parsers
 */
class EventPage extends AbstractParser
{
    protected $returnContent = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $this->returnContent['races']   = $this->parseRaces();
        $this->returnContent['results']['header'] = $this->parseResultsHeader();
        $this->returnContent['results']['list'] = $this->parseResultsTable();

        return $this->returnContent;
    }

    public function getModelClassName()
    {
        // TODO: Implement getModelClassName() method.
    }

    /**
     * @return array
     */
    protected function parseRaces()
    {
        $return    = [];
        $eventMenu = $this->getCrawler()->filter('#ctl00_Content_Main_pnlEventMenu');
        if ($eventMenu->count() > 0) {
            $raceLinks = $eventMenu->filter('div.tab > a');
            foreach ($raceLinks as $link) {
                $parameters = [
                    'name' => $link->nodeValue,
                    'href' => $link->getAttribute('href')
                ];
                $return[]   = new Race($parameters);
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
        $resultsRows      = $this->getCrawler()->filter(
            '#ctl00_Content_Main_grdNew_DXMainTable > tbody > tr'
        );
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
     * @return array
     */
    protected function parseResultsHeader()
    {
        $return = [];

        $fields   = $this->getCrawler()->filter(
            '#ctl00_Content_Main_grdNew_DXHeadersRow table td a'
        );
        $fieldMap = Result::getLabelMaps();
        if ($fields->count() > 0) {
            $colNum = 0;
            foreach ($fields as $field) {
                $fieldName = $field->nodeValue;
                $labelFind = array_search($fieldName, $fieldMap);
                if ($labelFind) {
                    $return[$colNum] = $labelFind;
                }
                $colNum++;
            }
        }

        return $return;
    }

    /**
     * @param DOMElement $row
     *
     * @return bool|Result
     */
    protected function parseResultsRow(DOMElement $row)
    {
        $parameters = [];
        $i = 0;
        foreach ($row->childNodes as $cell) {
            if ($cell instanceof DOMElement) {
                $parameters = $this->parseResultsRowCell($i, $cell, $parameters);
                $i++;
            }
        }
        if (count($parameters)) {
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
            if ($field == 'full_name') {
                $parameters['href'] = $cell->firstChild->getAttribute('href');
                $parameters[$field] = trim($cell->nodeValue);
            } else {
                $parameters[$field] = trim($cell->nodeValue);
            }
        }

        return $parameters;
    }
}
