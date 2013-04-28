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

    public static function checkConnexion($p) {

        try {
            $i = new PDO(
                    "{$p['driver']}:host={$p['host']};dbname={$p['dbname']}", 
                            $p['login'], $p['pass'], 
                            array(PDO::MYSQL_ATTR_INIT_COMMAND
                => 'SET NAMES \'UTF8\'')
            );
            $i->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public static function isStarted() {
        return (self::$instance instanceof self);
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

    protected function generateInterrArray($count,$sep=',')
    {
        $ret = array();
        foreach ($count as $key => $value) {
            $ret[] = '?';
        }
        return arr2str($ret,$sep);
    }

    protected function generateDefinedInterrArray($count, $sep=',')
    {
        $ret = array();
        foreach ($count as $key => $value) {
            if($value != '')
                $ret[] = $key.' = ?';
            else
                $ret[] = $key.' = NULL';
        }
        return arr2str($ret,$sep);
    }

    /**
     * Va inserer la data fournie en parametre dans la table spécifiée.
     */
    public function insert($data, $table)
    {
        $r = false;

        if(!is_array($data))
            throw new OrmException("Arguments invalides pour l'insertion.", 1);

        $fields = arr2str(array_keys($data),',');
        $parenthValues = $this->generateInterrArray($data);
        $request = 
            'INSERT INTO '
            .$table.' ('.$fields.')'
            .' VALUES ('.$parenthValues.');';

        try {
            $Req = self::$db->prepare($request);
            $r = $Req->execute(array_values($data));
        } catch (Exception $e) { //interception de l'erreur

            // Peut etre faudra il enlever cette exception.
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
        }
        return $r;
    }

    /**
     * Va mettre à jour les champs spécifiés dans data dans la table 
     * fournie par $table pour les ids spécifiés par $ids.
     */
    public function update($data, $table, $ids)
    {
        $r = false;

        if(!is_array($data))
            throw new OrmException("Arguments invalides pour l'insertion.", 1);

        $set = $this->generateDefinedInterrArray($data);
        $where = $this->generateDefinedInterrArray($ids,' AND ');
        $request = 
            'UPDATE '
            .$table.' SET '.$set.''
            .' WHERE '.$where.';';
        try {
            $Req = self::$db->prepare($request);
            $r = $Req->execute($this->getUpdatePreparedParams($data,$ids));
        } catch (Exception $e) { //interception de l'erreur

            // Peut etre faudra il enlever cette exception.
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
        }
        return $r;
    }

    /**
     * Va supprimer dans la table spécifiée par $table 
     * les entrées corespondant aux ids spécifiés par $ids.
     */
    public function delete($table, $ids)
    {
        $r = false;

        if(!is_array($ids))
            throw new OrmException("Arguments invalides pour l'insertion.", 1);

        $where = $this->generateDefinedInterrArray($ids,' AND ');
        $request = 
            'DELETE FROM '.$table
            .' WHERE '.$where.';';
        try {
            $Req = self::$db->prepare($request);
            $r = $Req->execute($this->getPreparedParams($ids));
        } catch (Exception $e) { //interception de l'erreur

            // Peut etre faudra il enlever cette exception.
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
        }
        return $r;
    }

    /**
     * Genere la liste des valeurs a envoyer a [execute()] à partir
     * des champs a mettre a jour et des ids de la clause WHERE.
     */
    protected function getUpdatePreparedParams($data,$ids)
    {
        $ret = array();
        foreach ($data as $key => $value) {
            if($value != '')
                $ret[] = $value;
        }
        foreach ($ids as $key => $value) {
            $ret[] = $value;
        }
        return $ret;
    }

    /**
     * Genere la liste des valeurs a envoyer a [execute()]
     */
    protected function getPreparedParams($data)
    {
        $ret = array();
        foreach ($data as $key => $value) {
            $ret[] = $value;
        }
        return $ret;
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

    public function getAttributeFrom($attr, $table)
    {
        $t = array();
        try {
            $Req = self::$db->prepare("select $attr from $table");
            $Req->execute();
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_ASSOC)) {
            $t[] = $res[$attr];
        }
        return $t;
    }

    public abstract function getAllTables();

    public abstract function getAllColumnsFrom($tableName);
    
    public abstract function getMoonLinksFrom($tableName, $external=false);

    public abstract function getAllRelationsFrom($tableName);

    public abstract function getAllRelationsWith($tableName);
    
    public abstract function getAllRelations();

    public abstract function getAllEntityFields($tableName);
}

?>
