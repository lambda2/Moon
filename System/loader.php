<?php

/**
 * Pour raccourcir les urls...
 */
const sep = DIRECTORY_SEPARATOR;

/**
 * La configuration (c'est à dire les logins de la base de données, un éventuel
 * préfixe de table et les modes de développement).
 */

/**
 * Le pathFinder, qui va nous permettre d'avoir un autoloader intelligent.
 */
require_once('System' . sep . 'pathfinder.php');

/**
 * L'autoloader, qui se sert du pathfinder
 */
function __autoload($nomClasse) {
    
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
    $foundClasses = PathFinder::getClasses('System');
    //var_dump($foundClasses);
    $pos          = strrpos($nomClasse, '\\');
    if ($pos > 0) {
        $nomClasse = substr($nomClasse, $pos + 1);
    }
    if (array_key_exists($nomClasse, $foundClasses)) {
        require_once($foundClasses[$nomClasse]);
    }
}

/**
 * Le pathfinder se charge aussi d'inclure les helpers, qui ne sont pas des
 * classes, et qui ne peuvent donc pas etre chargées automatiquement.
 * Rapellons que les helpers sont des fichiers regroupant par theme des 
 * fonctions utilitaires utilisables à n'importe quel endroit.
 * 
 */
$helpers = PathFinder::getHelpers('System');
foreach ($helpers as $class => $url) {
    require_once($url);
}

mb_internal_encoding("UTF-8");
session_start();

/**
 * Ci dessous, des aspects vus un peu trop tot. Le framework n'est pas
 * encore pret pour ça. Allons y doucement.
 */
/*
  // on charge le fichier de langue
  require_once('Langs/' . chargerLangue());

  require_once('System/pagination.php');

  if (isset($_GET['p']) && Page::pageExiste(htmlentities($_GET['p']), $GLOBALS['bdd']))
  $page = $_GET['p'];
  $page = new Page(Page::getIdPage($page, $GLOBALS['bdd']),$GLOBALS['bdd']);

  gererAcces($role, $page->getDroit());
 */
?>
