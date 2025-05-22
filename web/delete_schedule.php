<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['schedule_id'])) {
  header("Location: dashboard.php");
  exit();
}

$schedule_id = (int) $_POST['schedule_id'];

// Recupera l'animal_id per reindirizzare e verifica proprietà
$get = pg_query_params($db, "
  SELECT ds.animal_id
  FROM dispenser_schedules ds
  INNER JOIN animals a ON ds.animal_id = a.id
  WHERE ds.id = $1 AND a.user_id = $2
", [$schedule_id, $_SESSION['user_id']]);

if (!$get || pg_num_rows($get) === 0) {
  die("Accesso non autorizzato o orario non trovato.");
}

$row = pg_fetch_assoc($get);
$animal_id = $row['animal_id'];

// Esegui eliminazione
$delete = pg_query_params($db, "DELETE FROM dispenser_schedules WHERE id = $1", [$schedule_id]);

if (!$delete) {
  echo "Errore nella cancellazione: " . pg_last_error($db);
  exit();
}

header("Location: dispenser_settings.php?animal_id=$animal_id&deleted=1");
exit();
?>
