<?php

namespace Sportic\Omniresult\RaceTec\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\ItemContent;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;

/**
 * Class ResultPage
 * @package Sportic\Omniresult\RaceTec\Parsers
 */
class ResultPage extends AbstractParser
{
    protected $returnContent = [];

    /**
     * @inheritdoc
     */
    protected function generateContent()
    {
        $this->returnContent['fullName'] = $this->parseFullName();
        $this->returnContent['time'] = $this->parseFinishTime();
        $this->parsePositions();
        $this->parseResultBio();
        $this->returnContent['splits'] = $this->parseSplits();

        return ['item' => new Result($this->returnContent)];
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
        $this->returnContent['posGen'] = trim($posGen);
        $this->returnContent['participants']['race'] = trim($participants);

        $posGenData = $this->getCrawler()->filter('#ctl00_Content_Main_lblResGPos')->text();
        list($posGen, $participants) = explode('/', $posGenData);
        $this->returnContent['posGender'] = trim($posGen);
        $this->returnContent['participants']['gender'] = trim($participants);

        $posGenData = $this->getCrawler()->filter('#ctl00_Content_Main_lblResCPos')->text();
        list($posGen, $participants) = explode('/', $posGenData);
        $this->returnContent['posCategory'] = trim($posGen);
        $this->returnContent['participants']['category'] = trim($participants);
    }

    protected function parseResultBio()
    {
        $table = $this->getCrawler()->filter('#ctl00_Content_Main_grdBio');
        $rows = $table->filter('tbody > tr');

        foreach ($rows as $row) {
            $values = [];
            foreach ($row->childNodes as $childNode) {
                $value = trim($childNode->nodeValue);
                if (!empty($value)) {
                    $values[] = $value;
                }
            }
            $column = $values[0];
            $value = $values[1];

            switch ($column) {
                case 'Race No':
                    $this->returnContent['bib'] = $value;
                    break;
                case 'Gender':
                    $this->returnContent['gender'] = ($value == 'Female' ? 'female' : 'male');
                    break;
                case 'Category':
                    $this->returnContent['category'] = $value;
                    break;
                case 'Status':
                    $this->returnContent['status'] = $value;
                    break;
            }
        }
    }

    /**
     * @return array
     */
    protected function parseSplits()
    {
        $return = [];
        $headerData = [];
        $splitRows = $this->getCrawler()->filter(
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

        $fieldMap = self::getLabelMaps();
        $colNum = 0;
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
        $rowNum = 0;
        foreach ($row->childNodes as $cell) {
            if ($cell instanceof DOMElement) {
                $parameters = $this->parseSplitCell($rowNum, $cell, $headerData, $parameters);
                $rowNum++;
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
            $field = $headerData[$colCount];
            $parameters[$field] = trim($cell->nodeValue);
        }

        return $parameters;
    }

    /**
     * @return array
     */
    protected static function getLabelMaps()
    {
        return [
            'name' => 'Split Name',
            'timeFromStart' => 'Time',
            'time' => 'Time From Previous Split',
        ];
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return ItemContent::class;
    }

    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Result::class;
    }
}
