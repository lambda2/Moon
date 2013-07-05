<?php

/*
 * Représente un champ de formulaire.
 * C'est à dire un de ces types de champ : button, checkbox, color, 
 * date , datetime , datetime-local , email , file, hidden, image, month , 
 * number , password, radio, range , reset, search, submit, tel, text, time , 
 * url ou week.
 */
class Input extends Field
{
   protected $autocomplete = true; 
    /**
     * @var array allowed_types Les types de champ input déclarés
     * valides au 5/3/13 par le W3C.
     */
    protected $allowed_types = array(
       'button','checkbox','color',
       'date','datetime','datetime-local', 
       'email','file','hidden','image','month',
       'number','password','radio','range',
       'reset','search','submit','tel','text',
       'time','url','week');
    
    /**
     * Construit un champ de formulaire.
     * @param string $name l'attribut "name" du champ
     * @param string $type le type du champ
     */
    public function __construct($name, $type="text") {
        parent::__construct();
        $this->name = $name;
        if(!in_array($type, $this->allowed_types)){
            throw new AlertException("Le type $type ne semble pas etre un type de champ valide...");
        }
        $this->type = $type;

    }

    /**
     * Will set the type of the input element.
     * @param string $type the type of the input field.
     * @return Input $this
     */
    public function setType($type)
    {
        if(!in_array($type, $this->allowed_types)){
            throw new AlertException("Le type $type ne semble pas etre un type de champ valide...");
        }
        $this->type = $type;
        return $this;
    }

    /**
     * will enable or disable the autocompletion of 
     * the current input.
     * @return Input self
     */
    public function setAutocomplete($bool = true)
    {
        $this->autocomplete = $bool;
        return $this;
    }
    
    /**
     * Va retourner le code Html de l'input
     */
    public function getHtml() 
    { 
        if(!$this->isVisible()) return '';
        $s = '<input '.$this->getHtmlAttributesList();
        if(!$this->autocomplete)
            $s .= 'autocomplete="off" ';
        if($this->required)
            $s .= 'required';
        else if(!$this->enabled)
            $s .= 'disabled';
        $s .= '>';
        return $s;
    } 
    
    
}

?>
