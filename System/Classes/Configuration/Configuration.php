<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Configuration {

    const userConfigFile = 'Config/configuration.yml';
    const defaultConfigFile = 'System/Classes/Configuration/configuration.yml';
    
    private $database;
    private $dev_mode;
    private $db_prefix;
    private $debug;
    private static $router;
    private static $options;
    private static $instance;
    private static $user;
    private static $host_tables = array();

    private function __construct($driver, $host, $dbname, $login, $pass, $dev_mode = "DEBUG", $dbPrefix = '') {
        $this->initialize($driver, $host, $dbname, $login, $pass, $dev_mode, $dbPrefix);
    }
    
    
    private static function getOptions(){
        $userConfigFile = Spyc::YAMLLoad(self::userConfigFile);
        $defaultConfigFile = Spyc::YAMLLoad(self::defaultConfigFile);
        self::$options = new ArrayBrowser(extendArray($defaultConfigFile, $userConfigFile));
        
    }

    public static function startEngine() {
        
        self::getOptions();
        
        self::$instance = new Configuration(
                self::$options->database->driver, 
                self::$options->database->host, 
                self::$options->database->dbname, 
                self::$options->database->login, 
                self::$options->database->pass, 
                self::$options->system->mode, 
                self::$options->database->db_prefix);
        
        self::$user = null;
        self::$router = new Router();
        
        if(isConnecte()){
            $membre = new Membre();
            $membre->loadBy('id_membre', getId($_SESSION['login'], $this->database));
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
    }
    
    public static function getUser(){
        return self::$user;
    }

    private function initialize($driver, $host, $dbname, $login, $pass, $dev_mode, $dbPrefix) {
        $this->dev_mode = $dev_mode;
        $this->db_prefix = $dbPrefix;
        $this->debug = new Debug($this);
        
        $this->databaseConnect($driver, $host, $dbname, $login, $pass);
        $this->generateHostTables();
    }

    private function databaseConnect($driver, $host, $dbname, $login, $pass) {
        try {
            $this->database = new PDO("$driver:host=$host;dbname=$dbname", $login, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            print "Erreur lors de l\'initialisation de la base de données : " . $e->getMessage() . "<br/>";
            die();
        }
    }

    private function generateHostTables() {
        $t = array();
        try {
            $Req = $this->database->prepare("SHOW TABLES");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        while ($res = $Req->fetch(PDO::FETCH_NUM)) {
            $t[] = $res[0];
        }
        self::$host_tables = $t;
    }

    public static function getInstance() {
        if (!(self::$instance instanceof Configuration))
            throw new CoreException('getInstance() sans création');
        else
            return self::$instance;
    }

    public static function getBdd() {
        if (!(self::$instance instanceof Configuration))
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
    
    public static function route($url){
        
        if(is_array($url)){
            $tpl = self::$router->route($url);
        }
        
    }

}

?>
