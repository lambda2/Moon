<?php

/**
 * Retourne l'erreur correspondant au code fourni.
 * C'est également dans cette fonction que sont définies les erreurs
 */
function getError($codeErr) {
    $erreur = "";
    switch ($codeErr) {
        case 0:
            $erreur = "Une erreur est survenue. Merci de nous en excuser";
            break;
        case 1:
            $erreur = "La page demandée est indisponible ou inexistante";
            break;
        case 2:
            $erreur = "Cette offre d'emploi n'existe pas";
            break;
        case 3:
            $erreur = "Tous les champs requis n'ont pas étés remplis";
            break;
        case 4:
            $erreur = "Le numéro de téléphone spécifié semble invalide";
            break;
        case 5:
            $erreur = "L'adresse email spécifiée semble invalide";
            break;
        case 6:
            $erreur = "Vous n'avez pas les droits requis pour acceder a cette page. Si vous êtes déconnecté, essayez de vous connecter.";
            break;
        default:
            $erreur = "Une erreur est survenue. Merci de nous en excuser";
            break;
    }
    return $erreur;
}

// Affiche l'erreur dans la page spécifiée (accueil par défaut)
// Des paramètres peuvent être passés a la page (comme une id ou du genre)
function redirectError($codeErr = 0, $page = '') {
    if (!headers_sent()) {
        if ($page != '')
            $page = '&p=' . $page;
        header('Location: index.php?err=' . $codeErr . $page);
    }
    else {
        if ($page != '')
            $page = '&p=' . $page;
        echo('<script language="javascript">document.location.href="index.php?err=' . $codeErr . $page . '"</script>');
    }
}

/**
 * Retourne le message correspondant au code fourni.
 * C'est également dans cette fonction que sont définis les messages
 */
function getStatut($codeStat) {
    $statut = "";
    switch ($codeStat) {
        case 0:
            $statut = "Action réalisée avec succès !";
            break;
        case 1:
            $statut = "Votre demande a bien été prise en compte";
            break;
        case 2:
            $statut = "Votre offre a bien été publiée";
            break;
        case 3:
            $statut = "Vous avez bien été déconnecté";
            break;
        case 4:
            $statut = "Connexion réussie";
            break;
        default:
            $statut = "Action réalisée avec succès !";
            break;
    }
    return $statut;
}

// Affiche le message dans la page spécifiée (accueil par défaut)
// Des paramètres peuvent être passés a la page (comme une id ou du genre)
function redirectStatut($codeStat = 0, $page = '') {
    if (!headers_sent()) {
        if ($page != '')
            $page = '&p=' . $page;
        header('Location: index.php?stat=' . $codeStat . $page);
    }
    else {
        if ($page != '')
            $page = '&p=' . $page;
        echo('<script language="javascript">document.location.href="index.php?stat=' . $codeStat . $page . '"</script>');
    }
}

/**
 * Redirige l'utilisateur sur une page d'erreur 404
 * @param type $args les parametres a passer en plus dans l'url
 */
function pageNotFound($args = '', $rel = '') {
    if ($args != '')
        $args = '?p=404&' . $args;
    else
        $args = '?p=404';
    if (!headers_sent())
        header('Location: ' . $rel . 'index.php' . $args);
    else
        echo('<script language="javascript">document.location.href="' . $rel . 'index.php' . $args . '"</script>');
}

function displayMoonException($exc) {
    echo '<hr>';
    echo '<h1 style="font-family: monospace;"><span style="color: #999;">MOON FRAMEWORK</span><span style="color: #666;"> - What a lunar error !</span></h1>';
    echo '<hr><br>';
    echo '<table>' . $exc->xdebug_message . '</table>';
    echo '<br><hr><br>';
    var_dump($exc);
    echo '<br><hr><br>';
}

function dbg($msg, $severity = 0) {
    if (Core::getInstance()->debug()) {
        Core::getInstance()->debug()->addReport($msg, $severity);
        //echo $report;
    }
}

?>