<?php

namespace Sportic\Omniresult\RaceTec\Tests\Parsers;

use Sportic\Omniresult\Common\Content\AbstractContent;
use Sportic\Omniresult\RaceTec\Scrapers\EventPage as EventPageScraper;
use Sportic\Omniresult\RaceTec\Parsers\EventPage as EventPageParser;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\RaceTec\Tests\Scrapers
 */
class GenericPageTest extends AbstractPageTest
{
    public function testGenerateContentRaces()
    {
        $parsedParameters = static::initParserFromFixtures(
            new EventPageParser(),
            new EventPageScraper(),
            'event_page'
        );

        self::assertInstanceOf(AbstractContent::class, $parsedParameters);
    }
}
