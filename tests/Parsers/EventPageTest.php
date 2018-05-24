<?php

namespace Sportic\Timing\RaceTecClient\Tests\Parsers;

use Sportic\Timing\RaceTecClient\Models\Result;
use Sportic\Timing\RaceTecClient\Scrapers\EventPage as EventPageScraper;
use Sportic\Timing\RaceTecClient\Parsers\EventPage as EventPageParser;

/**
 * Class EventPageTest
 * @package Sportic\Timing\RaceTecClient\Tests\Scrapers
 */
class EventPageTest extends AbstractPageTest
{

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
                'href'        => 'MyResults.aspx?uid=16648-2091-1-29984',
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
        self::assertEquals(self::$parameters, self::$parametersParsed->all());
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
