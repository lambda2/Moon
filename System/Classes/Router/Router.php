<?php

/*
 * This file is part of the moon framework.
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
        
        
        
        if (isset($params['p'])) {
            // Et que cette page existe
            if (file_exists('Controllers/' . $params['p'] . '.php')) {
                // On la charge
                $classe = $params['p'];
                $c = new $classe($params);
                $c->render();
            }
            else {
                // Sinon, page introuvable
                include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
            }
        }
        else {
            // Si aucune page n'est définie, on va sur la page d'accueil
            $c = new Home($params);
            $c->render();
        }
    }

}

?>
