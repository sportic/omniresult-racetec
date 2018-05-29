<?php

require '../vendor/autoload.php';

$parameters = [
    'cId' => 16648
];

$client = new \Sportic\Omniresult\RaceTec\RaceTecClient();
$resultsParser = $client->events($parameters);
$resultsData = $resultsParser->getContent();

var_dump($resultsData);
