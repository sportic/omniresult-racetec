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
            'https://racetecresults.com/myresults.aspx?uid=16648-2091-1-29925',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Marius-Alexandru Dragu', $crawler->html());
        static::assertContains('Masculin 45-49', $crawler->html());
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/result_page.html', $crawler->html());
    }

    public function testGetCrawlerHtmlDetailsSplits()
    {
        $crawler = $this->getCrawler('16648-117-1-42147');

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Palici', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultPage/detailed_splits.html', $crawler->html());
    }

    public function testGetCrawlerHtmlNetTimeDetails()
    {
        $crawler = $this->getCrawler('16648-116-1-40995');

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Alin Bugari', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultPage/net_time_details.html', $crawler->html());
    }

    public function testGetCrawlerHtmlNoSplits()
    {
        $crawler = $this->getCrawler('16648-134-2-8533');

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Robert Eduard Peter', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultPage/no_splits.html', $crawler->html());
    }

    public function testGetCrawlerHtmlWithLapsTiming()
    {
        $crawler = $this->getCrawler('16648-2146-1-51779');

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Serbu Victor', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultPage/with_laps.html', $crawler->html());
    }

    /**
     * @return Crawler
     */
    protected function getCrawler($uid = '16648-2091-1-29925')
    {
        $params = ['uid' => $uid];
        $scraper = new ResultPage();
        $scraper->initialize($params);
        return $scraper->getCrawler();
    }
}
