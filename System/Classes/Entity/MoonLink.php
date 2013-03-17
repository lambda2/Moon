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
 * Le glu entre nos classes.
 * Cette classe va permettre de définir les liaisons entre les attributs des 
 * classes
 * @author lambda2
 */
class MoonLink {
    public $attribute;
    public $destinationTable;
    public $destinationColumn;
    public $instance = null;
    
    public function __construct($source, $destination, $instance = null){
        $s = explode('.', $destination);
        $this->attribute = $source;
        $this->destinationTable = $s[0];
        $this->destinationColumn = $s[1];
        $this->instance = $instance;
    }
    
    public function getLinkedInstance($value){
        return EntityLoader::loadInstance(
                $this->destinationTable.'.'.$this->destinationColumn, $value);
    }
    
    public function get(){
        return $this->instance;
    }
    
    public function __toString(){
        return 'le champ '.$this->attribute
                .' reference le champ '.$this->destinationColumn
                .' de la table '.$this->destinationTable;
    }
    
}

?>
