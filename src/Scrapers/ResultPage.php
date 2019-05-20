<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

use Sportic\Omniresult\RaceTec\Parsers\ResultPage as Parser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\RaceTec\Scrapers
 *
 * @method Parser execute()
 */
class ResultPage extends AbstractScraper
{
    /**
     * @throws \Sportic\Omniresult\Common\Exception\InvalidRequestException
     */
    protected function doCallValidation()
    {
        $this->validate('uid');
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->getParameter('uid');
    }

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

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost()
            . '/myresults.aspx?'
            . 'uid=' . $this->getUid();
    }
}
