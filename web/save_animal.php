<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id'])) {
  die("Accesso non autorizzato");
}

$user_id = $_SESSION['user_id'];
$name = $_POST['animal_name'];
$age = $_POST['animal_age'];
$weight = $_POST['animal_weight'];
$breed = $_POST['animal_breed'];

$target_dir = "uploads/";
$photo_name = basename($_FILES["animal_photo"]["name"]);
$target_file = $target_dir . time() . "_" . $photo_name;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$check = getimagesize($_FILES["animal_photo"]["tmp_name"]);
if ($check === false) {
  die("Il file caricato non è un'immagine valida.");
}

if (!is_dir("uploads")) {
  mkdir("uploads", 0755, true);
}

if (!move_uploaded_file($_FILES["animal_photo"]["tmp_name"], $target_file)) {
  die("Errore nel caricamento dell'immagine.");
}

pg_query_params($db, "DELETE FROM animals WHERE user_id = $1", [$user_id]);

$sql = "INSERT INTO animals (id, user_id, name, age, weight, breed, photo_path, created_at)
        VALUES (1, $1, $2, $3, $4, $5, $6, NOW())";


$res = pg_query_params($db, $sql, [$user_id, $name, $age, $weight, $breed, $target_file]);

if ($res) {
  header("Location: dashboard.php");
  exit();
} else {
  $error = pg_last_error($db);
  if (strpos($error, 'unique_user_per_animal') !== false) {
    echo "❌ Errore: hai già registrato un animale.";
  } else {
    echo "❌ Errore durante il salvataggio: " . htmlspecialchars($error);
  }
}
?>
