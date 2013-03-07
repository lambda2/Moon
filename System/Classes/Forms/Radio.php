<?php

include_once 'CheckBox.php';
/*
 * Représente un champ radio de formulaire.
 * C'est à dire une case à cocher dépendant des autres.
 */
class Radio extends CheckBox {
    protected $checked;
    
    /**
     * Construit un champ radio
     * @param string $name l'attribut "name" du champ
     * @param string $value l'attribut "value" du champ
     */
    public function __construct($name, $value, $checked=false) {
        parent::__construct($name, $value, $checked);
        $this->type = "radio";
    }


}

/**
 * TODO : faire un système pour ajouter N champs linkés 
 * entre eux à un formulaire.
 */

?>
