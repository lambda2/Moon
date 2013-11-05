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
 * Classe permettant d'acceder facilement aux fonctionnalitées de base
 * du framework.
 * @author lambda2
 */
class Moon {
    
    /**
     * Crée un nouvel objet
     * @param type $class le nom de la classe a instancier
     * @return Entity Une instance vide de la classe.
     */
    public static function create($class){
        return EntityLoader::getClass($class);
    }
    
    /**
     * Crée un nouvel objet et le charge en utilisant le champ et la valeur
     * spécifiés. Le champ doit etre une clé primaire ou un champ unique.
     * Par exemple le chat avec l'id numero 1 => get('Chat', 'id_chat', '1').
     * Si plusieurs objets correspondent, le premier objet sera retourné.
     * @param string $class la classe a instancier
     * @param string $field le champ a tester
     * @param string $value la valeur du champ
     * @return Entity Une instance de la classe correspondant aux champs.
     */
    public static function get($class, $field, $value){
        $cl = EntityLoader::getClass($class);
        $cl->loadBy($field, $value);
        $cl->autoLoadLinkedClasses();
        return $cl;
    }

    /**
     * Crée un tableau contenant le ou les objets correspondants aux critères 
     * passés en parametre, et les chargent en utilisant le champ et la valeur
     * spécifiés. Le champ ne doit pas forcement etre une clé primaire ou un champ unique.
     * @param string $class la classe a instancier
     * @param string $field le champ a tester
     * @param string $value la valeur du champ
     * @return Array un tableau d'instances de la classe correspondant aux champs.
     */
    public static function getAllWhere($class, $field, $value){
        $cl = EntityLoader::loadAllBy($class,$field,$value);
        return $cl;
    }
    
    /**
     * Retourne tous les objets de la classe présents dans la basede données.
     * Cette méthode peut etre très lourde, car elle instancie chaque objet,
     * et <b>doit etre evitée</b> lorsque il n'y a pas d'interaction avec 
     * ces objets, en utilisant Moon::getAll(). 
     * @param string $class la classe des objets
     * @return array un tableaux contenant les instances de la classe.
     * @see Moon::getAll
     */
    public static function getAllHeavy($class){
        return Entity::getAllObjects($class);
    }
        
    /**
     * Retourne tous les objets de la classe présents dans la basede données.
     * Cette méthode peut etre très lourde, car elle instancie chaque objet,
     * et <b>doit etre evitée</b> lorsque il n'y a pas d'interaction avec 
     * ces objets, en utilisant Moon::getAll(). 
     * @param string $class la classe des objets
     * @return array un tableaux contenant les instances de la classe.
     * @see Moon::getAll
     */
    public static function getEntities($class){
        return EntityLoader::loadAllEntities($class);
    }

    /**
     * Retourne tous les objets de la classe présents dans la base de données
     * sous la forme d'objets légers. Chaque élément du tableau ne contient 
     * que le nom des champs et leur valeurs.
     * Cette méthode est très conseillée si il n'y a aucune interaction
     * avec ces objets (lecture seule).
     * @param string $class la classe des objets
     * @return array un tableaux contenant les instances de la classe.
     */
    public static function getAll($class){
        $cl = EntityLoader::getClass($class);
        $cls = $cl->getAll();
        return $cls;
    }
    
}

?>
