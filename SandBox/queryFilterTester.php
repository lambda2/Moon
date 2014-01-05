<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function showEach($elt, $hard=1)
{
    $elt->loadFromDatabase();
    foreach ($elt as $key => $value)
    {
        if($hard)
        var_dump($key, $value);
        else
            echo "[$key] : [$value]";
    }
}
try 
{
    $classe = Moon::getEntities("contrat");
    var_dump($classe);
    showEach($classe,0);
    testResults('filter', array('[date_fin>=now()]'), $classe);
    showEach($classe,0);
    //$classe->clearHistory();
    testResults('filter', array('[date_fin>=now()]+[date_fin is null]'), $classe);
    showEach($classe,0);
    testResults('filter', array('[nom="facture"]'), $classe->etat_contrat);
    showEach($classe->etat_contrat,0);

    
} 
catch (Exception $exc) 
{
            displayMoonException($exc);
}


?>