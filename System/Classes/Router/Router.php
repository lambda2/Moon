<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Classe chargée des gérer les routes entre le fichier index.php et les
 * ressources, de choisir les bons controlleurs etc...
 * @author lambda2
 */
class Router {

    protected $requestedFile;

    public function __construct() {
        
    }

    public function route($params) {
        
        $c = new Home('');
        $c->setTemplate('index.twig');
        $c->render();
        /*if (isset($params['p'])) {
            // Et que cette page existe
            if (file_exists('Controleurs/' . strtolower($params['p']) . '.php')) {
                // On la charge
                include_once('Controleurs/' . strtolower($params['p']) . '.php');
            }
            else {
                // Sinon, page introuvable
                include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
            }
        }
        else {
            // Si aucune page n'est définie, on va sur la page d'accueil
            include_once('Controleurs/accueil_m.php');
        }*/
    }

}

?>
