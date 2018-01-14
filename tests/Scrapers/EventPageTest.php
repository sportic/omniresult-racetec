<?php

namespace Sportic\Timing\RaceTecClient\Tests\Scrapers;

use PHPUnit\Framework\TestCase;
use Sportic\Timing\RaceTecClient\Scrapers\EventPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Timing\RaceTecClient\Tests\Scrapers
 */
class EventPageTest extends TestCase
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://cronometraj.racetecresults.com/Results.aspx?CId=16648&RId=2091&EId=1',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Marius-Alexandru Dragu', $crawler->html());
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/event_page.html', $crawler->html());
    }

    /**
     * @return Crawler
     */
    protected function getCrawler()
    {
        $scraper = new EventPage('16648', '2091', '1');

        return $scraper->getCrawler();
    }
}
