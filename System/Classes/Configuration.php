<?php

class Configuration {

    private $database;
    private $dev_mode;
    private $db_prefix;
    private $debug;
    private static $instance;
    private static $host_tables = array();

    private function __construct($driver, $host, $dbname, $login, $pass, $dev_mode = "DEBUG", $dbPrefix = '') {
        $this->initialize($driver, $host, $dbname, $login, $pass, $dev_mode, $dbPrefix);
    }

    public static function startEngine($driver, $host, $dbname, $login, $pass, $dev_mode = "DEBUG", $dbPrefix = '') {
        self::$instance = new Configuration($driver, $host, $dbname, $login, $pass, $dev_mode, $dbPrefix);
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
        if (!$this->isStarted())
            throw new CoreException('Generation des tables distantes');
        else {
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
            $this->host_tables = $t;
        }
    }

    public static function getInstance() {
        if (!(self::$instance instanceof Configuration))
            throw new CoreException('getInstance() sans création');
        else
            return self::$instance;
    }

    public static function isValidClass($className) {
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

}

?>
