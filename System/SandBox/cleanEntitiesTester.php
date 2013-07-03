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
    $filter = Entities::getFilter();
    echo $filter;
    $a = 'membre[id_membre=2][id_groupe="lol"][texte="Bonjour ! Je suis un papa ? Papa Noeeeel ! /lol "hello!" #kikooo"]';
    preg_match_all($filter,$a,$matches,PREG_SET_ORDER);
    var_dump($matches);
    echo "<h3>Step One : Création d'un nouveau lot d'entitiées (contrat)</h3>";
    var_dump(preg_last_error());
    
    $classe = Membre::loadFromSession();
    echo '<p>-------------------------------------<p>';

    echo "<h3>Prenons les projets associés a ces contrats</h3>";
    $projets = $classe->membre_equipe->equipe->project;
    echo '<p>-------------------------------------<p>';
    
    echo "<h3>Prenons les equipes associées a ces projets</h3>";
    echo '<p>-------------------------------------<p>';
    $links = $classe->membre_reseau;
    foreach($links as $l)
    {
        var_dump($l->reseau_social->nom);
    }

    echo "<h3>Prenons les membre_equipes associées a ces equipes</h3>";
    var_dump($projets->generateQueryFromHistory()->getQuerySql());
    var_dump($projets->generateQueryFromHistory());
    var_dump($projets);
    foreach($projets as $elt)
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
