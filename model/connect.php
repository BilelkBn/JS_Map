<?php

 
	$hostname = "pjs4.bilelcn.fr";
	$base = "pweb";
	$loginBD = "other";
	$passBD ="ParisDescartes";
	$dsn = "mysql:dbname=$base;host=$hostname;port=3306;charset=utf8";

try {

	$pdo = new PDO ($dsn, $loginBD, $passBD);
	$pdo ->exec("SET NAMES UTF8");

} catch (PDOException $e) {

	die  ("Echec de connexion : " . utf8_encode($e->getMessage()) . "\n");
}

?>