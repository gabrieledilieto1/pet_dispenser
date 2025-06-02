<?php

// Include connessione DB pg_connect
require_once 'db.php';
include 'header.php'; // Include l'header per la navigazione


// Liste colonne per tabella
$tables = [
    'dispenser_logs' => ['animal_id', 'grams', 'delivered_at'],
    'alarm_log' => ['animal_id', 'alarm_type', 'timestamp'],
    'proximity_log' => ['animal_id', 'timestamp']
];

// Tabella scelta dall’utente
$table = $_GET['table'] ?? 'dispenser_logs';
if (!array_key_exists($table, $tables)) $table = 'dispenser_logs';

// Filtri
$animal_id = $_GET['animal_id'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Preparazione query dinamica con parametri
$where_clauses = [];
$params = [];

if ($animal_id !== '') {
    $where_clauses[] = 'animal_id = $' . (count($params) + 1);
    $params[] = $animal_id;
}

$date_column = $table == 'dispenser_logs' ? 'delivered_at' : 'timestamp';

if ($date_from !== '') {
    $where_clauses[] = "$date_column >= $" . (count($params) + 1);
    $params[] = $date_from;
}
if ($date_to !== '') {
    $where_clauses[] = "$date_column <= $" . (count($params) + 1);
    $params[] = $date_to;
}

$where_sql = count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
$sql = "SELECT " . implode(',', $tables[$table]) . " FROM $table $where_sql ORDER BY $date_column DESC LIMIT 100";
$result = pg_query_params($db, $sql, $params);

if (!$result) die("Errore nella query: " . pg_last_error($db));
$rows = pg_fetch_all($result) ?: [];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="storico.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storico - Pet Feeder</title>
</head>
<body>
 <main>
    <h1>Storico dati Pet Feeder</h1>
    <form method="get" action="storico.php" class="storico-form">
        <label for="table">Tipo storico:</label>
        <select name="table" id="table" onchange="this.form.submit()">
            <?php foreach ($tables as $key => $cols): ?>
                <option value="<?= htmlspecialchars($key) ?>" <?= ($key == $table) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($key) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="date_from">Da:</label>
        <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($date_from) ?>" />

        <label for="date_to">A:</label>
        <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($date_to) ?>" />

        <button type="submit">Filtra</button>
    </form>

    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <?php foreach ($tables[$table] as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rows) === 0): ?>
                <tr><td colspan="<?= count($tables[$table]) ?>">Nessun dato trovato</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php foreach ($tables[$table] as $col): ?>
                            <td data-label="<?= htmlspecialchars($col) ?>"><?= htmlspecialchars($row[$col]) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
 </main>
 <?php include 'footer.php'; ?>
</body>
</html>
