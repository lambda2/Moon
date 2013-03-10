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
 * Va nous permettre de naviguer simplement dans un ensemble de tableaux.
 * @author lambda2
 */
class ArrayBrowser {
    private $arr;
    
    /**
     * Construit un ensemble d'options avec les données du tableau passé
     * en parametre.
     * @param type $tab le tableau contenant les données.
     * @throws AlertException si le parametre n'est pas un tableau
     */
    public function __construct($tab){
        if(!is_array($tab)){
            throw new AlertException("le parametre de création d'une structure d'option doit etre un tableau !");
        }
        $this->arr = array();
        
        foreach ($tab as $key => $value) {
            if(is_array($value)){
            $this->arr[$key] = new ArrayBrowser($value);
            }
            else {
                $this->arr[$key] = $value;
            }
        }
    }
    
    
    public function __get($name){
        if(array_key_exists($name, $this->arr)){
            return $this->arr[$name];
        }
        else {
            throw new AlertException("La propriété $name n'est pas référencée...");
        }
    }
    
    /**
     * Retourne un tableau comprenant les sous niveaux disponibles
     * @return array un tableaux comprenant les sous niveaux disponibles
     */
    public function childs(){
        $s = array();
        foreach ($this->arr as $key => $value) {
            $s[] = $key;
        }
        return $s;
    }
    
    /**
     * Retourne l'arborescence des options disponibles
     * @return string l'arborescence des options disponibles
     */
    public function __toString(){
        $s = "<ul>";
        foreach ($this->arr as $key => $value) {
            if(is_a($this->arr[$key], 'ArrayBrowser')){
                $s .= '<li><b>'.$key.'</b> { '.$value.' } </li>';
            }
            else {
                $s .= '<li>'.$key.' : '.$value.'</li>';
            }
        }
        $s .= "</ul>";
        return $s;
    }
}

?>
