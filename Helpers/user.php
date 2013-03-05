<?php

/**
  Scripts d'enegistrement / connexion / deconnexion des membres
 */

/**
  Verifie que le login entrÃ© est correct a l'aide d'expressions rÃ©guliÃ¨res.
 */
function verifierLogin($login) {
    $ve = false;
    if (preg_match("#^[A-Za-z0-9_]{4,20}$#", $login)) {
        $ve = true;
    }
    return $ve;
}



/**
 *
 * @param int $id l'id du membre
 * @param type $bdd la bdd
 * @return string le pseudo du membre ayant l'id en parametre
 */
function pseudoMembre($id, $bdd) {
    try {
        $Req = $bdd->prepare("
                SELECT pseudo 
                FROM membres
                WHERE id_membre = ?");
        $Req->execute(array($id));
    } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
    if ($Req->rowCount() <= 0) {
        return FALSE;
    } else {
        $res = $Req->fetch(PDO::FETCH_COLUMN);
        return $res;
    }
}

/**
  Verifie la correspondance entre le mot de passe et sa confirmation.
 */
function verifierCorrespMotDePasse($pass, $v_pass) {
    if ($pass == $v_pass) {
        return true;
    } else {
        return false;
    }
}

/**
  Verifie la validitÃ©e du mot de passe a l'aide d'une expression rÃ©guliÃ¨re.
 */
function verifierMotDePasse($pass) {
    if (!preg_match("#^[A-Za-z0-9]{8,}$#", $pass)) {
        return false;
    } else {
        return true;
    }
}

/**
  Verifie que le pseudo n'est pas dÃ©ja utilisÃ©
 */
function verifierUnicitePseudo($bdd, $pseudo, $id = 0) {
    try {
        $Req = $bdd->prepare("SELECT * FROM membres WHERE pseudo like ?");
        $Req->execute(array($pseudo));
    } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
    if ($Req->rowCount() == 0) {
        $var = true;
    } elseif ($Req->rowCount() == 1) {
        while ($res = $Req->fetch(PDO::FETCH_NUM)) {
            $_id = $res[0];
            if ($_id == $id) {
                $var = true;
            }
            else
                $var = false;
        }
    }
    else {
        $var = false;
    }

    return $var;
}

/**
  Enregistre le membre dans la base de donnÃ©es
  Attention ! Aucune vÃ©rification n'est faite !
 */
function enregistrerMembre($bdd, $pseudo, $pass, $email) {
    try {
        $Req = $bdd->prepare("INSERT INTO membres (id_membre,pseudo,pass,email) VALUES('',?,?,?)");
        $Req->execute(array($pseudo, $pass, $email));
    } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
}

/**
  Verifie le pseudo et le mot de passe du membre qui essaye de se connecter
 */
function verifConnexion($bdd, $pseudo, $pass) {
    try {
        $Req = $bdd->prepare("SELECT * FROM membres WHERE pseudo LIKE ? AND pass LIKE ?");
        $Req->execute(array($pseudo, $pass));
    } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
    $var = $Req->rowCount() == 1 ? true : false;
    return $var;
}

/**
  Renvoie TRUE si une variable de session est active pour l'utilisateur
 */
function isConnecte() {
    $var = isset($_SESSION['login']) ? true : false;
    return $var;
}

/**
  redirige l'utilisateur si il n'est pas connectÃ©
 */
function verifierMembre() {
    if (!isConnecte()) {
        header("Location: index.php?err_identif=true");
    }
}

/**
 *
 * @param type $login le login de l'utilisateur
 * @param type $bdd la bdd
 * @return int retourne l'id du membre passÃ© en parametre 
 */
function getId($login, $bdd) {
    try {
        $Req = $bdd->prepare("SELECT id_membre FROM membres WHERE pseudo LIKE ?");
        $Req->execute(array($login));
    } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
    if ($Req->rowCount() == 1) {
        $res = $Req->fetch(PDO::FETCH_OBJ);
        return $res->id_membre;
    }
}

/**
 *
 * @param type $idMembre l'id du membre
 * @param type $bdd la bdd
 * @return bool true si le membre existe, false sinon 
 */
function membreExiste($idMembre, $bdd) {
    try {
        $Req = $bdd->prepare("SELECT * FROM membres WHERE id_membre LIKE ?");
        $Req->execute(array($idMembre));
    } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
    if ($Req->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function getGravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

?>