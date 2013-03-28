<?php

/**
 * Va nous permettre d'effectuer quelques essais sur les formulaires.
 */

$orm = OrmFactory::getOrm();
$astre = $orm->getAllColumnsFrom('astre');
var_dump($astre);

$e = FieldFactory::createField($astre['nom_astre']->Type,$astre['nom_astre']->Field);

echo($e);

?>