<?php session_start(); ?>
<html>
  <head>
    <title>Smart Pet Feeder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="header.css">
  </head>

  <header>
    <nav class="navbar">
      <div class="logo">
        <a href="index.php"><img src="photo/logo_b&W.svg" alt="SMART PET FEEDER"></a>
      </div>

      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
      

      <?php if (isset($_SESSION['username'])): ?>
        <!-- UTENTE LOGGATO -->
        <li class="dropdown">
          <a href="#" class="login-btn">
            <img src="photo/photo_header/user.png" alt="userphoto">
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </a>
          <ul class="dropdown-menu" id="login_dropdown">
            <li><a href="impostazioni_profilo.php">Impostazioni profilo</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      <?php else: ?>
        <!-- UTENTE NON LOGGATO -->
        <li>
          <a href="login.php" class="login-btn">
            <img src="photo/photo_header/user.png" alt="userphoto">Login
          </a>
        </li>
      <?php endif; ?>
      </ul>
    </nav>
  </header>
</html>
