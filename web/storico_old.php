<?php
session_start();
require_once 'db.php'; // <-- usa db.php corretto
include 'header.php'; // Include l'header per la navigazione

// Verifica login utente
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Filtri GET
$animal_id = $_GET['animal_id'] ?? '';
$date = $_GET['date'] ?? '';

// Query animali per dropdown
$user_id = $_SESSION['user_id'];
$animal_query = pg_query($db, "SELECT id, name FROM animals WHERE user_id = $user_id");

// Costruzione filtro
$where = [];
if ($animal_id) $where[] = "animal_id = " . intval($animal_id);
if ($date) $where[] = "DATE(timestamp) = '" . pg_escape_string($db, $date) . "'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Raccolta log
$logs = [];
$tables = ['dispenser_logs', 'proximity_log', 'alarm_log'];
foreach ($tables as $table) {
    $query = "SELECT '$table' AS log_type, * FROM $table $where_sql";
    $result = pg_query($db, $query);
    while ($row = pg_fetch_assoc($result)) {
        $logs[] = $row;
    }
}

// Ordina per timestamp
usort($logs, function($a, $b) {
    return strtotime($b['timestamp']) < strtotime($a['timestamp']) ? 1 : -1;
});
?>
<link rel="stylesheet" href="storico.css">

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Storico Log</title>
</head>
<body>
<div class="page-wrapper">
    <main class="content">
        <h1>Storico Log</h1>
    <form method="GET">
        <label>Animale:
            <select name="animal_id">
                <option value="">Tutti</option>
                <?php while ($a = pg_fetch_assoc($animal_query)): ?>
                    <option value="<?= $a['id'] ?>" <?= $a['id'] == $animal_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>Data:
            <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
        </label>

        <button type="submit">Filtra</button>
    </form>

    <hr>

    <?php if (count($logs) == 0): ?>
        <p>Nessun log trovato.</p>
    <?php else: ?>
        <table border="1" cellpadding="6">
            <tr>
                <th>Tipo</th>
                <th>ID Animale</th>
                <th>Timestamp</th>
                <th>Dettagli</th>
            </tr>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['log_type']) ?></td>
                    <td><?= htmlspecialchars($log['animal_id']) ?></td>
                    <td><?= htmlspecialchars($log['timestamp']) ?></td>
                    <td>
                        <?php
                        if ($log['log_type'] === 'alarm_log') {
                            echo "Allarme: " . htmlspecialchars($log['alarm_type']);
                        } elseif ($log['log_type'] === 'dispenser_logs') {
                            echo "Quantità: " . htmlspecialchars($log['quantity']) . "g";
                        } elseif ($log['log_type'] === 'proximity_log') {
                            echo "Presenza rilevata";
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>  
 </main>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>
