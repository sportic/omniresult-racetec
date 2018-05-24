<?php

namespace Sportic\Timing\RaceTecClient\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use Sportic\Timing\RaceTecClient\Scrapers\AbstractScraper;
use Sportic\Timing\RaceTecClient\Parsers\EventPage as EventPageParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractPageTest
 * @package Sportic\Timing\RaceTecClient\Tests\Scrapers
 */
abstract class AbstractPageTest extends TestCase
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

    public static function setUpBeforeClass()
    {
        self::$parameters = unserialize(
            file_get_contents(TEST_FIXTURE_PATH . DS . 'Parsers' . DS . static::getSerializedFile())
        );

        $scrapper = static::getNewScraper();

        $crawler = new Crawler(null, $scrapper->getCrawlerUri());
        $crawler->addContent(
            file_get_contents(
                TEST_FIXTURE_PATH . DS . 'Parsers' . DS . static::getHtmlFile()
            ),
            'text/html;charset=utf-8'
        );

        self::$parser = static::getNewParser();
        self::$parser->setScraper($scrapper);
        self::$parser->setCrawler($crawler);

        self::$parametersParsed = self::$parser->getContent();
    }

    /**
     * @return string
     */
    abstract protected static function getSerializedFile();

    /**
     * @return string
     */
    abstract protected static function getHtmlFile();

    /**
     * @return AbstractScraper
     */
    abstract protected static function getNewScraper();

    /**
     * @return AbstractScraper
     */
    abstract protected static function getNewParser();
}
