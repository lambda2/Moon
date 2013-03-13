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

    /**
     * Va tenter de trouver le bon controlleur dans le cas ou l'url
     * possède une hiérarchie.
     * La méthode procède par ordre de priorité :
     * 1 - Dans le fichier des routes de l'utilisateur, si une route est définie
     * pour cette URL.
     * 2 - Dans les controlleurs, si un controlleur a le nom du premier élément 
     * de l'url 
     * 
     * @TODO : Essayer de contruire dynamiquement un controlleur si le 
     * controlleur demandé correspond a une entitée. Il faut alors commencer a
     * se pencher sur la gestion des accès (classe Controller ? access.yml ?)
     * 
     * @return mixed False si aucun controlleur n'a été trouvé, sinon le nom du
     * controlleur à charger.
     */
    protected function getGoodControler() {
        $controlleur = false;
        if (isset($_SERVER['REDIRECT_URL'])) {

            // L'url demandée par l'internaute
            $request = $_SERVER['REDIRECT_URL'];

            // On cherche dans les routes définies par l'utilisateur
            $foundInRoutes = $this->findControllerInRoutes($request);
            if ($foundInRoutes != false)
                return $foundInRoutes;

            // On cherche dans les controleurs
            $foundInCtrls = $this->findControllerInFiles($request);
            if ($foundInCtrls != false)
                return $foundInCtrls;
        }
        else {
            echo ("aucune url de redirection...");
        }
        return $controlleur;
    }

    /**
     * Cherche dans les routes définies par l'utilisateur si la requete ne 
     * correspond pas à une route vers un controlleur.
     * @param string $requestedUrl l'url de la requete
     * @return mixed False si aucun controlleur n'a été trouvé, sinon le nom du
     * controlleur à charger.
     */
    protected function findControllerInRoutes($requestedUrl) {
        $controlleur = false;
        $a           = Core::routes();
        foreach (Core::routes()->childs() as $url => $ctrl) {
            if ($this->isMyController($url, $requestedUrl)) {
                $controlleur = $ctrl;
            }
        }
        return $controlleur;
    }

    /**
     * Cherche dans les controleurs si la requete ne correspond pas à un 
     * controlleur.
     * @param string $requestedUrl l'url de la requete
     * @return mixed False si aucun controlleur n'a été trouvé, sinon le nom du
     * controlleur à charger.
     */
    protected function findControllerInFiles($requestedUrl) {
        $controlleur = false;
        $split       = self::splitUrl($requestedUrl);

        if (count($split) == 0)
            return false;

        if (is_file('Controllers' . DIRECTORY_SEPARATOR . $split[0] . '.php'))
            $controlleur = $split[0];

        /** @TODO : Peut etre faudrait il chercher aussi les controlleurs 
         * sans prendre en compte la casse de caractères (case-insensitive) ? */
        return $controlleur;
    }

    /**
     * Regarde si l'url de la requete correspond à une route vers un controlleur
     * défini par l'utilisateur.
     * @param string $url l'url de la route pour un controlleur
     * @param string $request l'url de la requete de l'internaute
     * @return boolean true si la requete correspond, false sinon
     */
    protected function isMyController($url, $request) {
        $split = self::splitUrl($request);
        if (count($split) > 0 && strcasecmp($split[0], $url) == 0)
            return true;
        return false;
    }

    /**
     * Recoit une url et renvoie un tableau composé de tous les éléments de 
     * l'url (sauf les parametres). Si l'option [withoutRoot] est à true, les 
     * éléments composant la racine du site ne seront pas renvoyés.
     * @param string $url l'url à découper.
     * @param boolean $withoutRoot si est à true, les éléments composant 
     * la racine du site ne seront pas renvoyés.
     * @return array un tableau composé des éléments de l'url.
     */
    public static function splitUrl($url, $withoutRoot = true) {
        $splitted = split('/', $url);
        if ($withoutRoot && Core::isStarted()) {
            $root = Core::opts()->system->siteroot;
            $root = str_replace('/', '', $root);
            if (in_array($root, $splitted)) {
                $splitted = array_remove_value($splitted, $root);
            }
        }
        return array_index_clean($splitted);
    }

    /**
     * Méthode principale de la classe, chargée de déterminer le controlleur 
     * à apeller en fonction des paramètres de la requete.
     * @param type $params les paramètres de la requete.
     */
    public function route($params) {

            if (isset($params['p'])) {
                // Et que cette page existe
                if (file_exists('Controllers/' . $params['p'] . '.php')) {
                    // On la charge
                    $classe = $params['p'];
                    $c      = new $classe($params);
                    $c->index();
                    //$c->render();
                }
                else {
                    // Sinon, page introuvable
                    include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
                }
            }
            else {
                if (isset($params['handle'])) {
                    $ctrl = $this->getGoodControler();
                    if ($ctrl != false) {
                        $c = new $ctrl($params);
                        $c->index();
                        //$c->render();
                    }
                }
                else {
                    // Si aucune page n'est définie, on va sur la page d'accueil
                    $c = new Home($params);
                    $c->index();
                    //$c->render();
                }
            }
        
    }

}

?>
