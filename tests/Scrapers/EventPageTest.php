<?php

namespace Sportic\Omniresult\RaceTec\Tests\Scrapers;

use PHPUnit\Framework\TestCase;
use Sportic\Omniresult\RaceTec\Scrapers\EventPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\RaceTec\Tests\Scrapers
 */
class EventPageTest extends TestCase
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/Results.aspx?CId=16648&RId=2091&EId=1',
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
        $params = [
            'cId' => 16648,
            'rId' => 2091,
            'eId' => 1
        ];
        $scraper = new EventPage();
        $scraper->initialize($params);
        return $scraper->getCrawler();
    }
}
