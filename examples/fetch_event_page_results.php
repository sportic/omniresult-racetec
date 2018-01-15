<?php

require '../vendor/autoload.php';

$resultsParser = \Sportic\Timing\RaceTecClient\RaceTecClient::results(16648, 121, 3, 7);
$resultsData   = $resultsParser->getContent();

var_dump($resultsData);
