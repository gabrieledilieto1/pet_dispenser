<?php
session_start();
require './db.php';

$showAddMsg = isset($_GET['added']) && $_GET['added'] == 1;
$showUpdateMsg = isset($_GET['updated']) && $_GET['updated'] == 1;
$showDeleteMsg = isset($_GET['deleted']) && $_GET['deleted'] == 1;

if (!isset($_SESSION['user_id']) || !isset($_GET['animal_id'])) {
  header("Location: dashboard.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$animal_id = (int) $_GET['animal_id'];

// Verifica che l'animale appartenga all'utente
$check = pg_query_params($db, "SELECT id FROM animals WHERE id = $1 AND user_id = $2", [$animal_id, $user_id]);
if (!$check || pg_num_rows($check) === 0) {
  die("Accesso non autorizzato.");
}

// Recupera orari programmati
$sql = "SELECT * FROM dispenser_schedules WHERE animal_id = $1 ORDER BY schedule_time";
$res = pg_query_params($db, $sql, [$animal_id]);
$schedules = pg_fetch_all($res);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Impostazioni Dispenser</title>
  <link rel="stylesheet" href="dispenser_settings.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="dispenser-settings-container">
    <?php if ($showAddMsg): ?>
    <div class="feedback success">✅ Orario aggiunto con successo.</div>
    <?php endif; ?>

    <?php if ($showUpdateMsg): ?>
    <div class="feedback success">✅ Orario aggiornato con successo.</div>
    <?php endif; ?>

    <?php if ($showDeleteMsg): ?>
    <div class="feedback warning">🗑️ Orario eliminato correttamente.</div>
    <?php endif; ?>

  <h2>Impostazioni Erogatore</h2>

  <section class="add-schedule-form">
    <h3>Aggiungi nuovo orario</h3>
    <form action="save_schedule.php" method="POST">
      <input type="hidden" name="animal_id" value="<?php echo $animal_id; ?>">

      <label>Orario:</label>
      <input type="time" name="schedule_time" required>

      <label>Porzione (gr):</label>
      <input type="number" name="portion_grams" min="10" required>

      <label><input type="checkbox" name="proximity_enabled" checked> Prossimità attiva</label>
      <label><input type="checkbox" name="manual_mode"> Modalità manuale</label>

      <button type="submit">Aggiungi</button>
    </form>
  </section>

  <section class="existing-schedules">
    <h3>Orari programmati</h3>
    <?php if (!$schedules): ?>
      <p>Nessun orario impostato.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Orario</th>
            <th>Porzione (gr)</th>
            <th>Prossimità</th>
            <th>Manuale</th>
            <th>Attivo</th>
            <th>Azioni</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($schedules as $s): ?>
          <tr class="<?php echo $s['active'] ? '' : 'inactive'; ?>">
            <form action="update_schedule.php" method="POST">
              <input type="hidden" name="schedule_id" value="<?php echo $s['id']; ?>">
              <td><input type="time" name="schedule_time" value="<?php echo $s['schedule_time']; ?>" required></td>
              <td><input type="number" name="portion_grams" value="<?php echo $s['portion_grams']; ?>" required></td>
              <td><input type="checkbox" name="proximity_enabled" <?php echo $s['proximity_enabled'] ? 'checked' : ''; ?>></td>
              <td><input type="checkbox" name="manual_mode" <?php echo $s['manual_mode'] ? 'checked' : ''; ?>></td>
              <td><input type="checkbox" name="active" <?php echo $s['active'] ? 'checked' : ''; ?>></td>
              <td>
                <button type="submit">Salva</button>
            </form>
            <form action="delete_schedule.php" method="POST" onsubmit="return confirm('Confermi eliminazione?');" style="display:inline;">
              <input type="hidden" name="schedule_id" value="<?php echo $s['id']; ?>">
              <button type="submit">Elimina</button>
            </form>
              </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
