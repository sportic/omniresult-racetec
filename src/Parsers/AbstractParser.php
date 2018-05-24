<?php

namespace Sportic\Timing\RaceTecClient\Parsers;

use Sportic\Timing\CommonClient\Content\ContentFactory;
use Sportic\Timing\RaceTecClient\Models\AbstractModel;
use Sportic\Timing\RaceTecClient\Scrapers\AbstractScraper;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractParser
 * @package Sportic\Timing\RaceTecClient\Parsers
 */
abstract class AbstractParser
{

    /**
     * @var AbstractScraper
     */
    protected $scraper;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var null|boolean
     */
    protected $isValidContent = null;

    /**
     * @var null|AbstractContent
     */
    protected $contents = null;

    /**
     * @return mixed
     */
    public function getContent()
    {
        if ($this->contents === null) {
            if ($this->isValidContent()) {
                $contents = $this->generateContent();
                $this->contents = ContentFactory::createFromArray($contents);
            } else {
                $this->contents = false;
            }
        }

        return $this->contents;
    }

    abstract protected function generateContent();

    /**
     * @return bool|null
     */
    public function isValidContent()
    {
        if ($this->isValidContent == null) {
            $this->doValidation();
            $this->isValidContent = true;
        }

        return $this->isValidContent;
    }

    /**
     * @return void
     */
    protected function doValidation()
    {
    }

    /**
     * @return AbstractScraper
     */
    public function getScraper()
    {
        return $this->scraper;
    }

    /**
     * @param AbstractScraper $scraper
     */
    public function setScraper($scraper)
    {
        $this->scraper = $scraper;
    }

    /**
     * @return Crawler
     */
    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    /**
     * @param Crawler $crawler
     */
    public function setCrawler(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->getContent();
    }

    /**
     * @return AbstractModel
     */
    public function getModel()
    {
        $model      = $this->getNewModel();
        $parameters = $this->getContent();
        $model->setParameters($parameters);

        return $model;
    }

    /**
     * @return AbstractModel
     */
    public function getNewModel()
    {
        $className = $this->getModelClassName();
        $model     = new $className();

        return $model;
    }

    abstract public function getModelClassName();

    /**
     * @return string
     */
    protected function getContentClassName()
    {
        return GenericContent::class;
    }
}
