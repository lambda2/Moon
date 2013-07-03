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
    /*
    $filter = "/\[(?P<attribute>([A-Za-z_]*))(?P<operator>\=|!\=|<|>|<\=|>\=)(?P<value>([\d]*)|(\"[\w\.]*\"))\]/";
    echo $filter;
    $a = 'membre[id_membre=2][id_groupe="lol"]';
    preg_match_all($filter,$a,$matches,PREG_SET_ORDER);
    var_dump($matches);
    echo "<h3>Step One : Création d'un nouveau lot d'entitiées (contrat)</h3>";
    var_dump(preg_last_error());
    */
    $classe = Moon::get('contrat','etat','2');
    var_dump($classe->nom);
    echo '<p>-------------------------------------<p>';

    echo "<h3>Et affichons ces contrats !</h3>";
    var_dump($classe);
    echo '<p>-------------------------------------<p>';
    echo "<h3>Prenons les projets associés a ces contrats</h3>";
    $projets = $classe->project;
    echo '<p>-------------------------------------<p>';
    
    echo "<h3>Prenons les equipes associées a ces projets</h3>";
    $equipes = $projets->equipe;
    echo '<p>-------------------------------------<p>';

    echo "<h3>Prenons les membre_equipes associées a ces equipes</h3>";
    $equipes = $equipes->membre_equipe->membre->fonction->role;
    var_dump($equipes->generateQueryFromHistory()->getQuerySql());
    var_dump($equipes->generateQueryFromHistory());
    var_dump($equipes);
    foreach($equipes as $elt)
    {
        echo $elt->nom;
    }
    echo '<p>-------------------------------------<p>';
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>
