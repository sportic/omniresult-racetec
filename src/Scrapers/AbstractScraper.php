<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

use ByTIC\GouttePhantomJs\Clients\ClientFactory;
use Symfony\Component\BrowserKit\HttpBrowser;

/**
 * Class AbstractScraper
 * @package Sportic\Omniresult\RaceTec\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Omniresult\Common\Scrapers\AbstractScraper
{

    /**
     * @inheritDoc
     */
    protected function generateClient()
    {
        $client = ClientFactory::getPhantomJsClient();
//        $client->getEngine()->addOption('--ignore-ssl-errors=true');

        return $client;
//        return parent::generateClient();
    }

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost();
    }

    /**
     * @return string
     */
    protected function getCrawlerUriHost()
    {
        return 'https://racetecresults.com';
    }
}

