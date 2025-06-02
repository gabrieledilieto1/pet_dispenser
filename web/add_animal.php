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

      <label for="animal-age">Età (anni):</label>
      <input type="number" id="animal-age" name="animal_age" min="0" required>

      <label for="animal-weight">Peso (kg):</label>
      <input type="number" step="0.01" id="animal-weight" name="animal_weight" min="0" required>
      
      <label for="animal-type">Tipo animale:</label>
      <select id="animal-type" required onchange="updateBreedOptions()">
        <option value="">Seleziona tipo</option>
        <option value="cane">Cane</option>
        <option value="gatto">Gatto</option>
      </select>

      <label for="animal-breed">Razza:</label>
      <select id="animal-breed" name="animal_breed" required>
        <option value="">Seleziona razza</option>
      </select>

      <script>
        const breeds = {
          cane: [
        "Labrador Retriever",
        "Golden Retriever",
        "Bulldog",
        "Barboncino",
        "Pastore Tedesco",
        "Beagle",
        "Chihuahua",
        "Carlino",
        "Rottweiler",
        "Bassotto"
          ],
          gatto: [
        "Europeo",
        "Siamese",
        "Persiano",
        "Maine Coon",
        "Bengala",
        "Ragdoll",
        "British Shorthair",
        "Siberiano",
        "Certosino",
        "Sphynx"
          ]
        };

        function updateBreedOptions() {
          const type = document.getElementById('animal-type').value;
          const breedSelect = document.getElementById('animal-breed');
          breedSelect.innerHTML = '<option value="">Seleziona razza</option>';
          if (breeds[type]) {
        breeds[type].forEach(function(breed) {
          const option = document.createElement('option');
          option.value = breed;
          option.textContent = breed;
          breedSelect.appendChild(option);
        });
          }
        }
      </script>

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
