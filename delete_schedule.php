<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['schedule_id'])) {
  header("Location: dashboard.php");
  exit();
}

$schedule_id = (int) $_POST['schedule_id'];

// Recupera animal_id per reindirizzo
$get = pg_query_params($db, "SELECT animal_id FROM dispenser_schedules WHERE id = $1", [$schedule_id]);
if (!$get || pg_num_rows($get) === 0) exit("Orario non trovato");
$row = pg_fetch_assoc($get);
$animal_id = $row['animal_id'];

$sql = "DELETE FROM dispenser_schedules WHERE id = $1";
pg_query_params($db, $sql, [$schedule_id]);

header("Location: dispenser_settings.php?animal_id=$animal_id&updated=1");
exit();
?>
