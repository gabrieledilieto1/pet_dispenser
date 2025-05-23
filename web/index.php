
<?php
session_start();
?>


<html>
    <head>
        <title>Smart Pet Feeder</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico">  
        <link rel="stylesheet" href="index.css">      
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
      <?php if (isset($_SESSION['username'])): ?>
        <a href="dashboard.php" class="cta-button">
          Vai alla Dashboard <i class="fas fa-arrow-right"></i>
        </a>
      <?php else: ?>
        <a href="login.php" class="cta-button">
          Effettua il login per iniziare <i class="fas fa-sign-in-alt"></i>
        </a>
      <?php endif; ?>
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
            <?php if (isset($_SESSION['username'])): ?>
            <a href="storico.php" class="cta-button" style="margin-top: 10px; display: inline-block;">
          Vai allo Storico <i class="fas fa-database"></i>
            </a>
            <?php endif; ?>
          </div>
          <div class="feature-card">
            <i class="fas fa-bell"></i>
            <h3>Notifiche Intelligenti</h3>
            <p>Ricevi avvisi quando il cibo sta per terminare o se ci sono anomalie nel funzionamento.</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-mobile-alt"></i>
            <h3>App Mobile</h3>
            <p>Controlla e monitora il dispenser direttamente dal tuo smartphone.</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Sicurezza Alimentare</h3>
            <p>Materiali sicuri per alimenti e sistema anti-intasamento per la massima affidabilità.</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-cogs"></i>
            <h3>Personalizzazione Avanzata</h3>
            <p>Configura porzioni, orari e modalità di erogazione in base alle esigenze del tuo animale.</p>
          </div>
        </div>
          </section>

          <section class="how-it-works-section">
        <h2>Come funziona?</h2>
        <ol class="how-it-works-list">
          <li>
            <strong>Collega il dispenser</strong> alla rete Wi-Fi di casa.
          </li>
          <li>
            <strong>Configura</strong> gli orari e le porzioni tramite la dashboard o l’app mobile.
          </li>
          <li>
            <strong>Monitora</strong> lo stato e ricevi notifiche in tempo reale.
          </li>
          <li>
            <strong>Analizza</strong> le statistiche di alimentazione e adatta la dieta del tuo animale.
          </li>
        </ol>
          </section>

          <section class="faq-section">
        <h2>Domande Frequenti</h2>
        <div class="faq-list">
          <div class="faq-item">
            <h4>Posso controllare più di un dispenser?</h4>
            <p>Sì, puoi aggiungere e gestire più dispositivi dal tuo account.</p>
          </div>
          <div class="faq-item">
            <h4>Il sistema funziona anche senza internet?</h4>
            <p>Le funzioni base programmabili funzionano anche offline, ma il controllo remoto richiede una connessione.</p>
          </div>
          <div class="faq-item">
            <h4>È compatibile con tutti i tipi di cibo?</h4>
            <p>Il dispenser è progettato per crocchette di dimensioni standard. Verifica le specifiche per altri tipi di alimenti.</p>
          </div>
        </div>
          </section>
        </main>

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