<?php

namespace Sportic\Timing\RaceTecClient\Scrapers;

use Sportic\Timing\RaceTecClient\Parsers\ResultPage as Parser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyPage
 * @package Sportic\Timing\RaceTecClient\Scrapers
 *
 * @method Parser execute()
 */
class ResultPage extends AbstractScraper
{
    protected $uid;

    /**
     * ResultPage constructor.
     *
     * @param $uid
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        $client  = $this->getClient();
        $crawler = $client->request(
            'GET',
            $this->getCrawlerUri()
        );

        return $crawler;
    }

    /**
     * @return Crawler
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost().'/MyResults.aspx?'
               . 'uid=' . $this->getUid();
    }
}
