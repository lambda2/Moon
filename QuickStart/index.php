<?php

/**
 * @TODO modifier cette horreur.
 * > moteur de template ? (twig, smarty...)                     -   ✔
 * > laisser le soin de faire le routage à une classe dédiée ?  -   ✔
 * > système de binding d'url ?                                 -   ✔
 * 
 */

// Pour vérifier a tout moment qu'on est dans l'index.php
$INDEX = true;

// on ajoute l'autoload des classes et on crée la connexion
require_once(__DIR__.'/../System/loader.php');

/**
 * Enfin, on démarre notre moteur
 */
Core::startEngine();

Core::route($_GET);

?>