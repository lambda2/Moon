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
    $filter = "/\[(?P<attribute>([A-Za-z_]*))(?P<operator>\=|!\=|<|>|<\=|>\=)(?P<value>([\d]*)|(\"[\w\.]*\"))\]/";
    echo $filter;
    $a = 'membre[id_membre=2][id_groupe="lol"]';
    preg_match_all($filter,$a,$matches,PREG_SET_ORDER);
    var_dump($matches);
    echo "<h3>Step One : Création d'un nouveau lot d'entitiées (contrat)</h3>";
    var_dump(preg_last_error());
    $classe = Moon::getEntities('contrat');
    echo '<p>-------------------------------------<p>';

    echo "<h3>Et affichons ces contrats !</h3>";
    foreach($classe as $entity)
    {
        echo $entity;
    }

    echo '<p>-------------------------------------<p>';
    echo "<h3>Prenons les projets associés a ces contrats</h3>";
    $projets = $classe->project;
    var_dump($projets);
    var_dump($projets->generateQueryFromHistory()->getQuerySql());
    $projets->where('id_projet',2);
    var_dump($projets);
    var_dump($projets->generateQueryFromHistory()->getQuerySql());
    echo '<p>-------------------------------------<p>';
    
    echo "<h3>Prenons les equipes associées a ces projets</h3>";
    $equipes = $projets->equipe;
    echo '<p>-------------------------------------<p>';

    echo "<h3>Prenons les membre_equipes associées a ces equipes</h3>";
    $equipes = $equipes->membre_equipe->where('id_membre',1)->membre->fonction->role;
    var_dump($equipes->generateQueryFromHistory()->getQuerySql());
    echo '<p>-------------------------------------<p>';
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>
