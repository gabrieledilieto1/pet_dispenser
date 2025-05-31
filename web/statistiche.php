<?php
// proximity_stats.php

include 'db.php'; // connessione al database con pg_connect
include 'header.php'; // Include l'header per la navigazione

// intervallo ultimi 7 giorni (data in formato YYYY-MM-DD)
$start_date = date('Y-m-d', strtotime('-7 days'));

// Query con parametro
$sql = "SELECT DATE(timestamp) as day, COUNT(*) as detections
        FROM proximity_log
        WHERE timestamp >= $1
        GROUP BY day
        ORDER BY day";

// Preparazione ed esecuzione
$result = pg_prepare($db, "get_proximity_data", $sql);
$result = pg_execute($db, "get_proximity_data", array($start_date));

if (!$result) {
    die("Errore nella query: " . pg_last_error($db));
}

$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}

pg_free_result($result);
pg_close($db);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <link rel="stylesheet" href="statistiche.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiche rilevamenti di prossimità</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <main>

<h2>Statistiche Rilevamenti di Prossimità (ultimi 7 giorni)</h2>

<canvas id="proximityChart" width="600" height="400"></canvas>

<script>
const proximityData = <?php echo json_encode($data); ?>;

const labels = proximityData.map(item => item.day);
const counts = proximityData.map(item => parseInt(item.detections));

const ctx = document.getElementById('proximityChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Numero rilevamenti',
            data: counts,
            backgroundColor: 'rgba(137, 49, 104)'
        }]
    },
    options: {
        scales: {
            x: {
                title: { display: true, text: 'Giorno' }
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Numero rilevamenti' }
            }
        }
    }
});
</script>
 </main>
        <?php include 'footer.php'; ?>
</body>
</html>
