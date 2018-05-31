<?php

namespace Sportic\Omniresult\RaceTec;

use Sportic\Omniresult\Common\TimingClient;
use Sportic\Omniresult\RaceTec\Scrapers\EventsPage;
use Sportic\Omniresult\RaceTec\Scrapers\EventPage;
use Sportic\Omniresult\RaceTec\Scrapers\ResultsPage;
use Sportic\Omniresult\RaceTec\Scrapers\ResultPage;

/**
 * Class RaceTecClient
 * @package Sportic\Omniresult\RaceTec
 */
class RaceTecClient extends TimingClient
{
    /**
     * @param $parameters
     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\EventsPage
     */
    public function events($parameters)
    {
        return $this->executeScrapper(EventsPage::class, $parameters);
    }

    /**
     * @param $parameters
     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\EventPage
     */
    public function event($parameters)
    {
        return $this->executeScrapper(EventPage::class, $parameters);
    }

    /**
     * @param $parameters
     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\ResultsPage
     */
    public function results($parameters)
    {
        return $this->executeScrapper(ResultsPage::class, $parameters);
    }

    /**
     * @param $parameters
     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\ResultPage
     */
    public function result($parameters)
    {
        return $this->executeScrapper(ResultPage::class, $parameters);
    }
}
