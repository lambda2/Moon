<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$orm = OrmFactory::getOrm();

var_dump($orm);

$tables = $orm->getAllRelationsFrom();
testResults('getAllTables', array(), $orm);


testResults('getAllColumnsFrom', array('astre'), $orm);
testResults('getAllRelations', array(), $orm);
testResults('getAllRelationsFrom', array('astre'), $orm);
testResults('getAllRelationsFrom', array('distance'), $orm);
testResults('getMoonLinksFrom', array('distance'), $orm);
testResults('query', array("select * from astre", array()), $orm);


$astres = Moon::getAllHeavy('distance');
foreach ($astres as $value) {
    var_dump($value->getLinkedClasses());
}


?>
