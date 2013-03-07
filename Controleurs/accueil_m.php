<?php
              
/*redirectStatut(0, "profil");
$realisationApp = Realisation::getRealisations($GLOBALS['bdd'], 'app');
$realisationSite = Realisation::getRealisations($GLOBALS['bdd'], 'site');
*/
$animal = EntityLoader::getClass("vehicule_lw");
var_dump($animal->loadBy("id", '1'));
$animal->autoLoadLinkedClasses();
//var_dump($animal);
include_once('Vues/accueil_m.php');
?>