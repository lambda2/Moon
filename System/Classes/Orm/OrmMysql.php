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
    public function getMoonLinksFrom($tableName, $external=false,$d=False) {
        $relations = $this->getAllRelationsFrom($tableName);
        if($external)
        {
            $relations = $this->getAllRelationsWith($tableName);
        }
        $links     = array();



        foreach ($relations as $relation) {
            $urls        = explode('@', $relation);
            if(count($urls) < 2)
            {
                throw new OrmException(
                "Unable to generate moonLink for relation [$relation]");
            }
            $sourceField = explode('.', $urls[0]);
            $links[$relation] = new MoonLink($urls[0], $urls[1]);
        }
        /*if($d) {
            echo '###### ANOU ##########<br>';
            var_dump($links);
            var_dump($this);
            echo '###### PLU ANOU ##########<br>';
        }
        */
        

        return $links;
    }


    /**
     * Va construire et retourner un tableau d'objets EntityField
     * correspondant aux champs de la table donnée en paramètre.
     */
    public function getAllEntityFields($tableName)
    {
        //echo '<h2>POUR LA TABLE '.$tableName.'</h2>';
        $fields = array();
        $sqlFields = $this->getAllColumnsFrom($tableName);
        $moonLinks = $this->getMoonLinksFrom($tableName);

        foreach ($sqlFields as $table => $field) {

            //var_dump($field);
            $name = $field->Field;
            $type = self::parseTypeValue($field->Type);

            //echo '<br><br>nouvel ENTITYFIELD de '.$name.'('.$type.')<br>';
            $f = new EntityField($type,$name);
            //echo 'Formualire :<br>';
            //echo $f->getHtmlField();


            $f->setIsNull(self::parseNullValue($field->Null));

            if($field->Key == 'PRI'){
                $f->setIsPrimary(true);
            }
            else if ($field->Key == 'MUL'
                and array_key_exists($name, $moonLinks)
                and !isNull($moonLinks[$name]))
            {
                $f->setIsForeign(true);
                $f->setForeignTarget(
                    $moonLinks[$name]->destinationTable
                    .'.'.$moonLinks[$name]->destinationColumn);
                //echo '<b>'.$moonLinks[$name]->destinationTable
                //    .'.'.$moonLinks[$name]->destinationColumn.'</b><br>';
                //echo 'keys = '.arr2str(array_keys($moonLinks)).'<br>';
                $f->setForeignDisplayTarget(
                    $moonLinks[$name]->destinationTable
                    .'.'.$moonLinks[$name]->destinationColumn);
            }

            $f->setPlaceHolder($field->Default);

            if($field->Extra == 'auto_increment')
                $f->setIsAuto(true);

            if($type == 'enum'){
                // Récupère les valeurs de l'énum séparées par des virgules
                $options = self::parseInnerParenthValue($field->Type);
                // Et crée un tableau composé de ces éléments.
                $f->setOptionsValues(explode(',', $options));
            }

            $fields[$table] = $f;

        }

        return $fields;

    }


    /**
     * Retourne true si la valeur equivaut a vrai (YES).
     * Sinon, retourne false (NO).
     * @return boolean true if param == 'YES', false otherwise
     */
    protected static function parseNullValue($nullValue)
    {
        if($nullValue === 'YES')
            return true;
        else
            return false;
    }

    /**
     * Retourne le type sans les parenthèses conternant
     * la taille de la donnée dans mysql.
     * par exemple : 'int(255)'' retournera 'int'.
     * @param string typeValue le type mysql
     * @return string le type (php) de la donnée
     */
    protected static function parseTypeValue($typeValue)
    {
        $properTypeArray = explode('(', $typeValue);
        return $properTypeArray[0];
    }

    /**
     * Retourne le contenu entre parenthèses.
     * par exemple : 'int(255)'' retournera '255'.
     * utilse pour parser des series d'ENUM mysql.
     * @param string value le type mysql
     * @return string le contenu entre parenthèses
     */
    protected static function parseInnerParenthValue($value)
    {
        $result = array();
        preg_match('#\(+(.*)\)+#', $value, $result);
        return implode('', explode('\'',$result[1]));
    }

}

?>
