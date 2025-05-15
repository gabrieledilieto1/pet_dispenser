<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['schedule_id'])) {
  header("Location: dashboard.php");
  exit();
}

$active = isset($_POST['active']);
$schedule_id = (int) $_POST['schedule_id'];
$schedule_time = $_POST['schedule_time'];
$portion = (int) $_POST['portion_grams'];
$proximity = isset($_POST['proximity_enabled']);
$manual = isset($_POST['manual_mode']);

// Recupera animal_id per reindirizzamento
$get = pg_query_params($db, "SELECT animal_id FROM dispenser_schedules WHERE id = $1", [$schedule_id]);
if (!$get || pg_num_rows($get) === 0) exit("ID non valido");
$row = pg_fetch_assoc($get);
$animal_id = $row['animal_id'];

$sql = "UPDATE dispenser_schedules 
        SET schedule_time = $1, portion_grams = $2, proximity_enabled = $3, manual_mode = $4, active = $5 
        WHERE id = $6";
pg_query_params($db, $sql, [$schedule_time, $portion, $proximity, $manual, $active, $schedule_id]);

header("Location: dispenser_settings.php?animal_id=$animal_id&updated=1");
exit();
?>
