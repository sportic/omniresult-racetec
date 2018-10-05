<?php

namespace Sportic\Omniresult\RaceTec\Tests\Scrapers;

use PHPUnit\Framework\TestCase;
use Sportic\Omniresult\RaceTec\Scrapers\ResultsPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\RaceTec\Tests\Scrapers
 */
class ResultsPageTest extends TestCase
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/Results.aspx?CId=16648&RId=163&EId=1',
            $crawler->getUri()
        );
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/netTime.html', $crawler->html());
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Dragan Antoaneta', $crawler->html());
        static::assertContains('Foca Oana Maria', $crawler->html());
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/result_page.html', $crawler->html());
    }

    /**
     * @return Crawler
     */
    protected function getCrawler()
    {
        $params = ['cId' => 16648, 'rId' => 163, 'eId' => 1, 'page' => 10];
        $scraper = new ResultsPage();
        $scraper->initialize($params);
        return $scraper->getCrawler();
    }
}
