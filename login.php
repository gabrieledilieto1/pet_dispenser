<html>
    <head>
        <title>Pet Feeder - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="login.css" type="text/css"/>
        <script language="javascript" type="text/javascript">

            function registrazione(){
                document.getElementById("login_form").style.display = "none";
                document.getElementById("registration_form").style.display = "flex";
            }
            function login(){
                document.getElementById("login_form").style.display = "flex";
                document.getElementById("registration_form").style.display = "none";
            }
            function mod_password(){
                $type = document.getElementById('password').type;
                if($type == "password"){
                    document.getElementById('password').type = "text";
                    document.getElementById('pwd1').type = "text";
                    document.getElementById('confirm_password').type = "text";
                    document.getElementById('pass_photo').src = "photo/photo_login/eyeopen.png";
                    document.getElementById('pass_photo2').src = "photo/photo_login/eyeopen.png";
                    document.getElementById('pass_photo3').src = "photo/photo_login/eyeopen.png";
                }    
                else{
                    document.getElementById('password').type = "password";
                    document.getElementById('pwd1').type = "password";
                    document.getElementById('confirm_password').type = "password";
                    document.getElementById('pass_photo').src = "photo/photo_login/eyeclosed.png";
                    document.getElementById('pass_photo2').src = "photo/photo_login/eyeclosed.png";
                    document.getElementById('pass_photo3').src = "photo/photo_login/eyeclosed.png";
                }
                    
            }
            function check_signup_data(elementoModulo) {
                if (elementoModulo.email.value != elementoModulo.confirm_email.value) {
                    alert("Le due email non corrispondono");
                    elementoModulo.email.focus();
                    elementoModulo.email.select();
                    return false;
                }
                if (elementoModulo.pwd1.value.length < 8) {
                    alert("La password deve essere di almeno 8 caratteri");
                    elementoModulo.pwd1.focus();
                    return false;
                }
                if (elementoModulo.pwd1.value != elementoModulo.confirm_password.value) {
                    alert("Le due password non corrispondono");
                    elementoModulo.pwd1.focus();
                    elementoModulo.pwd1.select();
                    return false;
                }
                return true;
            }	
        </script>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body style="align-items: center; display: flex; flex-direction: column;">
    <?php
	if(isset($_POST['name']))
		$name = $_POST['name'];
	else
		$name = "";
    
    if(isset($_POST['surname']))
		$surname = $_POST['surname'];
	else
		$surname = "";

	if(isset($_POST['username']))
		$user = $_POST['username'];
	else
		$user = "";

    if(isset($_POST['email']))
		$email = $_POST['email'];
	else
		$email = "";

    

?>

<video src="photo/photo_login/video1.mov" autoplay loop muted>
    Video cannot be displayed.
    </video>

    <main style="align-items: center; display: flex; flex-direction: column; ">
        <a href="index.php"><img src="photo/logo.png" alt="Logo Pet Feeder" style="width: 40vw;" height="auto"></a>
        
        <!-- Form di login -->
        <div id="login_form" style="display:flex;">
        <form method="post" action="login-manager.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>">
        <p>
        <label for="username">
            <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $user?>"/>
        </label>
        </p>
        <p>
        <label for="password" style="display: flex; align-items: center;">
            <input type="password" name="password" id="password" placeholder="Password" style="margin-right: 5px;margin-left: 22px;"/>
            <img id="pass_photo" src="photo/photo_login/eyeclosed.png" alt="Mostra password" style="width: 20px;" onclick="mod_password()"/>
        </label>
        </p>
        <p>
        <input type="submit" name="login" class="submit_button" value="Login"/>
        </p>
        <p> Nuovo utente?</p>
        <p><input type="button" name="go_to_sign_up" class="submit_button" value="Registrati!" onclick="registrazione()"/></p>
        </form>
        </div>
        
        <div id="registration_form" style="display:none;">
        <!-- Form di registrazione -->
        <form method="post" action="login-manager.php" onsubmit="return check_signup_data(this)">
        <p>
        <label for="name">
            <input type="text" name="name" id="name" placeholder="Nome" value="<?php echo $name?>" required/>
        </label>
        </p>
        <p>
        <label for="surname">
            <input type="text" name="surname" id="surname" placeholder="Cognome" value="<?php echo $surname?>" required/>
        </label>
        </p>
        <p>
        <label for="username">
            <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $user?>" required/>
        </label>
        </p>
        <p>
        <label for="email">
            <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $email?>" required/>
        </label>
        </p>
        <p>
        <label for="confirm_email">
            <input type="email" name="confirm_email" id="confirm_email" placeholder="Conferma email" required/>
        </label>
        </p>
        <p>
        <label for="password" style="display: flex; align-items: center;">
            <input type="password" name="pwd1" id="pwd1" placeholder="Password" style="margin-right: 5px;margin-left: 22px;" required/>
            <img id="pass_photo2" src="photo/photo_login/eyeclosed.png" alt="Mostra password" style="width: 20px;" onclick="mod_password()"/>
        </label>
        </p>
        <p>
        <label for="confirm_password" style="display: flex; align-items: center;">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Conferma password" style="margin-right: 5px;margin-left: 22px;" required/>
            <img id="pass_photo3" src="photo/photo_login/eyeclosed.png" alt="Mostra password" style="width: 20px;" onclick="mod_password()"/>
        </label>
        </p>
        <p>
        <input type="submit" name="sign_up" class="submit_button" value="Effettua la registrazione" id="sign_up"/>
        </p>
        <p> Sei già registrato?</p>
        <p><input type="button" name="go_to_login" class="submit_button" value="Effettua il login!" onclick="login()"/></p>
        </form>
        </div>
        <a href="index.php"><button>Torna alla homepage</button></a>
    </main>
    </body>
    <?php require "registrazione.php" ?>
</html>