<?php

/*
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$nomClasse = 'Twig_Extension';
        echo "serching in :";
        echo dirname(__FILE__) . '/Classes/' . str_replace(array('_', "\0"), array('/', ''), $nomClasse) . '.php';

if (is_file($file = dirname(__FILE__) . '.' . str_replace(array('_', "\0"), array('/', ''), $nomClasse) . '.php')) {
        require $file;
    }
?>
