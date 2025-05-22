<?php
function username_exist($user){
	require "./db.php";
	//CONNESSIONE AL DB
	$sql = "SELECT username FROM account WHERE username=$1";
	$prep = pg_prepare($db, "sqlUsername", $sql);

	$ret = pg_execute($db, "sqlUsername", array($user));

	if(!$ret) {
		echo "ERRORE QUERY: " . pg_last_error($db);
		return false;
	}
	else{
		
		if ($row = pg_fetch_assoc($ret)){
			return true;
		}
		else{
			return false;
		}
	}
	pg_close($db);
}

function insert_utente($nome, $user, $pass){
	require "./db.php";
	//CONNESSIONE AL DB
	$hash = password_hash($pass, PASSWORD_DEFAULT);
	$sql = "INSERT INTO account(nome, username, password) VALUES($1, $2, $3)";
	$prep = pg_prepare($db, "insertUser", $sql);
	$ret = pg_execute($db, "insertUser", array($nome, $user, $hash));
	if(!$ret) {
		echo "ERRORE QUERY: " . pg_last_error($db);
		return false;
	}
	else{
		return true;
	}
	pg_close($db);
}
?>
