<?php

namespace Sportic\Timing\RaceTecClient\Tests\Parsers;

use Sportic\Timing\RaceTecClient\Models\Split;
use Sportic\Timing\RaceTecClient\Scrapers\ResultPage as PageScraper;
use Sportic\Timing\RaceTecClient\Parsers\ResultPage as PageParser;

/**
 * Class ResultPageTest
 * @package Sportic\Timing\RaceTecClient\Tests\Scrapers
 */
class ResultPageTest extends AbstractPageTest
{

    public function testGenerateResultsBox()
    {
        self::assertSame('Marius-Alexandru Dragu', self::$parametersParsed['full_name']);

        self::assertSame('02:12:11.38', self::$parametersParsed['time']);
        self::assertSame('10', self::$parametersParsed['pos_gen']);
        self::assertSame('211', self::$parametersParsed['race']['participants']);

        self::assertSame('10', self::$parametersParsed['pos_gender']);
        self::assertSame('194', self::$parametersParsed['gender']['participants']);

        self::assertSame('1', self::$parametersParsed['pos_category']);
        self::assertSame('28', self::$parametersParsed['category']['participants']);

        self::assertSame('188', self::$parametersParsed['bib']);
        self::assertSame('male', self::$parametersParsed['gender']['name']);
        self::assertSame('Masculin 45-49', self::$parametersParsed['category']['name']);
        self::assertSame('Finished', self::$parametersParsed['status']['name']);
    }

    public function testSplits()
    {
        /** @var Split[] $splits */
        $splits = self::$parametersParsed['splits'];
        self::assertEquals(12, count($splits));

        self::assertInstanceOf(Split::class, $splits[0]);
        self::assertSame('Swim', $splits[0]->getName());
        self::assertSame('00:17:21.53', $splits[0]->getTime());

        self::assertInstanceOf(Split::class, $splits[8]);
        self::assertSame('Ciclism 7', $splits[8]->getName());
        self::assertSame('01:17:11.19', $splits[8]->getTimeFromStart());
        self::assertSame('00:08:50.52', $splits[8]->getTime());
    }

    /**
     * @inheritdoc
     */
    protected static function getNewScraper()
    {
        return new PageScraper('16648-2091-1-29925');
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
        return 'result_page.serialized';
    }

    /**
     * @inheritdoc
     */
    protected static function getHtmlFile()
    {
        return 'result_page.html';
    }
}
