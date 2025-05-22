<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// 1. Recupera l'ID dell'animale dalla query string
if (!isset($_GET['animal_id'])) {
  die("ID animale non fornito.");
}
$animal_id = (int) $_GET['animal_id'];

// 2. Recupera i dati attuali dell'animale
$sql = "SELECT * FROM animals WHERE id = $1 AND user_id = $2";
$res = pg_query_params($db, $sql, [$animal_id, $user_id]);
if (!$res || pg_num_rows($res) === 0) {
  die("Animale non trovato o accesso non autorizzato.");
}
$animal = pg_fetch_assoc($res);

// 3. Gestione aggiornamento dati via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['animal_name'];
  $age = $_POST['animal_age'];
  $weight = $_POST['animal_weight'];
  $breed = $_POST['animal_breed'];

  $photo_path = $animal['photo_path']; // Default: foto esistente

  // Se è stata caricata una nuova immagine
  if (!empty($_FILES['animal_photo']['tmp_name'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0755, true);
    }

    $photo_name = basename($_FILES["animal_photo"]["name"]);
    $target_file = $target_dir . time() . "_" . $photo_name;

    if (move_uploaded_file($_FILES["animal_photo"]["tmp_name"], $target_file)) {
      $photo_path = $target_file;
    }
  }

  // 4. Esegui l'UPDATE
  $update_sql = "UPDATE animals SET name = $1, age = $2, weight = $3, breed = $4, photo_path = $5 WHERE id = $6 AND user_id = $7";
  $update_res = pg_query_params($db, $update_sql, [$name, $age, $weight, $breed, $photo_path, $animal_id, $user_id]);

  if ($update_res) {
    header("Location: dashboard.php?updated=1");
    exit();
  } else {
    echo "Errore aggiornamento: " . pg_last_error($db);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Modifica Animale</title>
  <link rel="stylesheet" href="add_animal.css">
</head>
<body>

  <?php include 'header.php'; ?>

  <main class="add-animal-container">
    <section class="add-animal-form">
      <h2>Modifica Dati Animale</h2>

      <form action="" method="POST" enctype="multipart/form-data">
        <label for="animal-name">Nome animale:</label>
        <input type="text" id="animal-name" name="animal_name" value="<?php echo htmlspecialchars($animal['name']); ?>" required>

        <label for="animal-age">Età (anni):</label>
        <input type="number" id="animal-age" name="animal_age" value="<?php echo (int)$animal['age']; ?>" required>

        <label for="animal-weight">Peso (kg):</label>
        <input type="number" step="0.01" id="animal-weight" name="animal_weight" value="<?php echo (float)$animal['weight']; ?>" required>

        <label for="animal-breed">Razza:</label>
        <input type="text" id="animal-breed" name="animal_breed" value="<?php echo htmlspecialchars($animal['breed']); ?>" required>

        <label for="animal-photo">Cambia immagine (opzionale):</label>
        <input type="file" id="animal-photo" name="animal_photo" accept="image/*">

        <button type="submit" class="save-button">SALVA MODIFICHE</button>
      </form>
    </section>
    <div style="text-align: center; margin-top: 30px;">
    <a href="dashboard.php" class="cta-button">← Torna alla Dashboard</a>
    </div>

  </main>

  <?php include 'footer.php'; ?>

</body>
</html>
