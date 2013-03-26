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

	echo MoonChecker::analyze();

	$orm = OrmFactory::getOrm();
	echo '<h3>Debug outputs for <i>Multi mapping</i> between instances</h3>';

	$astres = Moon::getAllHeavy('astre');
	echo '<li> First, we get One astre </li>';
	$astre = $astres[0];
    echo "<li> we selected : <pre>".($astre)."</pre></li>";
    echo "<li> testons astre.nom : {".$astre->__get('nom')."}</li>";
    echo "<li> testons astre.distance : {".$astre->__get('distance')."}</li>";
    /*
    echo "<li> testons astre.distance.distance : {"
    .$astre->__get('distance')->__get('distance')."}</li>";
    echo "<li> testons astre.distance.astre_depart : {"
    .$astre->__get('distance')->__get('astre_depart')."}</li>";
    echo "<li> testons astre.distance.astre_depart.nom : {"
    .$astre->__get('distance')->__get('astre_depart')->__get('nom')."}</li>";
    echo "<li> testons astre.distance.astre_fin.nom : {"
    .$astre->__get('distance')->__get('astre_fin')->__get('nom')."}</li>";
    */


    echo "<li> affichons chaque distance... :</li>";
    foreach ($astre->__get('distance') as $key => $value) {
    	echo 'BWA';
    	echo "<li> testons astre.distance.astre_depart.nom : {"
	    	.$value->__get('astre_depart')->__get('nom')."}</li>";
	    echo "<li> testons astre.distance.astre_fin.nom : {"
	    	.$value->__get('astre_fin')->__get('nom')."}</li>";
    }

} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>