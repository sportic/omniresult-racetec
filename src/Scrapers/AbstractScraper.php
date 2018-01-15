<?php

namespace Sportic\Timing\RaceTecClient\Scrapers;

use ByTIC\GouttePhantomJs\Clients\ClientFactory;
use Sportic\Timing\RaceTecClient\Parsers\AbstractParser;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractScraper
 * @package Sportic\Timing\RaceTecClient\Scrapers
 */
abstract class AbstractScraper
{

    /**
     * @var Crawler
     */
    protected $crawler = null;

    /**
     * @var Client
     */
    protected $client = null;

    /**
     * @return AbstractParser
     */
    public function execute()
    {
        $crawler = $this->getCrawler();
        $parser = $this->getNewParser();
        $parser->setCrawler($crawler);

        return $parser;
    }

    /**
     * @return Crawler
     */
    public function getCrawler()
    {
        if (!$this->crawler) {
            $this->initCrawler();
        }

        return $this->crawler;
    }

    protected function initCrawler()
    {
        $this->crawler = $this->generateCrawler();
    }

    /**
     * @return Crawler
     */
    abstract protected function generateCrawler();

    /**
     * @return Client
     */
    public function getClient()
    {
        if ($this->client == null) {
            $this->initClient();
        }

        return $this->client;
    }

    protected function initClient()
    {
        $client = $this->generateClient();
        $this->setClient($client);
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    protected function generateClient()
    {
        return ClientFactory::getPhantomJsClient();
    }

    /**
     * @return AbstractParser
     */
    protected function getNewParser()
    {
        $class = $this->getParserName();
        /** @var AbstractParser $parser */
        $parser = new $class();
        $parser->setScraper($this);
        return $parser;
    }

    /**
     * @return string
     */
    protected function getParserName()
    {
        $fullClassName = get_class($this);
        $partsClassName = explode('\\', $fullClassName);
        $classFirstName = array_pop($partsClassName);
        $classNamespacePath = implode('\\', $partsClassName);
        $classNamespacePath = str_replace('\Scrapers', '', $classNamespacePath);

        return $classNamespacePath.'\Parsers\\'.$classFirstName;
    }

    /**
     * @return string
     */
    protected function getCrawlerUriHost()
    {
        return 'http://cronometraj.racetecresults.com';
    }
}
