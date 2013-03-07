<?php

include_once 'Field.php';
/*
 * Représente un champ CheckBox de formulaire.
 * C'est à dire une case à cocher indépendante des autres.
 */
class CheckBox extends Field {
    protected $checked;
    
    /**
     * Construit un champ CheckBox
     * @param string $name l'attribut "name" du champ
     * @param string $value l'attribut "value" du champ
     */
    public function __construct($name, $value, $checked=false) {
        parent::__construct();
        $this->name = $name;
        $this->type = "checkbox";
        $this->value = $value;
        if(is_bool($checked))
            $this->checked = $checked;
    }
    
    /**
     * @return boolean true si la case est cochée
     */
    public function isChecked() {
        return $this->checked;
    }

    public function setChecked($checked) {
        $this->checked = $checked;
        return $this;
    }
    
    /**
     * Va retourner le code Html du champ CheckBox
     */
    public function getHtml() {
        $s = '<input '.$this->getHtmlAttributesList();
        if($this->checked)
            $s .= 'checked='.dbQuote("checked");
        if(!$this->enabled)
            $s .= 'disabled';
        $s .= '>';
        return $s;
    } 


}

/**
 * @TODO : faire un système pour ajouter N champs linkés 
 * entre eux à un formulaire.
 */

?>
