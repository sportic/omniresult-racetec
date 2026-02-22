<?php

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

require '../vendor/autoload.php';

$error = null;
$detectorResult = null;
$url = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['url']) && !empty($_GET['url'])) {
    $url = $_GET['url'];
    try {
        $client = new \Sportic\Omniresult\RaceTec\RaceTecClient();
        $detectorResult = $client->detect($url);

        if (!$detectorResult->isValid()) {
            $error = 'Invalid URL. Please provide a valid RaceTec results URL.';
        } else {
            // Redirect to fetch_event_page_results.php with the detected parameters
            $params = $detectorResult->getParams();
            $queryString = http_build_query($params);
            header('Location: fetch_event_page_results.php?' . $queryString);
            exit;
        }
    } catch (\Exception $e) {
        $error = 'Error parsing URL: ' . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RaceTec Results Fetcher</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="home-page">
<div class="container">
    <h1>RaceTec Results Fetcher</h1>

    <?php
    if ($error): ?>
        <div class="error"><?php
            echo $error; ?></div>
    <?php
    endif; ?>

    <div class="info">
        <strong>Supported URLs:</strong><br>
        - cronometraj.racetecresults.com/results.aspx<br>
        - racetecresults.com/results.aspx
    </div>

    <form method="GET" action="">
        <div class="form-group">
            <label for="url">RaceTec Results URL:</label>
            <input
                type="text"
                id="url"
                name="url"
                placeholder="e.g., http://racetecresults.com/results.aspx?CId=16648&RId=207&EId=1"
                value="<?php
                echo htmlspecialchars($url); ?>"
                required
            >
        </div>
        <button type="submit">Fetch Results</button>
    </form>
</div>
</body>
</html>
