<?php

namespace Sportic\Omniresult\RaceTec\Tests\Parsers;

use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\SplitCollection;
use Sportic\Omniresult\RaceTec\Scrapers\ResultsPage as PageScraper;
use Sportic\Omniresult\RaceTec\Parsers\ResultsPage as PageParser;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\RaceTec\Tests\Scrapers
 */
class ResultsPageTest extends AbstractPageTest
{

//    public function testGenerateContentResultHeader()
//    {
//        self::assertCount(8, self::$parametersParsed['results']['header']);
//    }

    public function testGenerateContentResultList()
    {
        self::assertCount(50, self::$parametersParsed['records']);
        self::assertInstanceOf(Result::class, self::$parametersParsed['records'][5]);
        self::assertEquals(
            [
                'posGen' => '6',
                'bib' => '247',
                'fullName' => 'Sorin Boriceanu',
                'href' => 'MyResults.aspx?uid=16648-2091-1-29984',
                'time' => '02:04:16',
                'category' => 'Masculin 35-39',
                'posCategory' => '3',
                'gender' => 'Male',
                'posGender' => '6',
                'id' => null,
                'parameters' => null,
                'splits' => new SplitCollection(),
                'status' => null,
            ],
            self::$parametersParsed['records'][5]->__toArray()
        );
    }

    public function testGenerateContentResultPagination()
    {
        self::assertEquals(
            [
                'current' => 1,
                'all' => 5,
                'items' => 222,
            ],
            self::$parametersParsed['pagination']
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
        return new PageScraper('16648', '2091', '1');
    }

    /**
     * @inheritdoc
     */
    protected static function getNewParser()
    {
        return new PageParser();
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
