<?php

require '../vendor/autoload.php';

$parameters = [
    'cId' => 16648,
    'rId' => 121,
    'eId' => 3,
    'page' => 7,
];

$client = new \Sportic\Omniresult\RaceTec\RaceTecClient();
$resultsParser = $client->results($parameters);
$resultsData   = $resultsParser->getContent();

var_dump($resultsData);
