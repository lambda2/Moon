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
    $classe = Moon::getEntities('project');
    echo '<p>-------------------------------------<p>';
    foreach($classe as $cl)
        echo ' - '.$cl->nom.'<br>';
    echo '<h2> COUNT : '.count($classe).'</h2>';
    echo '<p>-------------------------------------<p>';
    echo '<p>After [nom!="Moon"]</p>';
    $classe->filter('[nom!="Moon"]');
    foreach($classe as $cl)
        echo ' @ '.$cl->nom.'<br>';
    echo '<h2> COUNT : '.count($classe).'</h2>';

    echo '<p>-------------------------------------<p>';
    echo '<p>After [id_projet>="2"]</p>';
    $classe->filter('[id_projet>="2"][id_projet!=10]');
    foreach($classe as $cl)
        echo ' @ '.$cl->nom.'<br>';
    echo '<h2> COUNT : '.count($classe).'</h2>';
 /*
    echo '<p>-------------------------------------<p>';
    echo '<p>After [id_projet<"10"]</p>';
    $classe->filter('[id_projet<"20"]');
    foreach($classe as $cl)
        echo ' @ '.$cl->equipe->nom.'<br>';
    echo '<h2> COUNT : '.count($classe).'</h2>';


    echo '<p>-------------------------------------<p>';
    echo '<p>After [equipe.nom]</p>';
    $classe->filter('[equipe.nom="Mammuth"]');
    foreach($classe as $cl)
        echo ' @ '.$cl->nom.'<br>';
    echo '<h2> COUNT : '.count($classe).'</h2>';

	$orm = OrmFactory::getOrm();
	echo '<h3>Tests on the ORM requests</h3>';
    $r = $orm->select('*')->from('project_log')->where('id_project','3')->fetchArray();
    echo '<p>'.count($r).' élement(s) recu(s)</p>';
    $r = $orm->select('*')->from('project_log')->where('id_project','3')->limit(10)->fetchArray();
    echo '<p>'.count($r).' élement(s) recu(s) avec une limite de 10</p>';
    $r = $orm->select('*')->from('project_log')->where('id_project','3')->limit(10)->orderBy('id_commit','asc');
	testResults('fetchArray', array(), $r);
	$ent = testResults('fetchEntities', array(), $r);
    $p = $ent->project;
    foreach($p as $pro)
    {
        echo $pro->getNom();
    }
    echo '<p>'.count($r->fetchArray()).' élement(s) recu(s) avec une limite de 10 et un tri par message</p>';
    */
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>
