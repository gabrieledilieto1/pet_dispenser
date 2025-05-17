<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['schedule_id'])) {
  header("Location: dashboard.php");
  exit();
}

$schedule_id = (int) $_POST['schedule_id'];
$schedule_time = $_POST['schedule_time'];
$portion = (int) $_POST['portion_grams'];

// Legge i valori "true"/"false" dai checkbox (gestiti come stringhe)
$proximity = ($_POST['proximity_enabled'] === 'true') ? 'true' : 'false';
$manual = ($_POST['manual_mode'] === 'true') ? 'true' : 'false';
$active = ($_POST['active'] === 'true') ? 'true' : 'false';

// Recupera animal_id per redirect
$get = pg_query_params($db, "SELECT animal_id FROM dispenser_schedules WHERE id = $1", [$schedule_id]);
if (!$get || pg_num_rows($get) === 0) exit("ID non valido");
$row = pg_fetch_assoc($get);
$animal_id = $row['animal_id'];

$sql = "UPDATE dispenser_schedules
        SET schedule_time = $1,
            portion_grams = $2,
            proximity_enabled = $3,
            manual_mode = $4,
            active = $5
        WHERE id = $6";

$ret = pg_query_params($db, $sql, [
  $schedule_time,
  $portion,
  $proximity,
  $manual,
  $active,
  $schedule_id
]);

if (!$ret) {
  echo "Errore nella query: " . pg_last_error($db);
  exit();
}

header("Location: dispenser_settings.php?animal_id=$animal_id&updated=1");
exit();
?>
