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
 * Cette classe va nous permettre de charger dynamiquement une classe à partir du néant.
 * C'est la grande force du framework, et doit, de ce fait, rester PROPRE
 *                                                                 ------
 * Beaucoup d'echo sont en commentaire, il vaut mieux les laisser parce que les bugs sont
 * quotidiens dans cette classe. Autorisation de les enlever après une batterie de tests 
 * unitaires plus tordus les uns que les autres.
 */
class EntityLoader {


    /**
     * Va créer une entité vide, c'est à dire un objet, représentant une classe / Table issue
     * de la base de donnée.
     * Note : Si la classe est définie en dur dans l'application, c'est celle çi que l'on va
     * charger <b>si elle hérite de la classe [Entity]</b>.
     * @param string le nom de la classe / table a charger.
     * @throws CriticalException si la méthode ne parvient pas a instancier la classe.
     */
    public static function getClass($className) {
        $return = null;
        if (class_exists($className) 
            && in_array('Entity', class_parents($className))) 
        {
            $return = new $className();
        }
        else if (class_exists(ucfirst($className)) 
            && in_array('Entity', class_parents(ucfirst($className)))) 
        {
            $className  = ucfirst($className);
            $return = new $className();
        }
        else 
        {
            //echo("classe inexistante... scan de la base de données...");
            $reducClassName = $className;
            $withPrefix = $reducClassName;
            if(Core::getInstance()->getDbPrefix() != '')
                $withPrefix = strstr($reducClassName, Core::getInstance()->getDbPrefix());
            //echo("contient le prefixe ? ");
            //echo $withPrefix ? 'true' : 'false';

            if ($withPrefix != FALSE) {
                //echo "before : $reducClassName";
                $reducClassName = substr_replace(
                    $className, 
                    '', 
                    -(strlen(Core::getInstance()->getDbPrefix())), 
                    strlen(Core::getInstance()->getDbPrefix())
                    );
                
                //echo "after : $reducClassName";
                
            }
            //echo('isValidClass ?  ('.$reducClassName.Core::getInstance()->getDbPrefix().')');
            if (Core::isValidClass($reducClassName . Core::getInstance()->getDbPrefix())) {
                //echo('isValidClass !  ('.$reducClassName.Core::getInstance()->getDbPrefix().')');
                $return = new TableEntity($reducClassName);
            }
            else if (Core::isValidClass($reducClassName)) {
                $return = new TableEntity($reducClassName);
            }
            else {
                // Big Badabim Boum ! Badabada Boum !
                throw new CriticalException('Impossible d\'instancier la classe ' . $reducClassName);
            }
        }
        
        return $return;
    }

    /**
     * Va [faire un essai pour] charger <b>une</b> instance <b>unique</b> de la
     * classe spécifiée.
     * @param String $fieldSchema le nom de la classe et du champ 
     * a rechercher (de préférence un identifiant) sous la forme table.champ
     * @param String $fieldValue la valeur de ce champ
     * @return Mixed une instance de la classe
     */
    public static function loadInstance($fieldSchema, $fieldValue) {

        $target = split('\.', $fieldSchema);
        $class  = self::getClass($target[0]);

        if ($target[1] != "" && $fieldValue != null) {
            $class->loadBy($target[1], $fieldValue);
        }
        return $class;
    }
    
    /**
     * Will load Entities from an SQL request. The table name have 
     * to be specified.
     * @param $request String the sql request to execute
     * @param $table String the name of the table / Class to be instancied
     * @return Entities the Entities who matches with the request
     */
    public static function loadEntitiesFromRequest($request,$table)
    {
        $t = array();
        try {
            Profiler::addRequest($request);
            $Req = Core::getBdd()->getDb()->prepare($request);
            $Req->execute(array());
        } catch (Exception $e) {
            throw new OrmException(
            "Unable to execute the request '$request' : ["
            . $e->getMessage() . ']');
        }

        $t = new Entities($table);
        // Si on récupère quelque chose :
        if ($Req->rowCount() != 0)
        {
            while ($res = $Req->fetch(PDO::FETCH_OBJ))
            {
                $f          = self::getClass($table);
                $pri        = $f->getValuedPrimaryFields($res);
                if (!isNull($pri))
                {
                    $f->loadByArray($pri);
                    $f->autoLoadLinkedClasses();
                    $t->addEntity($f);
                }

            }
        }
        return $t;
    }

    /**
     * Charge tous les objets en fonction d'un ou plusieurs parametres
     * et renvoie l'ensemble sous forme d'un groupe d'entitées.
     * @return Entities le groupe d'entitées.
     */
    public static function loadAllEntities($classe) {

        $c = EntityLoader::getClass($classe);
        return new Entities($c->getTable());

        /* ### old code
        $request = "SELECT * FROM {$c->getTable()}";
        return EntityLoader::loadEntitiesFromRequest($request,$c->getTable());
        */
    }

    /**
     * Charge tous les objets en fonction d'un ou plusieurs parametres
     * et renvoie l'ensemble sous forme d'un groupe d'entitées.
     * @return Entities le groupe d'entitées.
     */
    public static function loadAllEntitiesBy($classe,$field, $value, $filter=null) {
        $c = self::getClass($classe);
        $tab = $c->getTable();
        $request = "";
        $orm = Core::getBdd();
        $fi = array();
        foreach(array_keys($c->getFields()) as $v) { $fi[] = $tab.'.'.$v; }
        $selection = implode(',',$fi);
        /**
         * Dans le cas ou on a plusieurs champs contraints (plusieurs clés primaires
         * par exemple...) on ajoute les contraintes dans la requete.
         */

        $orm->select($selection)->from($tab);
        if(is_array($field)
            and is_array($value)
            and count($field) == count($value))
        {
            foreach ($field as $key=>$cle)
            {
                $orm->where($tab.'.'.$cle,$value[$key]);
            }
        }
        else
        {
            $orm->where($tab.'.'.$field,$value);
        }
        return $orm->fetchEntities();
    }



}

?>
