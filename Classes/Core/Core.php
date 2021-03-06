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
    const defaultConfigFile = 'Moon/Configuration/configuration.yml';

    protected $database;
    protected $dev_mode;
    protected $db_prefix;
    protected $debug;
    protected static $context;
    protected static $router;
    protected static $user_routes;
    protected static $options = null;
    protected static $instance;
    protected static $user;
    protected static $host_tables = array();
    protected static $capabilities;

    protected function __construct($dev_mode = "DEBUG", $dbPrefix = '')
    {
        $this->initialize($dev_mode, $dbPrefix);
    }

    public static function loadOptions()
    {
        $userConfigFile = Spyc::YAMLLoad(self::userConfigFile);
        $defaultConfigFile = Spyc::YAMLLoad(self::defaultConfigFile);
        self::$options = new ArrayBrowser(extendArray($defaultConfigFile, $userConfigFile));
    }

    protected function loadLoggedUser()
    {
        if(isset($_SESSION['login']))
        {
            $this->setUser($_SESSION['login']);
        }
    }

    protected static function loadTemplate()
    {
        $success = true;

        // the template to use
        $selectedTemplate = self::$options->templates->default_template;

        // the path to the templates folder
        $templatePath = self::$options->templates->path;

        // the selected template config file path
        $templateConfigPath = $templatePath.'/'.$selectedTemplate.'/template.yml';

        if(file_exists($templateConfigPath))
        {
            $tplOpts = Spyc::YAMLLoad($templateConfigPath);
            $tplOptsBr = new ArrayBrowser($tplOpts);

            if(is_array($tplOpts)
                and self::$options->templates->appendNewBrowser('config',$tplOptsBr))
            {
                $success = true;
            }
        }
        return $success;
    }

    public static function opts()
    {
        if(isNull(self::$options))
            self::loadOptions ();
        return self::$options;
    }

    public static function loadRoutes()
    {
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


            //MoonChecker::runTests();
            self::$capabilities = CapabilitiesFacory::getCapabilities();
            self::loadOptions();
            self::loadRoutes();

            self::$instance = new Core(
                    self::$options->system->mode,
                    self::$options->database->db_prefix);

            self::$router = new Router();

            if(isConnecte() and class_exists('Membre'))
            {
                $membre = new Membre();
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

    /**
     * Returns true if the given capability is on the server (ex : apc_cache),
     * false otherwise.
     * @param String $capability the capability to check
     * @return Bool true or false
     */
    public static function haveCapability($capability)
    {
        return self::$capabilities->havePower($capability);
    }

    public static function getUser(){
        return self::$user;
    }

    public static function setUser($user){
        self::$user = $user;
    }

    protected function initialize($dev_mode, $dbPrefix) {
        $this->dev_mode = $dev_mode;
        $this->context = '';
        $this->db_prefix = $dbPrefix;
        if($dev_mode == 'DEBUG')
            $this->debug = Debug::getInstance();
        else
            $this->debug = null;

        $this->databaseConnect();
        $this->generateHostTables();
        $this->loadLoggedUser();
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
        if (in_array($className, self::$host_tables))
            return true;
        else
            return false;
    }

    public function isDebug() { return $this->dev_mode == 'DEBUG'; }

    public static function setContext($context)
    {
        self::$context = $context;
    }

    public static function getContext()
    {
        return self::$context;
    }

    public static function getRouter()
    {
        return self::$router;
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

    /**
     * Will search in the form files for a rules set.
     * If the file exists, content is returned as an array.
     * @return mixed Array if found, boolean False otherwise.
     */
    public static function getFormDefinitionArray()
    {
        $return = false;
        $search = self::$options->forms->form_files.self::$context.'.yml';

        if(file_exists($search))
        {
            $return = Spyc::YAMLLoad($search);
        }

        return self::parseFormDefinitionArray($return);
    }

    protected static function parseFormDefinitionArray($form)
    {
        $fixedArray = array();
        foreach ($form as $formName => $rules) {

            $f = explode('|',$formName);
            if(count($f) > 1)
            {
                foreach($f as $formNameSplitted)
                {
                    $fixedArray[trim($formNameSplitted)] = $rules;
                }
            }
            else
            {
                $fixedArray[$formName] = $rules;
            }
        }
        return $fixedArray;
    }


}

?>
