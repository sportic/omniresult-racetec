<?php

namespace Sportic\Omniresult\RaceTec\Tests\src\Parsers;

use Sportic\Omniresult\RaceTec\Parsers\EventPage as EventPageParser;
use Sportic\Omniresult\RaceTec\Scrapers\EventPage as EventPageScraper;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\RaceTec\Tests\Scrapers
 */
class EventPageTest extends AbstractPageTest
{
    public function testGenerateContentRaces()
    {
        $parsedParameters = static::initParserFromFixtures(
            new EventPageParser(),
            new EventPageScraper(),
            'event_page'
        );
        self::assertCount(5, $parsedParameters['records']);
    }
}
