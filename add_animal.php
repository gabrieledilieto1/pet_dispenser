<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserimento Nuovo Animale</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="add_animal.css">
</head>

 <!-- Header comune -->
 <header>
    <?php include 'header.php' ?>
  </header>

<main class="add-animal-container">
  <section class="add-animal-form">
    <h2>Inserimento Nuovo Animale</h2>

    <form action="save_animal.php" method="POST" enctype="multipart/form-data">
      <label for="animal-name">Nome animale:</label>
      <input type="text" id="animal-name" name="animal_name" required>

      <label for="animal-photo">Aggiungi allegato:</label>
      <div class="file-upload-wrapper">
        <input type="file" id="animal-photo" name="animal_photo" accept="image/*" required>
        <i class="fas fa-camera"></i>
      </div>

      <button type="submit" class="save-button">SALVA</button>
    </form>
  </section>
</main>

<!-- Footer comune -->
<footer>
    <?php include 'footer.php' ?>
</footer>
</html>
