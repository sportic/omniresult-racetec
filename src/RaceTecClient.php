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
     * @param int $cId
     * @param int $rId
     * @param int $eId
     *
     * @param int $page
     *
     * @return Parsers\EventPage
     */
    public static function results(int $cId, int $rId, int $eId, $page = 1)
    {
        return (new EventPage($cId, $rId, $eId, $page))->execute();
    }
}
