<?php

namespace Sportic\Omniresult\RaceTec\Tests\Parsers;

use Sportic\Omniresult\Common\Content\ItemContent;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;
use Sportic\Omniresult\RaceTec\Parsers\ResultPage as PageParser;
use Sportic\Omniresult\RaceTec\Scrapers\ResultPage as PageScraper;

/**
 * Class ResultPageTest
 * @package Sportic\Omniresult\RaceTec\Tests\Scrapers
 */
class ResultPageTest extends AbstractPageTest
{
    public function testGenerateResultsBox()
    {
        $parsedParameters = static::getParserParameters('16648-2091-1-29925', 'result_page');

        /** @var Result $record */
        $record = $parsedParameters->getRecord();

        self::assertInstanceOf(Result::class, $record);
        self::assertSame('Marius-Alexandru Dragu', $record->getFullName());

        self::assertSame('02:12:11.38', $record->getTime());

        self::assertSame('10', $record->getPosGen());
        self::assertSame('10', $record->getPosGender());
        self::assertSame('1', $record->getPosCategory());

        $participants = $record->getParameter('participants');
        self::assertSame('211', $participants['race']);
        self::assertSame('194', $participants['gender']);
        self::assertSame('28', $participants['category']);

        self::assertSame('188', $record->getBib());
        self::assertSame('male', $record->getGender());
        self::assertSame('Masculin 45-49', $record->getCategory());
        self::assertSame('Finished', $record->getStatus());
    }

    public function testNetTimeBox()
    {
        $parsedParameters = static::getParserParameters('16648-116-1-40995', 'ResultPage\net_time_details');

        /** @var Result $record */
        $record = $parsedParameters->getRecord();

        self::assertInstanceOf(Result::class, $record);
        self::assertSame('Alin Bugari', $record->getFullName());

        self::assertSame('01:15:38', $record->getTime());
        self::assertSame('2', $record->getBib());
        self::assertSame('1', $record->getPosGen());
        self::assertSame('Masculin', $record->getCategory());
        self::assertSame('Finished', $record->getStatus());
    }

    public function testSplits()
    {
        $parsedParameters = static::getParserParameters('16648-2091-1-29925', 'result_page');

        $record = $parsedParameters->getRecord();
        /** @var Split[] $splits */
        $splits = $record->getSplits();
        self::assertEquals(12, count($splits));

        self::assertInstanceOf(Split::class, $splits[0]);
        self::assertSame('Swim', $splits[0]->getName());
        self::assertSame('00:17:21.53', $splits[0]->getTime());

        self::assertInstanceOf(Split::class, $splits[8]);
        self::assertSame('Ciclism 7', $splits[8]->getName());
        self::assertSame('01:17:11.19', $splits[8]->getTimeFromStart());
        self::assertSame('00:08:50.52', $splits[8]->getTime());
    }

    public function testSplitsNoDataToDisplay()
    {
        $parsedParameters = static::getParserParameters('16648-134-2-8533', 'ResultPage/no_splits');

        $record = $parsedParameters->getRecord();

        /** @var Split[] $splits */
        $splits = $record->getSplits();
        self::assertEquals(0, count($splits));
    }

    public function testSplitsWithDetails()
    {
        $parsedParameters = static::getParserParameters('16648-117-1-42147', 'ResultPage/detailed_splits');

        $record = $parsedParameters->getRecord();

        /** @var Split[] $splits */
        $splits = $record->getSplits();
        self::assertEquals(3, count($splits));

        $split = $splits[1];

        self::assertInstanceOf(Split::class, $split);
        self::assertSame('KM 28,7', $split->getName());

        self::assertSame('01:15:27', $split->getTime());
//        self::assertSame('02:20:50', $split->getTimeFromStart());
//        self::assertSame('11:21:55', $split->getTimeOfDay());

//        self::assertSame('2', $split->getPosGen());
//        self::assertSame('2', $split->getPosCategory());
        self::assertSame('2', $split->getPosGender());
    }

    public function testSplitsWithLaps()
    {
        $parsedParameters = static::getParserParameters('16648-2146-1-51779', 'ResultPage/with_laps');

        /** @var Result $record */
        $record = $parsedParameters->getRecord();

        self::assertSame('Serbu Victor', $record->getFullName());

        /** @var Split[] $splits */
        $splits = $record->getSplits();
        self::assertEquals(38, count($splits));

        $split = $splits[3];

        self::assertInstanceOf(Split::class, $split);
        self::assertSame('Lap 3', $split->getName());

        self::assertSame('00:36:39.32', $split->getTime());
        self::assertSame('01:45:59.62', $split->getTimeFromStart());
    }

    /**
     * @param $uid
     * @param $fixturePath
     * @return mixed
     */
    protected static function getParserParameters($uid, $fixturePath)
    {
        $scraper = new PageScraper();
        $scraper->initialize(['uid' => $uid]);

        return static::initParserFromFixtures(
            new PageParser(),
            $scraper,
            $fixturePath
        );

    }
}
