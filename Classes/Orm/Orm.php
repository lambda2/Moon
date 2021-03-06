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

    // Constraints

    /** @var $wcontraints contains all the [where] contraints */
    protected $currentQuery = null;


    /* ----------------- Common methods ------------------- */

    public function __construct($driver) {
        $this->driver = $driver;
        $this->currentQuery = new Query();
        /** @TODO : Peut etre peut on enlever la respondsabilitée de la base
         * de données a la classe Core, et faire en sorte que l'ORM gere seul
         * la base de données...
         */
    }

    public function setQuery($query)
    {
        $this->currentQuery = $query;
        return $this;
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
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new OrmException(
                    "Erreur lors de l\'initialisation de la base de données : "
                    . $e->getMessage() . "<br/>");
        }
    }

    public function clearQuery()
    {
        $this->currentQuery = new Query();
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
            if($value != '')
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

    protected function getDefinedFieldsFromData($data, $sep=',')
    {
        $ret = array();
        foreach ($data as $key => $value) {
            if($value != '')
                $ret[] = $key;
        }
        return arr2str($ret,$sep);
    }

    protected function getDefinedValuesFromData($data, $sep=',')
    {
        $ret = array();
        foreach ($data as $key => $value) {
            if($value != '')
                $ret[] = $value;
        }
        return $ret;
    }

    /**
     * Va inserer la data fournie en parametre dans la table spécifiée.
     */
    public function insert($data, $table)
    {
        $r = false;

        if(!is_array($data))
            throw new OrmException("Arguments invalides pour l'insertion.", 1);

        $fields = $this->getDefinedFieldsFromData($data);
        $parenthValues = $this->generateInterrArray($data);
        $request =
            'INSERT INTO '
            .$table.' ('.$fields.')'
            .' VALUES ('.$parenthValues.');';

        try {
            $Req = self::$db->prepare($request);
            $r = $Req->execute($this->getDefinedValuesFromData($data));
            if($r)
            {
                $r = self::$db->lastInsertId();
            }
        } catch (Exception $e) { //interception de l'erreur

            Debug::log("Erreur lors de l'insertion : $e->getMessage()",1);
            // Peut etre faudra il enlever cette exception.

            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
            var_dump($e);

        }
        return $r;
    }

    public function getLastInsertId()
    {
        return self::$db->lastInsertId();
    }

    protected function convertFieldsToParams($entity)
    {
        $params = array();
        foreach($entity->getFields() as $field)
        {
            if($field->getValue() !== null)
            {
                $params[$field->getName()] = $field->getValue();
            }
        }
        return $params;
    }


    public function insertEntity($entity)
    {
        $table = $entity->getTable();
        $fields = $this->convertFieldsToParams($entity);
        echo "ready to insert in table $table :<br>";
        var_dump($fields);
        return $this->insert($fields,$table);
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
        if ($ids == NULL)
            $where = "1";
        else
            $where = $this->generateDefinedInterrArray($ids,' AND ');
        $request =
            'UPDATE '
            .$table.' SET '.$set.''
            .' WHERE '.$where.';';
        try {
            $Req = self::$db->prepare($request);
            $r = $Req->execute($this->getUpdatePreparedParams($data,$ids));
        } catch (Exception $e) { //interception de l'erreur

            Debug::log("Erreur lors de la mise à jour : $e->getMessage()",1);
            // Peut etre faudra il enlever cette exception.
            /*
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
            */
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

    protected function arrayQuery($request, $args = array()) {
        $t = array();
        try {
            $Req = self::$db->prepare($request);
            $Req->execute(array_values($args));
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_ASSOC)) {
            $t[] = $res;
        }
        return $t;
    }


    public function getAttributeFrom($attr, $table, $order=null)
    {
        $t = array();
        $orders = '';
        if($order != null)
            $orders = ' ORDER BY '.$order;
        try {
            $Req = self::$db->prepare("select $attr from $table $orders");
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

    /* ---------------------- Query selectors --------------------- */


    /**
     * Add a [select] constraint to the next query.
     * @param $fields the fields to select
     * @return $this the current instance.
     * @since 0.0.4
     */
    public function select($fields)
    {
        $this->currentQuery->select($fields);
        return $this;
    }

    /**
     * Add a [where] constraint to the next query.
     * @param $field the field to be constrained
     * @param $value the value to contraint
     * @param $arg the contraint operator. default: [=]
     * @param $assoc the logical operand
     * @return $this the current instance.
     * @since 0.0.4
     * @TODO set a betted system to manipulate logical operands.
     * Currently, we can't.
     */
    public function where($field, $value, $arg='=', $assoc='AND')
    {
        $this->currentQuery->where($field, $value, $arg, $assoc);
        return $this;
    }

    /**
     * Add a [from] constraint to the next query.
     * @param $table the table to search
     * @return $this the current instance.
     * @since 0.0.4
     */
    public function from($table)
    {
        $this->currentQuery->from($table);
        return $this;
    }

    /**
     * Add a [limit] constraint to the next query.
     * @param $limit the number of tuples to limit
     * @return $this the current instance
     * @since 0.0.4
     */
    public function limit($limit)
    {
        $this->currentQuery->limit($limit);
        return $this;
    }

    /**
     * Add a [order by] constraint to the next query.
     * @param $columns the columns to order to
     * @param $order the order of sorting (asc or desc)
     * @return $this the current instance
     * @since 0.0.4
     */
    public function orderBy($columns,$order = 'asc')
    {
        $this->currentQuery->orderBy($columns,$order);
        return $this;
    }

    /**
     * Will execute a [select] query for defined fields, constraints and tables.
     * @return Array a set of element corresponding to the results of the
     * query.
     * @since 0.0.4
     */
     public function fetchArray()
     {
        $query = $this->currentQuery->getQuerySql();
        $this->clearQuery();
        Profiler::addRequest($query);
        return $this->arrayQuery($query);
     }

    /**
     * Will execute a [select] query for defined fields, constraints and tables.
     * @return Entities a set of element corresponding to the results of the
     * query.
     * @since 0.0.4
     */
     public function fetchEntities()
     {
        $query = $this->currentQuery->getQuerySql();
        Profiler::addRequest($query);
        $table = $this->currentQuery->getLastTable();
        $this->clearQuery();
        return EntityLoader::loadEntitiesFromRequest($query, $table);
     }

    /* --------------- To be defined in lower classes ---------- */

    public abstract function getAllTables();

    public abstract function getAllColumnsFrom($tableName);

    public abstract function getMoonLinksFrom($tableName, $external=false);

    public abstract function getAllRelationsFrom($tableName);

    public abstract function getAllRelationsWith($tableName);

    public abstract function getAllRelationsBetween($origin,$destination);

    public abstract function getAllRelations();

    public abstract function getAllEntityFields($tableName);
}

?>
