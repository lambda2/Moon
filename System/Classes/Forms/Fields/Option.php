<?php

/**
 * Element option d'un select d'un formulaire.
 * Autrement dit, une option d'une liste déroulante.
 */
class Option extends Field {
    
    protected $innerText;
    
    /**
     * Construit une option d'un select d'un formulaire.
     * @param string $value l'attribut "value" de l'option
     * @param string $text le texte de l'option
     */
    public function __construct($value, $text="") {
        parent::__construct();
        $this->value = $value;
        if(isNull($text)){
            $this->innerText = $this->value;
        }
        else
            $this->innerText = $text;
    }
    
    /**
     * Va retourner le code Html de l'option
     */
    public function getHtml() {
        $s = '<option '.$this->getHtmlAttributesList();
        if(!$this->enabled)
            $s .= 'disabled';
        $s .= '>'.$this->innerText.'</option>';
        return $s;
    }   
}

?>
