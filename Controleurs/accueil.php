<?php
               
$tra = 'Trajet';
$tarjet = new $tra();
$mot = 'Moteur';
try {
    $d = class_exists($mot);
    if($d)
    $moteur = new $mot();
    else
        dbg("La classe {$mot} n'existe pas !");
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}

var_dump($tarjet);
var_dump($moteur);
include_once('Vues/accueil.php');
?>