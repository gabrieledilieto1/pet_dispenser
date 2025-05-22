<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['animal_id'])) {
  header("Location: dashboard.php");
  exit();
}

$animal_id = (int) $_POST['animal_id'];
$schedule_time = $_POST['schedule_time'];
$portion = (int) $_POST['portion_grams'];

// ✅ Lettura coerente dei valori checkbox come stringhe
$proximity = ($_POST['proximity_enabled'] === 'true') ? 'true' : 'false';
$manual = ($_POST['manual_mode'] === 'true') ? 'true' : 'false';
$active = ($_POST['active'] === 'true') ? 'true' : 'false';

// Inserimento nel database
$sql = "INSERT INTO dispenser_schedules (animal_id, schedule_time, portion_grams, proximity_enabled, manual_mode, active)
        VALUES ($1, $2, $3, $4, $5, $6)";

$ret = pg_query_params($db, $sql, [
  $animal_id,
  $schedule_time,
  $portion,
  $proximity,
  $manual,
  $active
]);

if (!$ret) {
  echo "Errore nella query: " . pg_last_error($db);
  exit();
}

header("Location: dispenser_settings.php?animal_id=$animal_id&added=1");
exit();
?>
