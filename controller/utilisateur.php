<?php 

function ident() {

	$nom = isset($_POST['nom-utilisateur'])?($_POST['nom-utilisateur']):'';
	$num = isset($_POST['mdp'])?($_POST['mdp']):'';

	if (count($_POST) == 0)
  		require ("./view/utilisateur/ident.tpl");
  else {

			// On lui re-affiche la page de login si l'authentification a échoué
	    if (($profil = verif_ident($nom,$num)) == array()) {
					$_SESSION['profil'] = array();
					$_SESSION['info'] = $profil;
					require ("./view/utilisateur/ident.tpl");
					
			// l'authentification a réussi, on affiche la page d'accueil
			} else {
					$_SESSION['profil'] = $nom;
					$_SESSION['password'] = $num;
					$_SESSION['info'] = $profil;
					$url = "index.php?controller=utilisateur&action=carte";
					header ("Location:" .$url);
			}
  }	
}

function verif_ident($nom,$num) {
	require ('./model/utilisateurBD.php');
	return verif_ident_BD($nom,$num);
}

function carte(){
	$login = $_SESSION['profil'];
	$profil = verif_ident($login, $_SESSION['password']);
	$argents = $_SESSION['info']['argents'];
	require('view/carte/carte.html');
}


function AjouterImmobilier(){
	require('model/utilisateurBD.php');
	
	if (isset($_POST['lat'])&isset($_POST['lon'])&isset($_POST['prix'])) {
		$lat = $_POST['lat'];
		$lon = $_POST['lon'];
		$prix = $_POST['prix'];
		if($_SESSION['info']['argents'] - $prix >= 0){
			ajouterBienImmobilier($lat,$lon,$prix,$_SESSION['profil']);
			$MajArgents = $_SESSION['info']['argents'] - $prix;
			$_SESSION['info']['argents'] = $MajArgents;
			achatBien($_SESSION['profil'],$MajArgents);
		}
	}

	$url = "index.php?controller=utilisateur&action=carte";
	header ("Location:" .$url);
}

function RecupImmobilier(){
	require('model/utilisateurBD.php');

	if (isset($_SESSION['profil'])) {
		echo json_encode(RecupImmobilierBD($_SESSION['profil']));
	}

}

function RecupToutImmobilier(){
	require('model/utilisateurBD.php');
		echo json_encode(RecupToutImmobilierBD());
}

function RecupToutImmobilierAutreJoueur(){
    require('model/utilisateurBD.php');
    if (isset($_SESSION['profil'])) {
        echo json_encode(RecupToutImmobilierAutreJoueurBD($_SESSION['profil']));
	}}
	

function RecupClassementJoueur(){
	require('model/utilisateurBD.php');
	if (isset($_SESSION['profil'])) {
		echo json_encode(RecupClassementJoueurBD($_SESSION['profil']));
	}}

function saveAddress(){
	require('model/utilisateurBD.php');

	if (isset($_GET['profil']) & isset($_GET['address'])& isset($_GET['cp'])) {
		saveAddressBD($_GET['profil'],$_GET['address'],$_GET['cp']);
	}
}

function majArgent(){
    require('model/utilisateurBD.php');
    if (isset($_SESSION['profil'])) {
       $_SESSION['info']['argents'] += majArgentBD($_SESSION['profil']);
	   echo json_encode($_SESSION['info']['argents']);
	}

}