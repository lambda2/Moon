<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
try 
{

	$orm = OrmFactory::getOrm();
	echo '<h3>Tests on the ORM requests</h3>';
    $r = $orm->select('*')->from('project_log')->where('id_project','3')->fetchArray();
    echo '<p>'.count($r).' élement(s) recu(s)</p>';
    $r = $orm->select('*')->from('project_log')->where('id_project','3')->limit(10)->fetchArray();
    echo '<p>'.count($r).' élement(s) recu(s) avec une limite de 10</p>';
    $r = $orm->select('*')->from('project_log')->where('id_project','3')->limit(10)->orderBy('id_commit','asc');
	testResults('fetchArray', array(), $r);
    echo '<p>'.count($r->fetchArray()).' élement(s) recu(s) avec une limite de 10 et un tri par message</p>';
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>
