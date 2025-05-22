<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['animal_id'])) {
  header("Location: dashboard.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$animal_id = (int) $_POST['animal_id'];

// Verifica che l'animale appartenga all'utente
$sql = "DELETE FROM animals WHERE id = $1 AND user_id = $2";
$res = pg_query_params($db, $sql, [$animal_id, $user_id]);

if ($res) {
  header("Location: dashboard.php?deleted=1");
  exit();
} else {
  echo "Errore nella cancellazione: " . pg_last_error($db);
}
?>
