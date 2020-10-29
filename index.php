<?php 
session_start();

if (isset($_GET['controller']) & isset($_GET['action'])) {
 	$controle = $_GET['controller'];
	$action = $_GET['action'];

} else {

	$controle = "utilisateur";
	$action = "ident";
}
	

require ('./controller/' . $controle . '.php');
$action ();
