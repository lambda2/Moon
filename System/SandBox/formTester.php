<?php

/**
 * Va nous permettre d'effectuer quelques essais sur les formulaires.
 */

$orm = OrmFactory::getOrm();
$fields = $orm->getAllEntityFields('astre');
var_dump($fields);

foreach ($fields as $key => $value) {
	$formField = $value->getHtmlField();
	echo $formField;
}


?>