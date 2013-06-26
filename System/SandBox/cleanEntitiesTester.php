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
    echo "<h3>Step One : Création d'un nouveau lot d'entitiées (contrat)</h3>";
    $classe = Moon::getEntities('contrat');
    var_dump($classe); 
    echo '<p>-------------------------------------<p>';

    echo "<h3>Et affichons ces contrats !</h3>";
    $query = new Query();
    $query_two = new Query();
    $query->select('*')->from('project');
    $query_two->select('id_contrat')->from('contrat');
    $query->in('id_project',$query_two);
    $query->in('id_project',$query_two);

    $q = $classe->loadFromDatabase();
    var_dump($q->getQuerySql());
    foreach($classe as $entitiy)
    {
        echo $entity;
    }

    echo '<p>-------------------------------------<p>';
    echo "<h3>Prenons les projets associés a ces contrats</h3>";
    $projets = $classe->project;
    var_dump($projets); 
    $q = $projets->loadFromDatabase();
    var_dump($q->getQuerySql());
    echo '<p>-------------------------------------<p>';
    
    echo "<h3>Prenons les equipes associées a ces projets</h3>";
    $equipes = $projets->equipe;
    var_dump($equipes); 
    $q = $equipes->loadFromDatabase();
    var_dump($q->getQuerySql());
    echo '<p>-------------------------------------<p>';

    echo "<h3>Prenons les membre_equipes associées a ces equipes</h3>";
    $equipes = $equipes->membre_equipe->membre->fonction->role;
    var_dump($equipes); 
    $q = $equipes->loadFromDatabase();
    echo '<code style="width: 500px; display: block;">'.$q->getQuerySql().'</code>';
    echo '<p>-------------------------------------<p>';
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}

?>
