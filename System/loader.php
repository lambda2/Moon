<?php

// les exceptions particulières du framework et les entités
require_once('System/Classes/Exceptions.php');
require_once('System/Classes/Configuration.php');
require_once('System/Classes/Entity.php');

// on ajoute l'autoload des classes et on crée la connexion
require_once('Helpers/errors.php');
require_once('Helpers/common.php');
require_once('Helpers/mail.php');
require_once('Helpers/user.php');

require_once('Config/configuration.php');

require_once('System/Debug/Debug.php');

Configuration::startEngine($driver, $host, $dbname, $login, $pass, $dev_mode, $db_prefix);
$GLOBALS['System'] = Configuration::getInstance();

function __autoload($nomClasse) {
    $pos = strrpos($nomClasse, '\\');
    if ($pos > 0) {
        $nomClasse = substr($nomClasse, $pos + 1);
    }
    if(is_file('Modeles' . DIRECTORY_SEPARATOR . ($nomClasse) . '.php')){
        require_once('Modeles' . DIRECTORY_SEPARATOR . ($nomClasse) . '.php');
    }
    else if(is_file('Controleurs' . DIRECTORY_SEPARATOR . ($nomClasse) . '.php')){
        require_once('Controleurs' . DIRECTORY_SEPARATOR . ($nomClasse) . '.php');
    }
    else if(Configuration::isValidClass($nomClasse)){
        
    }
    else if(is_file('System' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . ($nomClasse) . '.php')){
        require_once('System' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . ($nomClasse) . '.php');
    }
    else if(is_file('System' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR. ($nomClasse) . '.php')){
        require_once('System' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR. ($nomClasse) . '.php');
    }
    else if(is_file('System' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'yaml' . DIRECTORY_SEPARATOR. ($nomClasse) . '.php')){
        require_once('System' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'yaml' . DIRECTORY_SEPARATOR. ($nomClasse) . '.php');
    }
}





if (isset($_GET['lang'])) {
    definirLangue($_GET['lang']);
}

mb_internal_encoding("UTF-8");
session_start();



// on charge le fichier de langue
require_once('Langs/' . chargerLangue());

require_once('System/pagination.php');
/*
if (isset($_GET['p']) && Page::pageExiste(htmlentities($_GET['p']), $GLOBALS['bdd']))
        $page = $_GET['p'];
$page = new Page(Page::getIdPage($page, $GLOBALS['bdd']),$GLOBALS['bdd']);

gererAcces($role, $page->getDroit());
*/
?>
