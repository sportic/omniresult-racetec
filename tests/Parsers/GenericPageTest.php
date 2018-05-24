<?php

namespace Sportic\Timing\RaceTecClient\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use Sportic\Timing\RaceTecClient\Content\GenericContent;
use Sportic\Timing\RaceTecClient\Models\Result;
use Sportic\Timing\RaceTecClient\Scrapers\EventPage as EventPageScraper;
use Sportic\Timing\RaceTecClient\Parsers\EventPage as EventPageParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Timing\RaceTecClient\Tests\Scrapers
 */
class GenericPageTest extends AbstractPageTest
{

    public function testGenerateContentRaces()
    {
        self::assertInstanceOf(GenericContent::class, self::$parametersParsed);
    }

    /**
     * @inheritdoc
     */
    protected static function getNewScraper()
    {
        return new EventPageScraper('16648', '2091', '1');
    }

    /**
     * @inheritdoc
     */
    protected static function getNewParser()
    {
        return new EventPageParser();
    }

    /**
     * @inheritdoc
     */
    protected static function getSerializedFile()
    {
        return 'event_page.serialized';
    }

    /**
     * @inheritdoc
     */
    protected static function getHtmlFile()
    {
        return 'event_page.html';
    }
}
