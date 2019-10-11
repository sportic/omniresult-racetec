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
            'http://racetecresults.com/Results.aspx?CId=16648&RId=163&EId=1&dt=0',
            $crawler->getUri()
        );
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/netTime.html', $crawler->html());
    }

    public function testGetCrawlerHasSplits()
    {
        $crawler = $this->getCrawler(168, 2, 1);

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/Results.aspx?CId=16648&RId=168&EId=2&dt=0',
            $crawler->getUri()
        );

//        static::assertContains('Cristea Felix', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/has_splits.html', $crawler->html());
    }

    public function testGetCrawlerHiddenSimpleName()
    {
        $crawler = $this->getCrawler(207, 2, 7);

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/Results.aspx?CId=16648&RId=207&EId=2&dt=0',
            $crawler->getUri()
        );

        static::assertContains('Stanciu Alecsandru', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/hidden_fullname.html', $crawler->html());
    }

    public function testGetCrawlerHiddenAccordionRow()
    {
        $crawler = $this->getCrawler(207, 1, 10);

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/Results.aspx?CId=16648&RId=207&EId=1&dt=0',
            $crawler->getUri()
        );

        static::assertContains('Bocica Dragos', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/hidden_accordion_row.html', $crawler->html());
    }

    public function testGetCrawlerNetTime()
    {
        $crawler = $this->getCrawler(116, 1, 1);

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/Results.aspx?CId=16648&RId=116&EId=1&dt=0',
            $crawler->getUri()
        );

        static::assertContains('Farkas', $crawler->html());

        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/net_time.html', $crawler->html());
    }

    public function testGetCrawlerNoCategory()
    {
        $crawler = $this->getCrawler(175, 1, 1);

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'http://racetecresults.com/Results.aspx?CId=16648&RId=175&EId=1&dt=0',
            $crawler->getUri()
        );
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/no_category.html', $crawler->html());
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Dragan Antoaneta', $crawler->html());
        static::assertContains('Foca Oana Maria', $crawler->html());
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/results_page.html', $crawler->html());
    }

    /**
     * @return Crawler
     */
    protected function getCrawler($rId = 163, $eId = 1, $page = 10)
    {
        $params = ['cId' => 16648, 'rId' => $rId, 'eId' => $eId, 'page' => $page];
        $scraper = new ResultsPage();
        $scraper->initialize($params);
        return $scraper->getCrawler();
    }
}
