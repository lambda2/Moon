<?php

/*
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Core {

    const userConfigFile = 'Config/configuration.yml';
    const defaultConfigFile = '../System/Configuration/configuration.yml';
    
    protected $database;
    protected $dev_mode;
    protected $db_prefix;
    protected $debug;
    protected static $router;
    protected static $user_routes;
    protected static $options = null;
    protected static $instance;
    protected static $user;
    protected static $host_tables = array();

    protected function __construct($dev_mode = "DEBUG", $dbPrefix = '') {
        $this->initialize($dev_mode, $dbPrefix);
    }


    
    
    public static function loadOptions(){
        $userConfigFile = Spyc::YAMLLoad(self::userConfigFile);
        $defaultConfigFile = Spyc::YAMLLoad(self::defaultConfigFile);
        self::$options = new ArrayBrowser(extendArray($defaultConfigFile, $userConfigFile));
    }
    
    public static function opts(){
        if(isNull(self::$options))
            self::loadOptions ();
        return self::$options;
    }
    
    public static function loadRoutes(){
        $routeFiles = Core::opts()->system->routes_files;
        $r = array();
        foreach ($routeFiles as $route) {
            if(!file_exists($route)){
                throw new CriticalException(
                    "Unable to find the routes config file in [$route]..."
                    ."Please review your routing files url by editing "
                        .self::userConfigFile);
            }
            $a = Spyc::YAMLLoad($route);
            $r = array_merge($r,$a);
        }
        self::$user_routes = new ArrayBrowser($r['routes']);
    }
    
    public static function routes(){
        if(isNull(self::$user_routes))
            self::loadRoutes();
        return self::$user_routes;
    }
    
    public static function startEngine() {

        try {
            
            MoonChecker::runTests();
            self::loadOptions();
            self::loadRoutes();
            
            self::$instance = new Core(
                    self::$options->system->mode, 
                    self::$options->database->db_prefix);
            
            self::$user = null;
            self::$router = new Router();
            
            if(isConnecte()){
                $membre = new Membre();
                //$membre->loadBy('id_membre', getId($_SESSION['login'], $this->database));
            }
            
            /*
             * Pour les gens un peu old school, dégueux, ou les deux, on stocke un pointeur
             * sur l'instance de notre moteur dans une superglobale accessible partout.
             * On notera que la configuration est un singleton. Ainsi, ces deux expressions
             * sont équivalentes à n'importe quel endroit :
             * > $GLOBALS['System'] === Configuration::getInstance()
             * 
             * @TODO : Virer cette superglobale. elle est quand meme crasseuse.
             */
            $GLOBALS['System'] = self::$instance;

        } catch (Exception $e) {
            MoonChecker::showHtmlReport($e);
        }
    }
    
    public static function getUser(){
        return self::$user;
    }

    protected function initialize($dev_mode, $dbPrefix) {
        $this->dev_mode = $dev_mode;
        $this->db_prefix = $dbPrefix;
        if($dev_mode == 'DEBUG')
            $this->debug = new Debug($this);
        else
            $this->debug = null;
        
        $this->databaseConnect();
        $this->generateHostTables();
    }

    protected function databaseConnect() {
        Orm::launch(self::$options->database->childs());
        $this->database = OrmFactory::getOrm();
    }

    protected function generateHostTables() {
        self::$host_tables = $this->database->getAllTables();
    }

    public static function getInstance() {
        if (!(self::$instance instanceof Core))
            throw new CoreException('getInstance() sans création');
        else
            return self::$instance;
    }

    public static function getBdd() {
        if (!(self::$instance instanceof Core))
            throw new CoreException('Aucune instance de configuration');
        else
            return self::$instance->bdd();
    }
    
    public static function isValidClass($className) {
        /*foreach (self::$host_tables as $value) {
            echo "on regarde si la table $className correspond a $value...";
            if(strcmp($className, $value) == 0){
                echo " YES !";
            }
            else
                echo " NON... !";
            echo '<br>';
        }*/
        if (in_array($className, self::$host_tables))
            return true;
        else
            return false;
    }

    public static function isStarted() {
        return (self::$instance instanceof self);
    }

    public function bdd() {
        return $this->database;
    }

    public function getDevMode() {
        return $this->dev_mode;
    }

    public function getDbPrefix() {
        return $this->db_prefix;
    }

    public function debug() {
        return $this->debug;
    }
    
    public function getTablesList(){
        return self::$host_tables;
    }
    
    public static function getAccess($classe){
        $a = new Access();
        $a->loadFromTable($classe);
        return $a;
    }
    
    public static function route(){
        try {
            $tpl = self::$router->route();
            
        } catch (Exception $exc) {
            MoonChecker::showHtmlReport($exc);
        }

        
        
    }

}

?>
