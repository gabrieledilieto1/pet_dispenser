<html>
    <head>
        <title>Smart Pet Feeder</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico">        
    </head>


<!-- Header comune -->
 <header>
    <?php include 'header.php' ?>
  </header>

  <body>
  <main class="homepage-container">
    <section class="intro-section">
      <h1>Benvenuto in Smart Pet Feeder</h1>
      <p>Controlla e programma l’alimentazione del tuo animale domestico ovunque tu sia.</p>
      <a href="dashboard.php" class="cta-button">
        Vai alla Dashboard <i class="fas fa-arrow-right"></i>
      </a>
    </section>

    <section class="features-section">
      <h2>Funzionalità principali</h2>
      <div class="features-grid">
        <div class="feature-card">
          <i class="fas fa-clock"></i>
          <h3>Erogazione Programmata</h3>
          <p>Imposta orari di alimentazione automatica secondo le esigenze del tuo pet.</p>
        </div>
        <div class="feature-card">
          <i class="fas fa-wifi"></i>
          <h3>Controllo Remoto</h3>
          <p>Gestisci il sistema da qualsiasi dispositivo connesso tramite Wi-Fi e MQTT.</p>
        </div>
        <div class="feature-card">
          <i class="fas fa-dog"></i>
          <h3>Sensore di Prossimità</h3>
          <p>Rileva la presenza dell'animale e anticipa l’erogazione se necessario.</p>
        </div>
        <div class="feature-card">
          <i class="fas fa-chart-line"></i>
          <h3>Statistiche & Storico</h3>
          <p>Monitora il consumo del cibo e le abitudini del tuo animale.</p>
        </div>
      </div>
    </section>
    <section class="image-gallery">
    <img src="img/prodotto-frontale.jpg" alt="Erogatore - Vista Frontale">
    <img src="img/prodotto-laterale.jpg" alt="Erogatore - Vista Laterale">
    <img src="img/prodotto-con-animale.jpg" alt="Erogatore in uso con animale">
    </section>

  </main>
</body>



 <!-- Footer comune -->
<footer>
    <?php include 'footer.php' ?>
</footer>

</html>