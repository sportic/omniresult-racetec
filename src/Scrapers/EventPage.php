<?php

namespace Sportic\Timing\RaceTecClient\Scrapers;

use Sportic\Timing\RaceTecClient\Parsers\EventPage as Parser;

/**
 * Class CompanyPage
 * @package Sportic\Timing\RaceTecClient\Scrapers
 *
 * @method Parser execute()
 */
class EventPage extends AbstractScraper
{
    protected $cId;
    protected $rId;
    protected $eId = 1;

    /**
     * EventPage constructor.
     *
     * @param $cId
     * @param $rId
     * @param int $eId
     */
    public function __construct($cId, $rId, int $eId)
    {
        $this->cId = $cId;
        $this->rId = $rId;
        $this->eId = $eId;
    }


    /**
     * @return mixed
     */
    public function getCId()
    {
        return $this->cId;
    }

    /**
     * @param mixed $cId
     */
    public function setCId($cId)
    {
        $this->cId = $cId;
    }

    /**
     * @return mixed
     */
    public function getRId()
    {
        return $this->rId;
    }

    /**
     * @param mixed $rId
     */
    public function setRId($rId)
    {
        $this->rId = $rId;
    }

    /**
     * @return int
     */
    public function getEId(): int
    {
        return $this->eId;
    }

    /**
     * @param int $eId
     */
    public function setEId(int $eId)
    {
        $this->eId = $eId;
    }

    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        $crawler = $this->getClient()->request(
            'GET',
            'http://cronometraj.racetecresults.com/Results.aspx?'
            . 'CId=' . $this->getCId()
            . '&RId=' . $this->getRId()
            . '&EId=' . $this->getEId()
        );

        return $crawler;
    }
}
