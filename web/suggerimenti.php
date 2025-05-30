<?php
// proximity_suggestions.php

include 'db.php'; // connessione al db PostgreSQL
include 'header.php'; // Include l'header per la navigazione
$start_date = date('Y-m-d', strtotime('-7 days'));

// 1) Conteggio totale rilevamenti ultimi 7 giorni
$sql_total = "SELECT COUNT(*) as total FROM proximity_log WHERE timestamp >= $1";
$res_total = pg_prepare($db, "total_proximity", $sql_total);
$res_total = pg_execute($db, "total_proximity", array($start_date));
$total_data = pg_fetch_assoc($res_total);
$total = intval($total_data['total']);

// 2) Conteggio rilevamenti fuori orari programmati
// Qui definiamo per esempio orari erogazione fissi: 7-9 e 18-20 (da adattare in base al tuo sistema)
$sql_off_hours = "
SELECT COUNT(*) as off_total 
FROM proximity_log 
WHERE timestamp >= $1
AND (
    EXTRACT(HOUR FROM timestamp) < 7 
    OR (EXTRACT(HOUR FROM timestamp) > 9 AND EXTRACT(HOUR FROM timestamp) < 18) 
    OR EXTRACT(HOUR FROM timestamp) > 20
)";
$res_off = pg_prepare($db, "off_hours_proximity", $sql_off_hours);
$res_off = pg_execute($db, "off_hours_proximity", array($start_date));
$off_data = pg_fetch_assoc($res_off);
$off_total = intval($off_data['off_total']);

// 3) Generazione suggerimento
if ($total < 5) {
    $suggestion = "L'animale si avvicina poco al dispenser: prova a spostarlo in un posto più accessibile o visibile.";
} elseif ($off_total > 10) {
    $suggestion = "L'animale si avvicina spesso fuori dagli orari di erogazione: potresti aggiungere o ampliare una fascia di erogazione anticipata.";
} else {
    $suggestion = "La frequenza di avvicinamento sembra corretta. Continua così!";
}

pg_close($db);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <title>Consigli sull'uso del dispenser</title>
    <style>
      body { font-family: Arial, sans-serif; margin: 30px; background: #f9f9f9; }
      .container { max-width: 700px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
      h1 { color: #2a7ae2; }
      .suggestion { margin-top: 20px; padding: 15px; background: #e3f2fd; border-left: 6px solid #2196f3; font-size: 1.1em; }
      .metrics { margin-top: 15px; font-size: 0.9em; color: #555; }
    </style>
</head>
<body>

<div class="container">
  <h1>Consigli per migliorare l'erogazione del cibo</h1>

  <div class="suggestion">
    <?php echo htmlspecialchars($suggestion); ?>
  </div>

  <div class="metrics">
    <p><strong>Metriche analizzate (ultimi 7 giorni):</strong></p>
    <ul>
      <li>Totale rilevamenti di prossimità: <strong><?php echo $total; ?></strong></li>
      <li>Rilevamenti fuori orari programmati: <strong><?php echo $off_total; ?></strong></li>
    </ul>
  </div>
</div>
    <?php include 'footer.php'; ?>
</body>
</html>
