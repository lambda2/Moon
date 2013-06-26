<?php

/**
 * Element option d'un select d'un formulaire.
 * Autrement dit, une option d'une liste déroulante.
 */
class Option extends Field {
    
    protected $innerText;
    protected $selected = false;
    
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
        
        if(!$this->isVisible()) 
            return '';

        $s = '<option '.$this->getHtmlAttributesList();
        if(!$this->enabled)
            $s .= 'disabled';
        else if($this->selected)
            $s .= 'selected';
        $s .= '>'.$this->innerText.'</option>';
        return $s;
    }   

    /**
     * Gets the value of innerText.
     *
     * @return mixed
     */
    public function getInnerText()
    {
        return $this->innerText;
    }

    /**
     * Sets the value of innerText.
     *
     * @param mixed $innerText the innerText
     *
     * @return self
     */
    public function setInnerText($innerText)
    {
        $this->innerText = $innerText;

        return $this;
    }

    /**
     * Gets the value of selected.
     *
     * @return mixed
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Sets the value of selected.
     *
     * @param mixed $selected the selected
     *
     * @return self
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }
}

?>
