<?php
session_start();

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$servername = "localhost";
$username = "www"; // o altro utente DB
$password = "";     // password del DB
$dbname = "pet_feeder";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica connessione
if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}

// Recupera dati dal form
$animal_name = trim($_POST['animal_name']);
$user_id = $_SESSION['user_id'];

// Gestione immagine
$target_dir = "uploads/";
$photo_name = basename($_FILES["animal_photo"]["name"]);
$target_file = $target_dir . time() . "_" . $photo_name;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Controllo validità immagine
$check = getimagesize($_FILES["animal_photo"]["tmp_name"]);
if ($check === false) {
  die("Il file caricato non è un'immagine valida.");
}

// Salvataggio immagine
if (!move_uploaded_file($_FILES["animal_photo"]["tmp_name"], $target_file)) {
  die("Errore durante il caricamento dell'immagine.");
}

// Query SQL
$stmt = $conn->prepare("INSERT INTO animals (user_id, name, photo_path) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $animal_name, $target_file);

if ($stmt->execute()) {
  header("Location: dashboard.php?success=1");
  exit();
} else {
  echo "Errore: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
