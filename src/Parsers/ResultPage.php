<?php

namespace Sportic\Timing\RaceTecClient\Parsers;

use DOMElement;
use Sportic\Timing\RaceTecClient\Models\Split;

/**
 * Class ResultPage
 * @package Sportic\Timing\RaceTecClient\Parsers
 */
class ResultPage extends AbstractParser
{
    protected $returnContent = [];

    /**
     * @inheritdoc
     */
    protected function generateContent()
    {
        $this->returnContent['full_name'] = $this->parseFullName();
        $this->returnContent['time']      = $this->parseFinishTime();
        $this->parsePositions();
        $this->parseResultBio();
        $this->returnContent['splits'] = $this->parseSplits();

        return $this->returnContent;
    }

    public function getModelClassName()
    {
        // TODO: Implement getModelClassName() method.
    }

    /**
     * @return string
     */
    protected function parseFullName()
    {
        return trim(
            $this->getCrawler()->filter('#ctl00_Content_Main_lblName')->text()
        );
    }

    /**
     * @return string
     */
    protected function parseFinishTime()
    {
        return trim(
            $this->getCrawler()->filter('#ctl00_Content_Main_lblResFinishTime')->text()
        );
    }

    protected function parsePositions()
    {
        $posGenData = $this->getCrawler()->filter('#ctl00_Content_Main_lblResOPos')->text();
        list($posGen, $participants) = explode('/', $posGenData);
        $this->returnContent['pos_gen'] = trim($posGen);
        $this->returnContent['race']['participants'] = trim($participants);

        $posGenData = $this->getCrawler()->filter('#ctl00_Content_Main_lblResGPos')->text();
        list($posGen, $participants) = explode('/', $posGenData);
        $this->returnContent['pos_gender'] = trim($posGen);
        $this->returnContent['gender']['participants'] = trim($participants);

        $posGenData = $this->getCrawler()->filter('#ctl00_Content_Main_lblResCPos')->text();
        list($posGen, $participants) = explode('/', $posGenData);
        $this->returnContent['pos_category'] = trim($posGen);
        $this->returnContent['category']['participants'] = trim($participants);
    }

    protected function parseResultBio()
    {
        $table = $this->getCrawler()->filter('#ctl00_Content_Main_grdBio');
        $rows = $table->filter('tbody > tr');

        foreach ($rows as $row) {
            $column = $row->childNodes[0]->nodeValue;
            $value = $row->childNodes[2]->nodeValue;

            switch ($column) {
                case 'Race No':
                    $this->returnContent['bib'] = $value;
                    break;
                case 'Gender':
                    $this->returnContent['gender']['name'] = ($value == 'Female' ? 'female' : 'male');
                    break;
                case 'Category':
                    $this->returnContent['category']['name'] = $value;
                    break;
                case 'Status':
                    $this->returnContent['status']['name'] = $value;
                    break;
            }
        }
    }

    /**
     * @return array
     */
    protected function parseSplits()
    {
        $return     = [];
        $headerData = [];
        $splitRows  = $this->getCrawler()->filter(
            '#ctl00_Content_Main_grdSplits_DXMainTable > tbody > tr'
        );
        if ($splitRows->count() > 0) {
            foreach ($splitRows as $resultRow) {
                if ($resultRow->getAttribute('id') === 'ctl00_Content_Main_grdSplits_DXHeadersRow') {
                    $headerData = $this->parseSplitsHeader($resultRow);
                } else {
                    $result = $this->parseSplitRow($resultRow, $headerData);
                    if ($result) {
                        $return[] = $result;
                    }

                }
            }
        }
        return $return;
    }

    /**
     * @param DOMElement $row
     *
     * @return array
     */
    protected function parseSplitsHeader($row)
    {
        $return = [];

        $fieldMap = Split::getLabelMaps();
        $colNum   = 0;
        foreach ($row->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $fieldName = trim($node->nodeValue);
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
     * @param $row
     * @param $headerData
     *
     * @return Split|null
     */
    protected function parseSplitRow($row, $headerData)
    {
        $parameters = [];
        $i          = 0;
        foreach ($row->childNodes as $cell) {
            if ($cell instanceof DOMElement) {
                $parameters = $this->parseSplitCell($i, $cell, $headerData, $parameters);
                $i++;
            }
        }
        if (count($parameters)) {
            return new Split($parameters);
        }

        return null;
    }


    /**
     * @param $colCount
     * @param $cell
     * @param $headerData
     * @param $parameters
     *
     * @return array
     */
    protected function parseSplitCell($colCount, $cell, $headerData, $parameters)
    {
        if (isset($headerData[$colCount])) {
            $field              = $headerData[$colCount];
            $parameters[$field] = trim($cell->nodeValue);
        }

        return $parameters;
    }
}
