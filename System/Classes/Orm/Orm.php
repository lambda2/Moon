<?php

/*
 * This file is part of the Moon Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Va etre notre classe d'Orm principale.
 * Elle est abstraite et doit etre implémentée pour chaque type de serveur SQL.
 * Pour l'instant, nous allons nous focaliser sur MySql.
 *
 * @author lambda2
 */
abstract class Orm {

    // Notre driver, cad MySql, Postgres etc...
    protected $driver;
    protected static $db;
    protected static $instance;

    public function __construct($driver) {
        $this->driver = $driver;
        /** @TODO : Peut etre peut on enlever la respondsabilitée de la base
         * de données a la classe Core, et faire en sorte que l'ORM gere seul 
         * la base de données...
         */
    }

    /**
     * Méthode necessaire pour démarrer le moteur.
     * Elle ne doit (normalement) etre exécutée qu'une seule fois à 
     * l'initialisation du Core.
     * @param array() $p les paramètres de connexion
     * @throws OrmException si il y a une erreur.
     */
    public static function launch($p) {
        try {
            self::$db = new PDO(
                    "{$p['driver']}:host={$p['host']};dbname={$p['dbname']}", 
                            $p['login'], $p['pass'], 
                            array(PDO::MYSQL_ATTR_INIT_COMMAND
                => 'SET NAMES \'UTF8\'')
            );
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            throw new OrmException(
                    "Erreur lors de l\'initialisation de la base de données : " 
                    . $e->getMessage() . "<br/>");
        }
    }

    public static function getInstance() {
        if (!(self::$instance instanceof Orm)) {
            self::$instance = OrmFactory::getOrm();
        }
        else
            return self::$instance;
    }

    public function getDb() {
        return self::$db;
    }

/*
    public function setDb($db) {
        self::$db = $db;
        return $this;
    }*/

    public function getDriver() {
        return $this->driver;
    }

    public function setDriver($driver) {
        $this->driver = $driver;
        return $this;
    }

    public function query($request, $args = array()) {
        $t = array();
        try {
            $Req = self::$db->prepare($request);
            $Req->execute(array_values($args));
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $t[] = $res;
        }
        return $t;
    }

    public abstract function getAllTables();

    public abstract function getAllColumnsFrom($tableName);
    
    public abstract function getMoonLinksFrom($tableName, $external=false);

    public abstract function getAllRelationsFrom($tableName);

    public abstract function getAllRelationsWith($tableName);
    
    public abstract function getAllRelations();
}

?>
