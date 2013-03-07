<?php


/**
 * Représente un bouton dans un formulaire
 */
class Button extends Field{
    
    
    /**
     * @var array allowed_types Les types de boutons déclarés
     * valides au 5/3/13 par le W3C.
     */
    protected $allowed_types = array('button', 'reset', 'submit');
    
    protected $inner_text;
    
    /**
     * Construit un bouton de formulaire.
     * @param string $name l'attribut "name" du bouton
     * @param string $type le type du bouton
     * @param string $value la valeur du bouton
     */
    public function __construct($name, $type="submit", $value="", $text="") {
        parent::__construct();
        $this->name = $name;
        $this->value = $value;
        $this->inner_text = $text;
        if(!in_array($type, $this->allowed_types)){
            throw new AlertException("Le type $type ne semble pas etre un type de bouton valide...");
        }
        $this->type = $type;
    }
    
    public function getInnerText() {
        return $this->inner_text;
    }

    public function setInnerText($inner_text) {
        $this->inner_text = $inner_text;
        return $this;
    }
    
    
    /**
     * Va retourner le code Html du bouton
     */
    public function getHtml() {
        $s = '<button '.$this->getHtmlAttributesList();
        if(!$this->enabled)
            $s .= 'disabled';
        $s .= '>'.$this->inner_text.'</button>';
        return $s;
    } 
    
}

?>
