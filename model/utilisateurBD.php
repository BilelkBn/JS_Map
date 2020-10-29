<?php


function ajouterBienImmobilier($lat,$lon,$prix,$iduser){
	require("model/connect.php");
	try {
		$req = "INSERT INTO `logement` (`id`, `lat`, `lon`, `prix`, `proprio`) VALUES (NULL,'" . $lat . "','" .$lon . "','" .$prix. "','".$iduser."')";
        
		$requete = $pdo->prepare($req);
		$requete->execute();
	}
	catch (PDOException $e) {
		echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
		die();
	}
}

function RecupImmobilierBD($pseudo){
	require("model/connect.php");
	$sql = "SELECT * FROM logement WHERE proprio=:idp";
	$res = array(); 
	
	try {
		$cde = $pdo->prepare($sql);
		$cde->bindParam(':idp', $pseudo);
		$b = $cde->execute();
			
		if (($b)) {
			$res = $cde->fetchAll(PDO::FETCH_ASSOC);
		}

        return($res);
	}
	catch (PDOException $e) {
		echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
		die();
	}
}

function RecupToutImmobilierBD(){
	require("model/connect.php");
	$sql = "SELECT * FROM logement";
	$res = array(); 
	
	try {
		$cde = $pdo->prepare($sql);
		$b = $cde->execute();
			
		if (($b)) {
			$res = $cde->fetchAll(PDO::FETCH_ASSOC);
		}

        return($res);
	}
	catch (PDOException $e) {
		echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
		die();
	}
}

function saveAddressBD($pseudo,$address,$cp){
	require("model/connect.php");
	
	try {
		$sql_stopTest = "UPDATE user SET addr =:addr , cp = :cp  where  login =:id";
		$cde_stopTest = $pdo->prepare($sql_stopTest);
		$cde_stopTest->bindParam(':id', $pseudo);
		$cde_stopTest->bindParam(':addr', $address);
		$cde_stopTest->bindParam(':cp', $cp);
		$b_stopTest = $cde_stopTest->execute();

	}
	catch (PDOException $e) {
		echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
		die();
	}

}

function verif_ident_BD($nom_utilisateur,$mdp){ 
	require ("model/connect.php");

	$sql_user = "SELECT * FROM `user` where  login=:nom_utilisateur and password=:mdp";
	$res_user = array(); 
	
	try {
		$cde_user = $pdo->prepare($sql_user);
		$cde_user->bindParam(':nom_utilisateur', $nom_utilisateur);
		$cde_user->bindParam(':mdp', $mdp);
		$b_user = $cde_user->execute();
		
		if (($b_user)) {
			$res_user = $cde_user->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	catch (PDOException $e) {
		echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
		die();
	}

	if (count($res_user) > 0) {
		$profil = $res_user[0];
		return $profil;
	}

	$profil = array();
	return $profil;
}

function RecupToutImmobilierAutreJoueurBD($pseudo){
    require("model/connect.php");
    $sql = "SELECT * FROM logement WHERE proprio<>:idp";
    $res = array(); 

    try {
        $cde = $pdo->prepare($sql);
        $cde->bindParam(':idp', $pseudo);
        $b = $cde->execute();

        if (($b)) {
            $res = $cde->fetchAll(PDO::FETCH_ASSOC);
        }

        return($res);
    }
    catch (PDOException $e) {
        echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
        die();
    }
}

function achatBien($pseudo,$prix){
    require("model/connect.php");

    try {
        $sql = "UPDATE user SET argents = :prix  where  login =:id";
        $cde = $pdo->prepare($sql);
        $cde->bindParam(':id', $pseudo);
        $cde->bindParam(':prix', $prix);
        $b = $cde->execute();

    }
    catch (PDOException $e) {
        echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
        die();
    }

}

function RecupArgentImmobilierBD($pseudo){
    require("model/connect.php");
    $sql = "SELECT sum(prix) as CA FROM logement WHERE proprio=:idp";
    $res = array(); 

    try {
        $cde = $pdo->prepare($sql);
        $cde->bindParam(':idp', $pseudo);
        $b = $cde->execute();

        if (($b)) {
            $res = $cde->fetchAll(PDO::FETCH_ASSOC);
        }

        return($res);
    }
    catch (PDOException $e) {
        echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
        die();
    }
}

function majArgentBD($pseudo){
    require("model/connect.php");

    $argent = RecupArgentImmobilierBD($pseudo)[0]['CA'] * 0.10;
	
    try {
        $sql = "UPDATE user SET argents += :prix  where  login =:id";
        $cde = $pdo->prepare($sql);
        $cde->bindParam(':id', $pseudo);
        $cde->bindParam(':prix', $argent);
        $b = $cde->execute();
		return $argent;
    }
    catch (PDOException $e) {
        echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
        die();
    }

}

function RecupClassementJoueurBD($pseudo){
    require("model/connect.php");
    $sql = "SELECT argents, login FROM user ORDER BY argents DESC";
    $res = array(); 

    try {
        $cde = $pdo->prepare($sql);
        $b = $cde->execute();

        if (($b)) {
            $res = $cde->fetchAll(PDO::FETCH_ASSOC);
		}
		
        for ($i=0; $i < sizeof($res); $i++) { 
			if ($res[$i]['login'] == $pseudo) {
				return array($i+1, sizeof($res));
			}
		}
		return array();
    }
    catch (PDOException $e) {
        echo utf8_encode("Echec de select : " . $e->getMessage() . "\n");
        die();
    }
}

?>