<?php

namespace Sportic\Timing\RaceTecClient\Scrapers;

/**
 * Class AbstractScraper
 * @package Sportic\Timing\RaceTecClient\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Timing\CommonClient\Scrapers\AbstractScraper
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
        return 'http://cronometraj.racetecresults.com';
    }
}
