<?php

namespace Sportic\Timing\RaceTecClient\Parsers;

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
        $this->returnContent['time'] = $this->parseFinishTime();
        $this->parsePositions();
        $this->parseResultBio();

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
}
