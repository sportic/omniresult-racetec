<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

use ByTIC\GouttePhantomJs\Clients\ClientFactory;
use Goutte\Client;
use JonnyW\PhantomJs\Client as PhantomJsBaseClient;

/**
 * Class AbstractScraper
 * @package Sportic\Omniresult\RaceTec\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Omniresult\Common\Scrapers\AbstractScraper
{

    /**
     * @return Client
     */
    protected function generateClient()
    {
        $client = PhantomJsBaseClient::getInstance();
        $client->getEngine()->addOption('--ignore-ssl-errors=true');

        return parent::generateClient();
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
