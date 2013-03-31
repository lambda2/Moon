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
            //echo 'class '.$className.' do not exists...';
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

}

?>
