<?php

namespace Sportic\Timing\RaceTecClient\Clients;

use Goutte\Client;

/**
 * Class ClientFactory
 * @package Sportic\Timing\RaceTecClient\Clients
 */
class ClientFactory
{
    /**
     * @return Client
     */
    public static function getGenericClient()
    {
        return self::getPhantomJsClient();
    }

    /**
     * @return Client
     */
    public static function getGoutteClient()
    {
        $options = [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0',
            ],
        ];
        return new Client($options);
    }

    /**
     * @return Client
     */
    public static function getPhantomJsClient()
    {
        $client = self::getGoutteClient();

        $phantomJsClient = new PhantomJs\ClientBridge();
        $client->setClient($phantomJsClient);
        return $client;
    }
}
