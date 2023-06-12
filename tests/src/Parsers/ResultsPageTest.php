<?php
/** @noinspection PhpMethodNamingConventionInspection */

namespace Sportic\Omniresult\RaceTec\Tests\src\Parsers;

use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\RaceTec\Parsers\ResultsPage as PageParser;
use Sportic\Omniresult\RaceTec\Scrapers\ResultsPage as PageScraper;

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

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(50, $results);
        self::assertInstanceOf(Result::class, $results[5]);
        self::assertEquals(
            [
                'posGen' => 6,
                'bib' => '247',
                'fullName' => 'Sorin Boriceanu',
                'href' => 'myresults.aspx?uid=16648-2091-1-29984',
                'time' => '02:04:16',
                'category' => 'Masculin 35-39',
                'posCategory' => 3,
                'gender' => 'male',
                'posGender' => 6,
                'id' => '16648-2091-1-29984::YToxOntzOjE5OiJnZW5kZXJDYXRlZ29yeU1lcmdlIjtzOjE6IjAiO30=',
                'parameters' => null,
                'splits' => [],
                'status' => null,
                'country' => null,
                'club' => null,
                'firstName' => 'Sorin',
                'lastName' => 'Boriceanu',
                'timeGross' => null,
                'notes' => null,
                'result' => null
            ],
            $results[5]->__toArray()
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
                'nextUrl' => ''
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
        $string = serialize($parametersParsed->all());
        self::assertEquals($parametersSerialized, $parametersParsed->all());
    }

    public function testForResultsWithNoCategory()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/no_category'
        );

        /** @var array|Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);
        self::assertInstanceOf(Result::class, $records[5]);
        self::assertEquals(
            [
                'posGen' => 6,
                'bib' => '589',
                'fullName' => 'Branzoi Dorin',
                'href' => 'myresults.aspx?uid=16648-175-1-64191',
                'time' => '00:42:58',
                'category' => null,
                'posCategory' => null,
                'gender' => 'male',
                'posGender' => 6,
                'id' => '16648-175-1-64191::YToxOntzOjE5OiJnZW5kZXJDYXRlZ29yeU1lcmdlIjtzOjE6IjAiO30=',
                'parameters' => null,
                'splits' => [
                    0 => [
                        'name' => 'Lap1',
                        'time' => '00:20:27',
                        'timeFromStart' => null,
                        'timeOfDay' => null,
                        'posGen' => null,
                        'posCategory' => null,
                        'posGender' => null,
                        'parameters' => null,
                        'timeGross' => null,
                        'result' => null
                    ],
                    1 => [
                        'name' => 'Lap2',
                        'time' => '00:22:30',
                        'timeFromStart' => null,
                        'timeOfDay' => null,
                        'posGen' => null,
                        'posCategory' => null,
                        'posGender' => null,
                        'parameters' => null,
                        'timeGross' => null,
                        'result' => null
                    ],
                ],
                'status' => null,
                'country' => null,
                'club' => null,
                'firstName' => 'Branzoi',
                'lastName' => 'Dorin',
                'timeGross' => null,
                'notes' => null,
                'result' => null
            ],
            $records[5]->__toArray()
        );
    }

    public function testForResultsWithNoCategoryWithFlag()
    {
        $parametersParsed = static::getParserParameters(
            ['genderCategoryMerge' => '1'],
            'ResultsPage/no_category'
        );

        /** @var array|Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);
        self::assertInstanceOf(Result::class, $records[5]);
        $record = $records[5]->__toArray();
        self::assertSame('Branzoi Dorin', $record['fullName']);
        self::assertSame('Male', $record['category']);
        self::assertSame('male', $record['gender']);
    }

    public function testForResultsWithHiddenFullName()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/hidden_fullname'
        );

        /** @var array|Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);

        $selectedRecord = $records[5];
        self::assertInstanceOf(Result::class, $selectedRecord);
        self::assertSame('Mihai Diana', $selectedRecord->getFullName());
    }

    public function testForResultsWithHiddenRowAccordion()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/hidden_accordion_row'
        );

        /** @var array|Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);

        $selectedRecord = $records[5];
        self::assertInstanceOf(Result::class, $selectedRecord);
        self::assertSame('Bobic Florin', $selectedRecord->getFullName());
    }

    public function testForNetTimeColumns()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/net_time'
        );

        /** @var array|Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);
        self::assertInstanceOf(Result::class, $records[5]);

        $result = $records[5];
        self::assertEquals('Razvan Farkas', $result->getFullName());
        self::assertEquals('01:24:40', $result->getTime());

        $splits = $result->getSplits();
        self::assertCount(3, $splits);

        $split = $splits[1];
        self::assertEquals('KM 10,5', $split->getName());
        self::assertEquals('00:25:00', $split->getTime());
    }

    public function testForLapsColumns()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/with_laps'
        );

        /** @var array|Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(22, $records);
        self::assertInstanceOf(Result::class, $records[5]);

        $result = $records[5];
        self::assertEquals('Serbu Victor', $result->getFullName());
        self::assertEquals('23:51:08', $result->getTime());
        self::assertEquals('26 laps', $result->getNotes());
        self::assertEquals(
            '16648-2146-1-51779::YToxOntzOjE5OiJnZW5kZXJDYXRlZ29yeU1lcmdlIjtzOjE6IjAiO30=',
            $result->getId()
        );

        $splits = $result->getSplits();
        self::assertCount(0, $splits);
    }

    public function testForSplitsColumns()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/has_splits'
        );

        /** @var array|Result[] $records */
        $records = $parametersParsed['records'];

        self::assertCount(50, $records);
        self::assertInstanceOf(Result::class, $records[5]);
        self::assertEquals(
            [
                'posGen' => 6,
                'bib' => '201',
                'fullName' => 'David Mihai',
                'href' => 'myresults.aspx?uid=16648-168-2-10993',
                'time' => '01:41:38',
                'category' => 'Masculin 30-39',
                'posCategory' => 1,
                'gender' => 'male',
                'posGender' => 6,
                'id' => '16648-168-2-10993::YToxOntzOjE5OiJnZW5kZXJDYXRlZ29yeU1lcmdlIjtzOjE6IjAiO30=',
                'splits' => [
                    0 => [
                        'name' => 'Lap 1',
                        'time' => '00:49:47',
                        'timeFromStart' => null,
                        'timeOfDay' => null,
                        'posGen' => null,
                        'posCategory' => null,
                        'posGender' => null,
                        'parameters' => null,
                        'timeGross' => null,
                        'result' => null
                    ],
                    1 => [
                        'name' => 'Lap 2',
                        'time' => '01:41:38',
                        'timeFromStart' => null,
                        'timeOfDay' => null,
                        'posGen' => null,
                        'posCategory' => null,
                        'posGender' => null,
                        'parameters' => null,
                        'timeGross' => null,
                        'result' => null
                    ],
                ],
                'status' => null,
                'country' => null,
                'club' => null,
                'parameters' => null,
                'firstName' => 'David',
                'lastName' => 'Mihai',
                'timeGross' => null,
                'notes' => null,
                'result' => null
            ],
            $records[5]->__toArray()
        );
    }

    /**
     * @param $params
     * @param $fixturePath
     * @return mixed
     */
    protected static function getParserParameters($params, $fixturePath)
    {
        $scraper = new PageScraper();
        $scraper->initialize($params);

        return static::initParserFromFixtures(
            new PageParser(),
            $scraper,
            $fixturePath
        );
    }
}
