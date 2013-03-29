<?php

/**
 * Va nous permettre d'effectuer quelques essais sur les formulaires.
 */

$orm = OrmFactory::getOrm();
$astre = $orm->getAllColumnsFrom('astre');
var_dump($astre);

$form = new Form('test');
foreach ($astre as $key => $value) {
	$e = FieldFactory::loadFromStdObject($value);
	$form->addField($e);
}
echo($form);


?>