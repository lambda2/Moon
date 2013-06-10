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
            if ($foundInRoutes != false){
                return $this->getRequestedTarget($foundInRoutes, $request);
            }
            /*
            else {
                dbg($_SERVER['REDIRECT_URL']." not found in routes...",0);
            }
            */

            // On cherche dans les controleurs
            $foundInCtrls = $this->findControllerInFiles($request);
            if ($foundInCtrls != false)
                return $this->getRequestedTarget($foundInCtrls, $request);
            /*
            else {
                dbg($_SERVER['REDIRECT_URL']." not found in controllers...",0);
            }
            */
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
     * Essaye de retrouver la méthode demandée dans l'url.
     * @param type $controller le controlleur demandé
     * @param type $request l'a'url demandée par l'internaute
     * @return mixed false si rien n'a été trouvé, le nom de la méthode sinon.
     */
    protected function getValidMethod($controller,$request){
        $metod = false;
        $url = self::splitUrl($request);
        foreach ($url as $elem) {
            if(method_exists($controller, $elem)){
                $metod = $elem;
            }
        }
        
        return $metod;
    }
    
    protected function getRequestedTarget($controller,$request){
        $method = $this->getValidMethod($controller, $request);
        if($method == false)
            $method = 'index';
        return $controller.'.'.$method;
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
            $rootElems = explode('/', $root);
            foreach ($rootElems as $key => $rootElem) {
                if (in_array($rootElem, $splitted)) {
                $splitted = array_remove_value($splitted, $rootElem);
                }
            }
            
        }
        return array_index_clean($splitted);
    }
    
    /**
     * Instancie le controlleur et apelle la bonne méthode
     * @param type $controllerUrl l'url du controlleur sous la forme 
     * [Classe].[Methode]
     * @return boolean True si tout est bon, false sinon.
     */
    public function callController($controllerUrl,$params,$urlParams=array())
    {
        $s = split('\.',$controllerUrl);
        $c = new $s[0]();
        Core::setContext($s[0]);
        $c->setUrlParams($urlParams);
        if (count($s) > 1) 
        {
            $c->$s[1]($params);
        }
        else 
            $c->index($params);
    }
    
    /**
     * Apelle la méthode index du controlleur par défaut.
     * @param array $params les parametres de la requete
     */
    public function callDefaultController($params,$urlParams=array()){
        $defC = Core::opts()->system->default_controller;
        $c = new $defC();
        $c->setUrlParams($urlParams);
        $c->index($params);
    }

    public function routeGet()
    {
        $params = $_GET;        
        // If it's a moon generated form :
        if(array_key_exists('moon-action', $_GET))
        {
            // We {auto} route it !
            $sucess = $this->routeMoonAutoGet();
        }
        else if (isset($params['p'])) {

                $request = explode('->',$params['p']);
                $classe = $request[0];
                $method = 'index';

                // We define the current context to the core
                Core::setContext($classe);

                $options = array();
                if(count($request) > 1){
                    $method = explode('?',explode('#',$request[1])[0])[0];
                    $opts = explode('?',$request[1]);
                    $alt_meth = array_shift($opts);
                    if(count($opts))
                    {
                        $options = implode('/',$opts);
                    }
                }

                /*
                echo "classe = $classe<br>";
                echo "method = $method<br>";
                echo "options = ".arr2str($options)."<br>";
                */

                if(isNull($method))
                    $method = 'index';

                // Et que cette page existe
                if (class_exists($classe)) {
                    // On la charge
                    $c = new $classe();
                    
                    if(is_a($c,'Controller'))
                    {
                        $annotParams = $this->parseUrlParams($c,$method,$options);
                        $c->setUrlParams($annotParams);
                    }
                    $c->$method();
                }
                else {
                    // Sinon, page introuvable
                    include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
                }
            }
            else if (isset($params['sandbox'])) {
                if (Core::opts()->system->mode == 'DEBUG' && 
                        file_exists(
                                '../System/SandBox/' . $params['sandbox'] . '.php'))
                {
                    include_once '../System/SandBox/' . $params['sandbox'] . '.php';
                }
                else {
                    // Sinon, page introuvable
                    echo '4o4 :S';
                    include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
                }
            }
            else if (isset($params['from-moon'])) {
                // Et que cette page existe
                if (Core::opts()->system->mode == 'DEBUG' && 
                        file_exists(
                                '../System/SandBox/' . $params['sandbox'] . '.php'))
                {
                    include_once '../System/SandBox/' . $params['sandbox'] . '.php';
                }
                else {
                    // Sinon, page introuvable
                    echo '4o4 :S';
                    include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
                }
            }
            else {
                if (isset($params['handle'])) {
                    $ctrl = $this->getGoodControler();
                    if(!$this->callController($ctrl, $params)){
                        $this->callDefaultController($params);
                    }
                    
                }
                else {
                    // Si aucune page n'est définie, on va sur la page par défaut
                    $this->callDefaultController($params);                }
            }
    }

    /**
     * Will read annotation in selected class for find
     * a param(s) definition, and will return corresponding
     * array. The param name are the keys, the param value are the value.
     * @return Array An associative array with [param name] = value
     * @ since 0.0.4
     */
    protected function parseUrlParams($className,$method,$params)
    {
        $pa = array();
        if(!isNull($params))
            $pa = explode('/',$params); 
        $reflectedClass = new ReflectionAnnotatedMethod($className,$method);

        if(!$reflectedClass->hasAnnotation('PathInfo'))
        {
            return $pa;
        }
        else
        {
            $annoClass = $reflectedClass->getAnnotation('PathInfo');
            $return = array();
            $scheme = explode('/',$annoClass->value);
            $counter = 0;
            foreach($pa as $urlElt)
            {
                if($counter <= count($scheme) -1)
                {
                    $return[$scheme[$counter]] = $urlElt;
                }
                else 
                {
                    $return[] = $urlElt;
                }
                $counter ++;
            }
            return $return;
        }
    }

    protected function checkAccess($action, $target)
    {
        return true;
    }

    /**
     * This method will describe post actions when the form
     * has been generated by the moon engine.
     */
    protected function routeMoonAutoPost()
    {
        $this->routeMoon($_POST);
    }

    /**
     * This method will describe get actions when the form
     * has been generated by the moon engine.
     */
    protected function routeMoonAutoGet()
    {
        $this->routeMoon($_GET);
    }

    /**
     * This method will describe post or get actions when the form
     * has been generated by the moon engine.
     */
    protected function routeMoon($params)
    {
        $sucess = false;

        $action = $_GET['moon-action'];
        $target = $_GET['target'];
        $p = '';

        if(isset($_GET['ajax']))
            $ajax = $_GET['ajax'];


        if(isset($_GET['p']))
            $p = $_GET['p'];



        // On vérifie que on a bien tous les arguments.
        if(isNull($action) or isNull($target))
        {
            throw new AlertException
            ("Unable to find what to do with this form...", 1);
            
            return false;
        }

        if(isset($params['moon-context']))
        {
            Core::setContext($params['moon-context']);
        }

        $class = EntityLoader::getClass($target);

        // We define the current context to the core

        /**
         * On vérifie les accès.
         * @TODO : Faire les accès :)
         */
        if(!$this->checkAccess($action, $target))
        {
            throw new MemberAccessException
                ("Accès non autorisé pour faire un $action sur $target");
            return false;
        }

        $class->initProcess($params);

        echo '<br>begin of router !<br>';
        switch ($action) {
            case 'insert':
                $sucess = $class->processInsertForm($params);
                break;
            case 'update':
                echo '<br>begin of router !<br>';
                $identifiers = $params['ids'];

                $values = param2arr($identifiers);
                $class->loadByArray($values);
                $sucess = $class->processUpdateForm($params);
                echo '<br>fin router !<br>';

                break;

            case 'delete':

                $identifiers = $params['ids'];
                $values = param2arr($identifiers);
                $class->loadByArray($values);
                $sucess = $class->processDeleteForm($values);

                break;

            default:
                throw new MemberAccessException
                    ("Action non valide ($action) sur la ressource $target");
                break;
        }

        echo '<br>fin router !<br>';
        var_dump($sucess);

        if($sucess)
        {
            if(!isNull($p))
            {
                redirectStatut($p);
            }
            else if($_SERVER['HTTP_REFERER'] != '')
            {
                $page = $_SERVER['HTTP_REFERER'];
                if (!headers_sent()) {
                    header('Location: '.$page);
                }
                else {
                    echo('<script language="javascript">document.location.href="'. $page .'"</script>');
                }
            }
        }
        return $sucess;
    }

    public function routePost()
    {
        $sucess = false;
        $params = $_POST;

        // If it's a moon generated form :
        if(array_key_exists('moon-action', $_GET))
        {
            // We {auto} route it !
            $sucess = $this->routeMoonAutoPost();
        }
        else
        {
            $params = $_GET;

            if (isset($params['p'])) {

                    $request = explode('->',$params['p']);
                    $classe = $request[0];
                    // We define the current context to the core
                    Core::setContext($classe);


                    if(count($request) > 1){
                        $method = explode('?',explode('#',$request[1])[0])[0];
                    }
                    else
                        $method = 'index';

                    // Et que cette page existe
                    if (class_exists($classe)) {
                        // On la charge
                        $c      = new $classe($params);
                        $c->$method();
                    }
                    else {
                        // Sinon, page introuvable
                        include_once('WebRoot/404.php'); //TODO : faire une page 404 respectable
                    }
            }
            else {
                if (isset($params['handle'])) {
                    $ctrl = $this->getGoodControler();
                    if(!$this->callController($ctrl, $params)){
                        $this->callDefaultController($params);
                    }
                    
                }
                else {
                    // Si aucune page n'est définie, on va sur la page par défaut
                    $this->callDefaultController($params);
                }
            }
        }

    }

    /**
     * Méthode principale de la classe, chargée de déterminer le controlleur 
     * à apeller en fonction des paramètres de la requete.
     * @param type $params les paramètres de la requete.
     */
    public function route() {

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->routeGet();
                break;
            case 'POST':
                $this->routePost();
                break;
            
            default:
                # code...
                break;
        }
    }

}

?>
