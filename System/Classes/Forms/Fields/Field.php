<?php

/*
 * @TODO : Virer cette include
 */
//include_once '/var/www/moon/Helpers/common.php';

/*
 * Représente un élément de formulaire.
 * Cette classe est abstraite et doit donc etre héritée.
 */
abstract class Field extends Element {

    protected $type;
    protected $name;
    protected $value;
    protected $placeholder;
    
    
    protected $required;
    protected $enabled;


    public function __construct() {
        $this->type = "";
        $this->name = "";
        $this->value = "";
        $this->placeholder = "";

        $this->required = false;
        $this->enabled = true;
    }
    
    public function __toString() {
        return $this->getHtml();
    }

    /**
     * retourne le code HTML du champ
     */
    //abstract public function getHtml();

    
    /**
     * Display the field placeholder as a label.
     * If the field don't have a placeholder or an id, return 
     * an empty string.
     * @return string the html string for the label
     */
    public function getLabel()
    {
        if(!isNull($this->placeholder) and !isNull($this->id))
        {
            return '<label for='.dbQuote($this->id).'>'.$this->placeholder.'</label>';
        }
        else if(!isNull($this->placeholder))
        {
            dbg('Le label du champ '.$this->name.' n\'a pas été placé car aucun id ne lui
                a été donné.',0);
        }
        return '';
    }
    
    public function setLabel($label)
    {
        return $this->setPlaceholder($label);
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }
    
    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function isEnabled() {
        return $this->enabled;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function isRequired() {
        return $this->required;
    }

    public function setRequired($required) {
        $this->required = $required;
        if($required == true)
            $this->enabled = true;
    }

    /**
     * Va créer une chaine contenant la liste de tous les attributs de
     * l'élément de formulaire sans la balise et les champs "required" et 
     * "disabled" (qui sont situés en fin de balise).
     * Les attributs vides ne sont pas affichés.
     * @return string la liste des attributs sous la forme :
     * id="uneId" class="maClasseUne maClasseDeux" etc...
     */
    protected function getHtmlAttributesList() {
        $s = '';
        if ($this->isVisible()) {
            if (!isNull($this->type))
                $s .= 'type=' . dbQuote($this->type) . ' ';
            if (!isNull($this->value) or get_class($this) == 'Option')
                $s .= 'value=' . dbQuote($this->value) . ' ';
            if (!isNull($this->placeholder))
                $s .= 'placeholder=' . dbQuote($this->placeholder) . ' ';
            if (!isNull($this->name))
                $s .= 'name=' . dbQuote($this->name) . ' ';

            // Load the html attributes inherited from the Element class.
            $s .= parent::getHtmlAttributesList() . ' ';
            
        }
        else if(Core::getInstance()->getDevMode() == 'DEBUG'){
            $s .= '<!--- Element de formulaire masqué : '.$this->name.' ('.$this->type.') -->';
        }
        
        return $s;
    }

}


/**
 * @TODO : Ajouter plusieurs classes de conteuneurs de formulaire. Comme optgroup 
 * ou fieldset
 */

?>
