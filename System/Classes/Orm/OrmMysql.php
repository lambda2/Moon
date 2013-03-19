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
 * Description of OrmMysql
 *
 * @author lambda2
 */
class OrmMysql extends Orm {

    public function getAllColumnsFrom($tableName) {
        $t = array();
        try {
            $Req = self::$db->prepare("SHOW COLUMNS FROM {$tableName}");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to list the columns of table $tableName : ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $t[$res->Field] = $res;
        }
        return $t;
    }

    public function getAllRelationsWith($tableName) {
        $dbname = Core::opts()->database->dbname;
        $t      = array();
        try {
            $Req = self::$db->prepare("
                SELECT CONCAT( table_name, '.',
                column_name, '@',
                referenced_table_name, '.',
                referenced_column_name ) AS f_keys
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_SCHEMA = '{$dbname}'
                AND REFERENCED_TABLE_NAME is not null
                AND REFERENCED_TABLE_NAME = '{$tableName}'
                ORDER BY TABLE_NAME, COLUMN_NAME;");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to get external relations referencing table '{$tableName}': ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_COLUMN)) {
            $t[] = $res;
        }
        return $t;
    }

    public function getAllRelationsFrom($tableName) {
        $dbname = Core::opts()->database->dbname;
        $t      = array();
        try {
            $Req = self::$db->prepare("
                SELECT CONCAT( table_name, '.',
                column_name, '@',
                referenced_table_name, '.',
                referenced_column_name ) AS f_keys
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_SCHEMA = '{$dbname}'
                AND REFERENCED_TABLE_NAME is not null
                AND TABLE_NAME = '{$tableName}'
                ORDER BY TABLE_NAME, COLUMN_NAME;");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to get relations of table '{$tableName}': ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_COLUMN)) {
            $t[] = $res;
        }
        return $t;
    }

    public function getAllRelations() {
        $dbname = Core::opts()->database->dbname;
        $t      = array();
        try {
            $Req = self::$db->prepare("
                SELECT CONCAT( table_name, '.',
                column_name, '@',
                referenced_table_name, '.',
                referenced_column_name ) AS f_keys
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_SCHEMA = '{$dbname}'
                AND REFERENCED_TABLE_NAME is not null
                ORDER BY TABLE_NAME, COLUMN_NAME;");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to get relations : ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_COLUMN)) {
            $t[] = $res;
        }
        return $t;
    }

    public function getAllTables() {
        $t = array();
        try {
            $Req = self::$db->prepare("SHOW TABLES");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            throw new OrmException(
            "Unable to list the tables : ["
            . $e->getMessage() . ']');
        }
        while ($res = $Req->fetch(PDO::FETCH_COLUMN)) {
            $t[] = $res;
        }
        return $t;
    }

    /**
     * Génére les MoonLinks des relations de la table fournie en parametre
     * @param string $tableName le nom de la table.
     * @return array(\MoonLink)
     * @throws OrmException si une erreur survient
     */
    public function getMoonLinksFrom($tableName, $external=false) {
        $relations = $this->getAllRelationsFrom($tableName);
        if($external)
        {
            $relations = $this->getAllRelationsWith($tableName);
        }
        $links     = array();
        foreach ($relations as $relation) {
            $urls        = explode('@', $relation);
            if (count($urls) < 2)
            {
                throw new OrmException(
                "Unable to generate moonLink for relation [$relation]");
            }
            $sourceField = explode('.', $urls[0]);
            $links[$sourceField[1]] = new MoonLink($urls[0], $urls[1]);
        }
        return $links;
    }

}

?>
