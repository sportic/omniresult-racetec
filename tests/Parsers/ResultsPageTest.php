<?php
/** @noinspection PhpMethodNamingConventionInspection */

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
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'event_page'
        );

        self::assertCount(50, $parametersParsed['records']);
        self::assertInstanceOf(Result::class, $parametersParsed['records'][5]);
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
                'id' => '16648-2091-1-29984',
                'parameters' => null,
                'splits' => new SplitCollection(),
                'status' => null,
            ],
            $parametersParsed['records'][5]->__toArray()
        );
    }

    public function testGenerateContentResultPagination()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'event_page'
        );

        self::assertEquals(
            [
                'current' => 1,
                'all' => 5,
                'items' => 222,
            ],
            $parametersParsed['pagination']
        );
    }

    public function testGenerateContentAll()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'event_page'
        );
        $parametersSerialized = static::getParametersFixtures('event_page');
        self::assertEquals($parametersSerialized, $parametersParsed->all());
    }

    public function testForResultsWithNoCategory()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/no_category'
        );

        /** @var Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);
        self::assertInstanceOf(Result::class, $records[5]);
        self::assertEquals(
            [
                'posGen' => '6',
                'bib' => '589',
                'fullName' => 'Branzoi Dorin',
                'href' => 'MyResults.aspx?uid=16648-175-1-64191',
                'time' => '00:42:58',
                'category' => null,
                'posCategory' => null,
                'gender' => 'Male',
                'posGender' => '6',
                'id' => '16648-175-1-64191',
                'parameters' => null,
                'splits' => [],
                'status' => null,
            ],
            $records[5]->__toArray()
        );
    }

    public function testForResultsWithNoCategoryWithFlag()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper())->initialize(['genderCategoryMerge' => '1']),
            'ResultsPage/no_category'
        );

        /** @var Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);
        self::assertInstanceOf(Result::class, $records[5]);
        self::assertArraySubset(
            [
                'fullName' => 'Branzoi Dorin',
                'category' => 'Male',
                'gender' => 'Male',
            ],
            $records[5]->__toArray()
        );
    }
}
