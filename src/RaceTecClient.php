<?php

namespace Sportic\Timing\RaceTecClient;

use Sportic\Timing\CommonClient\TimingClient;
use Sportic\Timing\RaceTecClient\Scrapers\EventPage;

/**
 * Class RaceTecClient
 * @package Sportic\Timing\RaceTecClient
 */
class RaceTecClient extends TimingClient
{
    /**
     * @param $parameters
     * @return \Sportic\Timing\CommonClient\Parsers\AbstractParser|Parsers\EventPage
     */
    public function results($parameters)
    {
        return $this->executeScrapper(EventPage::class, $parameters);
    }
}
