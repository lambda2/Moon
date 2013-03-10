<?php

/**
 * @TODO modifier cette horreur.
 * > moteur de template ? (twig, smarty...)
 * > laisser le soin de faire le routage à une classe dédiée ?
 * > système de binding d'url ?
 * 
 */

// Pour vérifier a tout moment qu'on est dans l'index.php
$INDEX = true;

// on ajoute l'autoload des classes et on crée la connexion
require_once('System/loader.php');

/**
 * Enfin, on démarre notre moteur
 */
Core::startEngine();

Core::route($_GET);

//$membre = Configuration::getUser();
/*
if (isset($_GET['m'])) {
    include_once('Callers/index.php');
} 
elseif (isset($_GET['s'])) {
    include_once('System/'.$_GET['s'].'.php');
}else {
    // On ajoute le header et le menu des membres
    include_once('Webroot/header_m.php');
    include_once('Webroot/menu_m.php');

// Si on a une page a charger
    if (isset($_GET['p'])) {
        // Et que cette page existe
        if (file_exists('Controleurs/' . strtolower($_GET['p']) . '.php')) {
            // On la charge
            include_once('Controleurs/' . strtolower($_GET['p']) . '.php');
        } else {
            // Sinon, page introuvable
            include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
        }
    } else {
        // Si aucune page n'est définie, on va sur la page d'accueil
        include_once('Controleurs/accueil_m.php');
    }


// On ajoute le footer
    include_once('Webroot/footer_m.php');
}
*/
?>