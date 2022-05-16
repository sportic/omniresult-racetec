<?php

require '../vendor/autoload.php';

$parameters = [
    'cId' => 16648,
    'rId' => 207,
    'eId' => 1,
    'page' => 10,
];
$client = new \Sportic\Omniresult\RaceTec\RaceTecClient();
$resultsParser = $client->results($parameters);
$resultsData   = $resultsParser->getContent();

var_dump($resultsData);
