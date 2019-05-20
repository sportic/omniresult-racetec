<?php

namespace Sportic\Omniresult\RaceTec\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\RecordContent;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;
use Sportic\Omniresult\Common\Models\SplitCollection;

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
        $this->parseResultBib();
        $this->parseResultBio();
        $this->returnContent['splits'] = $this->parseSplits();

        $params = ['record' => new Result($this->returnContent)];
        return $params;
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
        $timeContainers = ['ctl00_Content_Main_lblTime1Large', 'ctl00_Content_Main_lblTime2Large'];
        foreach ($timeContainers as $timeContainer) {
            $finishTime = $this->getCrawler()->filter('#' . $timeContainer);
            if ($finishTime->count() > 0) {
                return trim($finishTime->text());
            }
        }
        return null;
    }

    protected function parsePositions()
    {
        $this->parsePosition('gen');
        $this->parsePosition('gender');
        $this->parsePosition('category');
    }

    /**
     * @param $type
     */
    protected function parsePosition($type)
    {
        $typeValues = [
            'gen' => ['O', 'race'],
            'gender' => ['G', 'gender'],
            'category' => ['C', 'category'],
        ];
        $typeValue = $typeValues[$type];
        $positionContainers = [
            '#ctl00_Content_Main_lbl' . $typeValue[0] . 'Pos1',
            '#ctl00_Content_Main_lbl' . $typeValue[0] . 'Pos2'
        ];
        foreach ($positionContainers as $positionContainer) {
            $posNodes = $this->getCrawler()->filter($positionContainer);
            if ($posNodes->count() > 0) {
                $positionData = $posNodes->text();
                list($position, $participants) = explode('/', $positionData);
                $this->returnContent['pos' . ucfirst($type)] = trim($position);
                $this->returnContent['participants'][$typeValue[1]] = trim($participants);
                return;
            }
        }
        return;
    }

    protected function parseResultBib()
    {
        $bibContainer = $this->getCrawler()->filter('#ctl00_Content_Main_lblRaceNo');
        if ($bibContainer->count() > 0) {
            $this->returnContent['bib'] = trim($bibContainer->text());
        }
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
            $column = trim(str_replace([':'], '', $column));
            $value = isset($values[1]) ? $values[1] : '';

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
                case 'Team':
                    $this->returnContent['club'] = $value;
                    break;
            }
        }
    }

    /**
     * @return SplitCollection
     */
    protected function parseSplits()
    {
        $return = new SplitCollection();
        $headerData = [];
        $splitRows = $this->getCrawler()->filter(
            '#ctl00_Content_Main_divSplitGrid >table > tbody > tr'
        );
        if ($splitRows->count() > 0) {
            foreach ($splitRows as $resultRow) {
                $firstCell = $resultRow->childNodes->item(1);
                if ($firstCell->tagName == 'th') {
                    $headerData = $this->parseSplitsHeader($resultRow);
                } else {
                    $split = $this->parseSplitRow($resultRow, $headerData);
                    if ($split) {
                        $return[] = $split;
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
                $labelFind = isset($fieldMap[$fieldName]) ? $fieldMap[$fieldName] : null;
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
        $textContent = trim($row->textContent);
        if (strpos($textContent, 'No data to display') !== false) {
            return null;
        }

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
            'Name' => 'name',
            'Split Name' => 'name',
            'Time' => 'timeFromStart',
            'Time From Prev Leg' => 'time',
            'Leg Time' => 'time',
            'Split Time' => 'time',
            'Time From Previous Split' => 'time',
            'Time of Day' => 'timeOfDay',
            'TOD' => 'timeOfDay',
            'O Pos' => 'posGender',
            'G/Pos' => 'posGender',
            'C Pos' => 'posCategory',
            'C/Pos' => 'posCategory',
            'G Pos' => 'posGen',
            'Pos' => 'posGen',
        ];
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return RecordContent::class;
    }

    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Result::class;
    }
}
