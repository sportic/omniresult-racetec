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
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0'
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
