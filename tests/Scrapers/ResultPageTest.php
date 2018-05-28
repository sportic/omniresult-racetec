<?php

namespace Sportic\Omniresult\RaceTec\Tests\Scrapers;

use PHPUnit\Framework\TestCase;
use Sportic\Omniresult\RaceTec\Scrapers\ResultPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\RaceTec\Tests\Scrapers
 */
class ResultPageTest extends TestCase
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/MyResults.aspx?uid=16648-2091-1-29925',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Marius-Alexandru Dragu', $crawler->html());
        static::assertContains('Masculin 45-49', $crawler->html());
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/result_page.html', $crawler->html());
    }

    /**
     * @return Crawler
     */
    protected function getCrawler()
    {
        $scraper = new ResultPage('16648-2091-1-29925');

        return $scraper->getCrawler();
    }
}
