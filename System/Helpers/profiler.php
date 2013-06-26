<?php

/*
 * This file is part of the Moon Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Ce fichier va accueilir des fonction élémentaires de profilage dont nous
 * pourrions avoir besoin pour calculer ce que nous coutent nos méthodes...
 */

function testResults($function,$args=array(),$source=null,$desc=''){

    $return = null;

    echo "<br>============== Calling $function() with args ["
            .arr2str($args, ', ')
            ."]===============<br>";
    if(!isNull($desc))
    {
        echo '<p>'.$desc.'</p>';
    }
    if(isNull($source)){
        $return = (call_user_func_array($function, $args));
    }
    else {
         $return = (call_user_method_array($function, $source, $args));
    }
    var_dump($return);
    echo "<br><------------------- End -------------------><br>";
    return $return;
}

function arr2str($array,$sep=' '){
    return implode($sep, $array);
}
?>
