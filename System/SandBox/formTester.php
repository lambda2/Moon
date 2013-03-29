<?php

/**
 * Va nous permettre d'effectuer quelques essais sur les formulaires.
 */

$orm = OrmFactory::getOrm();
$astre = $orm->getAllColumnsFrom('astre');
var_dump($astre);

foreach ($astre as $key => $value) {
	$e = FieldFactory::createField($value->Type,$value->Field);
	echo($e);
}


?>