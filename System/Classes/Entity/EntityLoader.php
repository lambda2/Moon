<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class EntityLoader {

    public static function getClass($className) {
        $return = null;
        try {
            if (class_exists($className) && in_array('Entity', class_parents($className))) {
                $return = new $className();
            }
            else {
                //dbg("classe inexistante... scan de la base de données...");
                $reducClassName = $className;

                $withPrefix = strstr($reducClassName, Core::getInstance()->getDbPrefix());
                //dbg("contient le prefixe ? ");
                //echo $withPrefix ? 'true' : 'false';

                if ($withPrefix != FALSE) {
                    //echo "before : $reducClassName";
                    $reducClassName = substr_replace($className, '', -(strlen(Core::getInstance()->getDbPrefix())), strlen(Core::getInstance()->getDbPrefix()));
                    
                    //echo "after : $reducClassName";
                    
                }

                if (Core::isValidClass($reducClassName . Core::getInstance()->getDbPrefix())) {
                    //dbg('isValidClass !  ('.$reducClassName.Configuration::getInstance()->getDbPrefix().')');
                    
                    $return = new TableEntity($reducClassName);
                }
                else if (Core::isValidClass($reducClassName)) {
                    $return = new TableEntity($reducClassName);
                }
                else {
                    throw new CriticalException('Impossible d\'instancier la classe ' . $reducClassName);
                }
            }
        } catch (Exception $e) {
            var_dump($e);
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

}

?>
