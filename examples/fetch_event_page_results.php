<?php

require '../vendor/autoload.php';

// Default parameters
$defaultParameters = [
    'cId' => 16648,
    'rId' => 207,
    'eId' => 1,
    'page' => 1,
];

// Merge GET parameters with defaults (GET parameters take precedence)
$parameters = $defaultParameters;
foreach (array_keys($defaultParameters) as $key) {
    if (isset($_GET[$key])) {
        $parameters[$key] = (int) $_GET[$key];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-4">
        <a href="index.php" class="btn btn-outline-secondary mb-3">← Back to Home</a>
        <h1 class="mb-4">Event Page Results</h1>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Query Parameters</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Competition ID (cId):</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($parameters['cId']); ?></span></p>
                        <p><strong>Race ID (rId):</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($parameters['rId']); ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Event ID (eId):</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($parameters['eId']); ?></span></p>
                        <p><strong>Page:</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($parameters['page']); ?></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Results</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $client = new \Sportic\Omniresult\RaceTec\RaceTecClient();
                    $resultsParser = $client->results($parameters);
                    $resultsData   = $resultsParser->getContent();

                    $records = isset($resultsData['records']) ? $resultsData['records'] : [];

                    if (empty($records)) {
                        echo '<div class="alert alert-info">No results found.</div>';
                    } else {
                        ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Position</th>
                                        <th>Bib</th>
                                        <th>Name</th>
                                        <th>Time</th>
                                        <th>Category</th>
                                        <th>Cat Pos</th>
                                        <th>Gender</th>
                                        <th>Gen Pos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $record):
                                        $status = $record->getStatus() ?? 'active';
                                        $statusBadgeClass = in_array($status, ['active']) ? 'bg-success' : 'bg-warning';
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($record->getPosGen() ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record->getBib() ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record->getFullName() ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record->getTime() ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record->getCategory() ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record->getPosCategory() ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record->getGender() ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($record->getPosGender() ?? 'N/A'); ?></td>
                                            <td><span class="badge <?php echo $statusBadgeClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                } catch (\Exception $e) {
                    echo '<div class="alert alert-danger">Error fetching results: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    echo '<details class="mt-3"><summary>Error Details</summary><pre class="mt-2">';
                    echo htmlspecialchars($e->getTraceAsString());
                    echo '</pre></details>';
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
