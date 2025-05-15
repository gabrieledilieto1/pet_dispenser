<?php
session_start();
require './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // LOGIN
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = trim($_POST['username']);
        $pass = $_POST['password'];

        $hash = get_pwd($user, $db);
        if (!$hash) {
            echo "<script>alert('Utente non presente nel database');
                  window.location.href = 'login.php';</script>";
        } else {
            if (password_verify($pass, $hash)) {
                $_SESSION['username'] = $user;
                $_SESSION['user_id'] = get_id($user, $db); // 👈 SALVA ID UTENTE
                $_SESSION['nome'] = get_field($user, 'nome', $db);
                $_SESSION['cognome'] = get_field($user, 'cognome', $db);
                $_SESSION['mail'] = get_field($user, 'email', $db);

                $redirect_url = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'index.php';
                echo "<script>alert('Login effettuato con successo');
                      window.location.href = '$redirect_url';</script>";
            } else {
                echo "<script>alert('Username o password errati.');
                      window.location.href = 'login.php';</script>";
            }
        }

    // REGISTRAZIONE
    } elseif (isset($_POST['sign_up'])) {
        $nome = $_POST['name'];
        $cognome = $_POST['surname'];
        $user = $_POST['username'];
        $mail = $_POST['email'];
        $pass = $_POST['pwd1'];

        insert_utente($nome, $cognome, $user, $mail, $pass, $db);
    } else {
        echo "<script>alert('ERRORE: username o password non inseriti.');
              window.location.href = 'login.php';</script>";
    }
}

// === FUNZIONI ===

function get_pwd($user, $db) {
    $sql = "SELECT password FROM account WHERE username = $1";
    $res = pg_query_params($db, $sql, [$user]);
    if ($row = pg_fetch_assoc($res)) {
        return $row['password'];
    }
    return false;
}

function get_field($user, $field, $db) {
    $sql = "SELECT $field FROM account WHERE username = $1";
    $res = pg_query_params($db, $sql, [$user]);
    if ($row = pg_fetch_assoc($res)) {
        return $row[$field];
    }
    return false;
}

function get_id($user, $db) {
    $sql = "SELECT id FROM account WHERE username = $1";
    $res = pg_query_params($db, $sql, [$user]);
    if ($row = pg_fetch_assoc($res)) {
        return $row['id'];
    }
    return false;
}

function username_exist($user, $db) {
    $sql = "SELECT 1 FROM account WHERE username = $1";
    $res = pg_query_params($db, $sql, [$user]);
    return pg_num_rows($res) > 0;
}

function mail_exists($mail, $db) {
    $sql = "SELECT 1 FROM account WHERE email = $1";
    $res = pg_query_params($db, $sql, [$mail]);
    return pg_num_rows($res) > 0;
}

function insert_utente($nome, $cognome, $user, $mail, $pass, $db) {
    if (username_exist($user, $db)) {
        echo "<script>alert('Username già presente nel sistema');
              window.location.href = 'login.php';</script>";
        return;
    }

    if (mail_exists($mail, $db)) {
        echo "<script>alert('Email già presente nel sistema');
              window.location.href = 'login.php';</script>";
        return;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "INSERT INTO account(nome, cognome, username, email, password) VALUES ($1, $2, $3, $4, $5)";
    $res = pg_query_params($db, $sql, [$nome, $cognome, $user, $mail, $hash]);

    if ($res) {
        echo "<script>alert('Utente registrato con successo! Effettua il login.');
              window.location.href = 'login.php';</script>";
    } else {
        echo "ERRORE QUERY: " . pg_last_error($db);
    }
}
?>
