<?php

namespace Sportic\Timing\RaceTecClient\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use Sportic\Timing\RaceTecClient\Models\Result;
use Sportic\Timing\RaceTecClient\Scrapers\EventPage as EventPageScraper;
use Sportic\Timing\RaceTecClient\Parsers\EventPage as EventPageParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Timing\RaceTecClient\Tests\Scrapers
 */
class EventPageTest extends TestCase
{
    protected static $parameters;

    /**
     * @var EventPageParser
     */
    protected static $parser;

    /**
     * @var array
     */
    protected static $parametersParsed;

    public function testGenerateContentRaces()
    {
        self::assertCount(5, self::$parametersParsed['races']);
    }

    public function testGenerateContentResultHeader()
    {
        self::assertCount(8, self::$parametersParsed['results']['header']);
    }

    public function testGenerateContentResultList()
    {
        self::assertCount(50, self::$parametersParsed['results']['list']);
        self::assertInstanceOf(Result::class, self::$parametersParsed['results']['list'][5]);
        self::assertEquals(
            [
                'posGen'      => '6',
                'bib'         => '247',
                'fullName'    => 'Sorin Boriceanu',
                'time'        => '02:04:16',
                'category'    => 'Masculin 35-39',
                'posCategory' => '3',
                'gender'      => 'Male',
                'posGender'   => '6',
            ],
            self::$parametersParsed['results']['list'][5]->__toArray()
        );
    }

    public function testGenerateContentResultPagination()
    {
        self::assertEquals(
            [
                'current' => 1,
                'all'     => 5,
                'items'   => 222,
            ],
            self::$parametersParsed['results']['pagination']
        );
    }

    public function testGenerateContentAll()
    {
        self::assertEquals(self::$parameters, self::$parametersParsed);
    }

    public static function setUpBeforeClass()
    {
        self::$parameters = unserialize(
            file_get_contents(TEST_FIXTURE_PATH . DS . 'Parsers' . DS . 'event_page.serialized')
        );

        $scrapper = new EventPageScraper('16648', '2091', '1');

        $crawler = new Crawler(null, $scrapper->getCrawlerUri());
        $crawler->addContent(
            file_get_contents(
                TEST_FIXTURE_PATH . DS . 'Parsers' . DS . 'event_page.html'
            ),
            'text/html;charset=utf-8'
        );

        self::$parser = new EventPageParser();
        self::$parser->setScraper($scrapper);
        self::$parser->setCrawler($crawler);

        self::$parametersParsed = self::$parser->getContent();

//        file_put_contents(
//            TEST_FIXTURE_PATH . DS . 'Parsers' . DS . 'event_page.serialized',
//            serialize(self::$parametersParsed)
//        );
    }
}
