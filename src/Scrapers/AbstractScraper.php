<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

/**
 * Class AbstractScraper
 * @package Sportic\Omniresult\RaceTec\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Omniresult\Common\Scrapers\AbstractScraper
{

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost();
    }

    /**
     * @return string
     */
    protected function getCrawlerUriHost()
    {
        return 'https://racetecresults.com';
    }
}
