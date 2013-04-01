<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


//echo MoonChecker::run();

echo '<h3>Debug outputs for <i>Multi mapping</i> between instances</h3>';

echo '<li> First, we get One astre </li>';
$f          = EntityLoader::getClass('astre');
var_dump($f);
$pri        = $f->getValuedPrimaryFields();
var_dump($pri);

$testArray = array(
	'type'=>'int',
	'name'=>'systeme',
	'foreignTarget'=>'systeme.id_systeme'
	);
$toParam = arr2paramArray($testArray);
echo 'vers param : '.$toParam.' <br>';

$toArray = param2arr($toParam);
echo 'vers array : '.arr2str($toArray,',').' <br>';


?>