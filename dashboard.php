<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico">  
        <link rel="stylesheet" href="dashboard.css">

    </head>

    <!-- Header comune -->
 <header>
    <?php include 'header.php' ?>
  </header>

<main class="dashboard-container">
  <section class="user-header">
    <div class="user-info">
      <div class="user-avatar">A</div>
      <span class="username">Utente loggato</span>
    </div>
    <a href="add_animal.php" class="add-animal-button">
      <i class="fas fa-plus"></i> Aggiungi animale
    </a>
  </section>

  <section class="animal-list">
    <!-- ANIMALE 1 -->
    <div class="animal-card">
      <div class="animal-photo">FOTO</div>
      <div class="animal-details">
        <h3>Nome animale</h3>
        <p>Caratteristiche - Scheda animale</p>
      </div>
      <div class="dispenser-settings">
        <p>Impostazioni dispenser</p>
      </div>
      <button class="edit-button">Edit</button>
    </div>

    <!-- ANIMALE 2 -->
    <div class="animal-card">
      <div class="animal-photo">FOTO</div>
      <div class="animal-details">
        <h3>Nome animale</h3>
        <p>Caratteristiche - Scheda animale</p>
      </div>
      <div class="dispenser-settings">
        <p>Impostazioni dispenser</p>
      </div>
      <button class="edit-button">Edit</button>
    </div>
  </section>
</main>

 <!-- Footer comune -->
 <footer>
    <?php include 'footer.php' ?>
</footer>

</html>