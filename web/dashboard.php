<?php
session_start();
require './db.php';

$showUpdateMessage = isset($_GET['updated']) && $_GET['updated'] == 1;
$showDeleteMessage = isset($_GET['deleted']) && $_GET['deleted'] == 1;

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Recupera gli animali dell'utente
$sql = "SELECT id, name, age, weight, breed, photo_path FROM animals WHERE user_id = $1";
$result = pg_query_params($db, $sql, [$user_id]);

$animals = [];
if ($result && pg_num_rows($result) > 0) {
  while ($row = pg_fetch_assoc($result)) {
    $animals[] = $row;
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">  
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://unpkg.com/paho-mqtt/mqttws31.min.js"></script>
    <script>
      const client = new Paho.MQTT.Client("broker.hivemq.com", 8000, "webClient" + parseInt(Math.random() * 1000));
      client.connect({onSuccess: () => console.log("MQTT connesso!")});

      function sendTime() {
          const time = document.getElementById("feedTime").value;
          const message = new Paho.MQTT.Message(time);
          message.destinationName = "/petfeeder/schedule";
          client.send(message);
          alert("Orario inviato: " + time);
      }
    </script>
  </head>

  <body>
    <?php include 'header.php'; ?>

    <main class="dashboard-container">
        <?php if ($showUpdateMessage): ?>
          <div class="update-success-message">
            ✅ Dati dell'animale aggiornati con successo.
        </div>
        <?php endif; ?>

        <?php if ($showDeleteMessage): ?>
          <div class="update-success-message" style="background-color:#ffe3e3; color:#a80000; border-color:#ffc2c2;">
          🗑️ Animale eliminato con successo.
          </div>
        <?php endif; ?>

      <section class="user-header">
        <div class="user-info">
          <div class="user-avatar">
            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
          </div>
          <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
        <a href="add_animal.php" class="add-animal-button">
          <i class="fas fa-plus"></i> Aggiungi animale
        </a>
      </section>

      <section class="animal-list">
        <?php if (count($animals) === 0): ?>
          <p>Nessun animale registrato. Aggiungine uno per iniziare!</p>
        <?php else: ?>
          <?php foreach ($animals as $animal): ?>
            <div class="animal-card">
              <div class="animal-photo">
                <img src="<?php echo htmlspecialchars($animal['photo_path']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
              </div>
              <div class="animal-details">
                <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
                <p>Età: <?php echo (int)$animal['age']; ?> anni — Peso: <?php echo (float)$animal['weight']; ?> kg</p>
                <p>Razza: <?php echo htmlspecialchars($animal['breed']); ?></p>
              </div>
              <div class="dispenser-settings">
                <p><a href="dispenser_settings.php?animal_id=<?php echo $animal['id']; ?>">Impostazioni dispenser</a></p>
              </div>
              <form action="edit_animal.php" method="GET">
                <input type="hidden" name="animal_id" value="<?php echo $animal['id']; ?>">
                <button type="submit" class="edit-button">Edit</button>
              </form>
              <form action="delete_animal.php" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo animale?');">
               <input type="hidden" name="animal_id" value="<?php echo $animal['id']; ?>">
               <button type="submit" class="delete-button">Elimina</button>
              </form>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>

      <!-- Sezione Programmazione Erogazione -->
      <section class="schedule-section">
          <h2>Programma erogazione</h2>
          <label for="feedTime">Orario (HH:MM):</label>
          <input type="time" id="feedTime">
          <button onclick="sendTime()">Programma</button>
      </section>
    </main>

    <?php include 'footer.php'; ?>
  </body>
</html>
