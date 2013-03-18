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

function testResults($function,$args=array(),$source=null){
    echo "<br>============== Calling $function() with args ["
            .arr2str($args, ', ')
            ."]===============<br>";
    if(isNull($source)){
        var_dump(call_user_func_array($function, $args));
    }
    else {
        var_dump( call_user_method_array($function, $source, $args));
    }
    echo "<br><br>";
}

function arr2str($array,$sep=' '){
    return implode($sep, $array);
}
?>
