<?php

/**
 * Pour raccourcir les urls...
 */
const sep = DIRECTORY_SEPARATOR;

$rpath = __DIR__.'/../';

/**
 * La configuration (c'est à dire les logins de la base de données, un éventuel
 * préfixe de table et les modes de développement).
 */

/**
 * Le pathFinder, qui va nous permettre d'avoir un autoloader intelligent.
 */
require_once(__DIR__.'/../'.'System' . sep . 'pathfinder.php');

/**
 * L'autoloader, qui se sert du pathfinder
 */
function __autoload($nomClasse) {
    if(strlen($nomClasse) <= 1)
        return false;
    
    /**
    * Le moteur de template
    * @uses Twig -> http://twig.sensiolabs.org/
    * Vu qu'on a déja notre propre moteur de chargement, on va devoir associer Twig a notre autoloader.
    */
    if (is_file($file = dirname(__FILE__) . sep . str_replace(array('_', "\0"), array('/', ''), $nomClasse) . '.php')) {
        require $file;
    }

    /**
     * Et on laisse la main à notre PathFinder
     */
    $foundClasses = PathFinder::getClasses(__DIR__.'/../'.'System');
    $pos          = strrpos($nomClasse, '\\');
    if ($pos > 0) {
        $nomClasse = substr($nomClasse, $pos + 1);
    }

    if (array_key_exists($nomClasse, $foundClasses)) 
    {
        require_once($foundClasses[$nomClasse]);
    }
    else 
    {
        $foundControllers = PathFinder::getControllers('.');
        if (array_key_exists($nomClasse, $foundControllers)) 
        {
            require_once($foundControllers[$nomClasse]);
        }
        else 
        {
            $foundModels = PathFinder::getModeles('.');
            if (array_key_exists($nomClasse, $foundModels)) 
            {
                require_once($foundModels[$nomClasse]);
            }

        }
    }
    
    if(!class_exists('Annotation_Target'))
    {
        require __DIR__.'/../'.'System/Addendum/annotations.php';
    }
}

/**
 * Le pathfinder se charge aussi d'inclure les helpers, qui ne sont pas des
 * classes, et qui ne peuvent donc pas etre chargées automatiquement.
 * Rapellons que les helpers sont des fichiers regroupant par theme des 
 * fonctions utilitaires utilisables à n'importe quel endroit.
 * 
 */
$helpers = PathFinder::getHelpers(__DIR__.'/../'.'System');
foreach ($helpers as $class => $url) {
    require_once($url);
}

mb_internal_encoding("UTF-8");
session_start();

?>
