<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

use Sportic\Omniresult\RaceTec\Parsers\EventsPage as Parser;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\RaceTec\Scrapers
 *
 * @method Parser execute()
 */
class EventsPage extends AbstractScraper
{
    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        $client = $this->getClient();
        $crawler = $client->request(
            'GET',
            $this->getCrawlerUri()
        );

        return $crawler;
    }

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost() . '/StartPage.aspx?'
            . 'CId=' . $this->getCId()
            . '&From=' . $this->getStartFrom();
    }

    /**
     * @throws \Sportic\Omniresult\Common\Exception\InvalidRequestException
     */
    protected function doCallValidation()
    {
        $this->validate('cId');
    }

    /**
     * @return string
     */
    protected function getCId()
    {
        return $this->getParameter('cId');
    }

    /**
     * @return mixed
     */
    protected function getPage()
    {
        return $this->getParameter('page', 1);
    }

    /**
     * @return float|int
     */
    protected function getStartFrom()
    {
        $page = $this->getPage();
        return (($page - 1) * $this->getItemsPerPage()) + 1;
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return 20;
    }
}
