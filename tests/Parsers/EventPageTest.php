<?php

namespace Sportic\Timing\RaceTecClient\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use Sportic\Timing\RaceTecClient\Scrapers\EventPage as EventPageScraper;
use Sportic\Timing\RaceTecClient\Parsers\EventPage as EventPageParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Timing\RaceTecClient\Tests\Scrapers
 */
class EventPageTest extends TestCase
{
    public function testGenerateContent()
    {
        $parameters = require TEST_FIXTURE_PATH . DS . 'Parsers' . DS . 'event_page.php';

        $scrapper = new EventPageScraper('16648', '2091', '1');

        $crawler = new Crawler(null, $scrapper->getCrawlerUri());
        $crawler->addContent(
            file_get_contents(
                TEST_FIXTURE_PATH . DS . 'Parsers' . DS . 'event_page.html'
            ),
            'text/html;charset=utf-8'
        );

        $parser = new EventPageParser();
        $parser->setScraper($scrapper);
        $parser->setCrawler($crawler);

        self::assertEquals($parameters, $parser->getContent());
    }
}
