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

// ✅ Conversione sicura per PostgreSQL
$proximity = isset($_POST['proximity_enabled']) ? 'true' : 'false';
$manual = isset($_POST['manual_mode']) ? 'true' : 'false';

$sql = "INSERT INTO dispenser_schedules (animal_id, schedule_time, portion_grams, proximity_enabled, manual_mode)
        VALUES ($1, $2, $3, $4, $5)";
$res = pg_query_params($db, $sql, [$animal_id, $schedule_time, $portion, $proximity, $manual]);

if (!$res) {
  echo "Errore durante l'inserimento: " . pg_last_error($db);
  exit();
}

header("Location: dispenser_settings.php?animal_id=$animal_id&added=1");
exit();
?>
