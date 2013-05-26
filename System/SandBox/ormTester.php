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
	/*echo '<h3>Debug outputs for <i>Orm</i> class</h3>';

	var_dump($orm);

	//$tables = $orm->getAllRelationsFrom();
	testResults('getAllTables', array(), $orm);


	testResults('getAllColumnsFrom', array('astre'), $orm);
	testResults('getAllRelations', array(), $orm);
	testResults('getAllRelationsFrom', array('astre'), $orm);
	testResults('getAllRelationsFrom', array('distance'), $orm);
	testResults('getMoonLinksFrom', array('distance'), $orm);

	echo '<h3>Debug outputs for <i>Orm</i> class</h3>';
	echo '<h5>→ External relations detection</h5>';

	testResults('getAllRelationsWith', array('astre'), $orm);
	testResults('getMoonLinksFrom', array('astre'), $orm);
	testResults('getMoonLinksFrom', array('astre',true), $orm);

*/
    $pseudo = 'lambda2';
    $user = Moon::get('membre','pseudo',$pseudo);

    if(!$user->exists())
        echo 'Utilisateur inexistant : '.$user.'<br>';

    testResults('__toString',array(),$user);

	$membre_equipe = testResults('__get', array('membre_equipe'), $user,
    'On récupère les equipes dont le membre '.$pseudo.' fait partie');
	
    $equipe = testResults('__get', array('equipe'), $membre_equipe,'On récupère les equipes de '.$pseudo.', depuis membre_equipe');
    $project = testResults('__get', array('project'), $equipe,'On récupère les projets de l\'equipe '.$equipe);

/*
    var_dump($externals);
    var_dump(Core::getInstance());

	echo '<h3>Debug outputs for <i>Entity</i> class</h3>';
	$astres = Moon::getAllHeavy('astre');

	testResults('__get', array('distance'), $astres[0]);
	testResults('query', array("select * from astre where ? = 1"
		, array('id_astre')), $orm);

	testResults('getNom', array(), $astres[0]);
	testResults('getNom_astre', array(), $astres[0]);
	testResults('Nom_astre', array(), $astres[0]);
	testResults('Nom', array(), $astres[0]);
	testResults('getSysteme', array(), $astres[0]);
	testResults('systeme', array(), $astres[0]);
	testResults('__get', array('systeme'), $astres[0]);
	testResults('__get', array('pouet'), $astres[0]);
*/
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>
