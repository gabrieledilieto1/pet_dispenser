<?php
session_start();
require './db.php';

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

// Recupera orari
$sql = "SELECT * FROM dispenser_schedules WHERE animal_id = $1 ORDER BY schedule_time";
$res = pg_query_params($db, $sql, [$animal_id]);
$schedules = pg_fetch_all($res);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Impostazioni Dispenser</title>
  <link rel="stylesheet" href="dispenser_settings.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="dispenser-settings-container">
  <h2>Impostazioni Dispenser</h2>

  <section class="add-schedule-form">
    <h3>Aggiungi Nuovo Orario</h3>
    <form action="save_schedule.php" method="POST">
      <input type="hidden" name="animal_id" value="<?php echo $animal_id; ?>">
      <label>Orario: <input type="time" name="schedule_time" required></label>
      <label>Porzione (gr): <input type="number" name="portion_grams" min="10" required></label>
      <label>
        <input type="hidden" name="proximity_enabled" value="false">
        <input type="checkbox" name="proximity_enabled" value="true" checked> Prossimità
      </label>
      <label>
        <input type="hidden" name="manual_mode" value="false">
        <input type="checkbox" name="manual_mode" value="true"> Manuale
      </label>
      <label>
        <input type="hidden" name="active" value="false">
        <input type="checkbox" name="active" value="true" checked> Attivo
      </label>
      <button type="submit">Aggiungi</button>
    </form>
  </section>

  <section class="existing-schedules">
    <h3>Orari Programmati</h3>
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
            <tr>
              <form action="update_schedule.php" method="POST">
                <input type="hidden" name="schedule_id" value="<?php echo $s['id']; ?>">
                <td><input type="time" name="schedule_time" value="<?php echo $s['schedule_time']; ?>" required></td>
                <td><input type="number" name="portion_grams" value="<?php echo $s['portion_grams']; ?>" required></td>
                <td>
                  <input type="hidden" name="proximity_enabled" value="false">
                  <input type="checkbox" name="proximity_enabled" value="true" <?php echo ($s['proximity_enabled'] === 't' || $s['proximity_enabled'] === '1') ? 'checked' : ''; ?>>
                </td>
                <td>
                  <input type="hidden" name="manual_mode" value="false">
                  <input type="checkbox" name="manual_mode" value="true" <?php echo ($s['manual_mode'] === 't' || $s['manual_mode'] === '1') ? 'checked' : ''; ?>>
                </td>
                <td>
                  <input type="hidden" name="active" value="false">
                  <input type="checkbox" name="active" value="true" <?php echo ($s['active'] === 't' || $s['active'] === '1') ? 'checked' : ''; ?>>
                </td>
                <td>
                  <button type="submit">Salva</button>
              </form>
              <form action="delete_schedule.php" method="POST" onsubmit="return confirm('Eliminare questo orario?');" style="display:inline;">
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

  <div style="text-align:center; margin-top:20px;">
    <a href="dashboard.php" class="cta-button">← Torna alla Dashboard</a>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
