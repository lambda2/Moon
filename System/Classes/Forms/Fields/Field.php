<?php

/*
 * @TODO : Virer cette include
 */
//include_once '/var/www/lwf/Helpers/common.php';

/*
 * Représente un élément de formulaire.
 * Cette classe est abstraite et doit donc etre héritée.
 */
abstract class Field {

    protected $type;
    protected $name;
    protected $id;
    protected $value;
    protected $placeholder;
    
    protected $classes;
    protected $datas;
    protected $customAttributes;
    
    protected $required;
    protected $enabled;
    protected $visible;

    public function __construct() {
        $this->type = "";
        $this->name = "";
        $this->id = "";
        $this->value = "";
        $this->placeholder = "";

        $this->classes = array();
        $this->datas = array();
        $this->customAttributes = array();

        $this->required = false;
        $this->enabled = true;
        $this->visible = true;
    }
    
    public function __toString() {
        return $this->getHtml();
    }

    /**
     * retourne le code HTML du champ
     */
    abstract public function getHtml();

    /**
     * Ajoute la classe a l'élément. 
     * @param type $classe le nom de la classe (sans le '.')
     * @throws AlertException si le nom est incorrect
     */
    public function addClass($classe) {
        if (isNull($classe))
            throw new AlertException("Impossible d'ajouter une classe nulle ou vide à l'élément {$this->name}");
        if (strcmp($classe[0], '.') == 0)
            throw new AlertException("La classe [ $classe ] ne doit pas commencer par un point lors de l'ajout à l'élément {$this->name}");

        $this->classes[] = $classe;
    }
    
    /**
     * retourne les classes de ce champ
     * @return array les classes du champ
     */
    public function getClasses(){
        return $this->classes;
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

    public function isVisible() {
        return $this->visible;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
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
     * Supprime de ce champ toutes les classes enregistrées.
     */
    public function clearClasses() {
        $this->classes = array();
    }

    /**
     * Ajoute ou met à jour un élément "data" du champ
     * @param String $field le nom du data
     * @param String $value la valeur du data
     * @throws AlertException si l'élément data est incorrect
     */
    public function addData($field, $value) {

        if (isNull($field))
            throw new AlertException("Impossible d'ajouter une data nulle ou vide à l'élément {$this->name}");

        $this->datas[$field] = $value;
    }
    
    /**
     * Retourne les datas enregistrées a ce champ
     * sous la forme d'un tableau associatif
     * (nom data => valeur data)
     * @return array les datas
     */
    public function getDatas(){
        return $this->datas;
    }

    /**
     * Supprime un élément "data" du champ
     * Par exemple, pour supprimer un élément "data-book-id", on utilisera
     * <b>removeData("book-id");</b>
     * @param String $field l'élément à supprimer
     * @throws AlertException si l'élément data est incorrect
     */
    public function removeData($field) {
        if (isNull($field))
            throw new AlertException("Impossible d'enlever une data nulle ou vide à l'élément {$this->name}");

        $this->datas = array_remove_key($this->datas, $field);
    }

    /**
     * Supprime du champ tous les éléments data enregistrés.
     */
    public function clearData() {
        $this->datas = array();
    }
    
    
    /**
     * Ajoute ou met à jour un attribut "personnalisé" au champ
     * @param String $field le nom de l'attribut personnalisé
     * @param String $value la valeur de l'attribut personnalisé
     * @throws AlertException si l'élément personnalisé est incorrect
     */
    public function addCustomAttribute($field, $value) {

        if (isNull($field))
            throw new AlertException("Impossible d'ajouter un élément personnalisé nulle ou vide à l'élément {$this->name}");

        $this->customAttributes[$field] = $value;
    }

    /**
     * Supprime un attribut "personnalisé" au champ
     * Par exemple, pour supprimer un élément "autocomplete", on utilisera
     * <b>removeCustomAttribute("autocomplete");</b>
     * @param String $field l'attribut à supprimer
     * @throws AlertException si l'attribut personnalisé est incorrect
     */
    public function removeCustomAttribute($field) {
        if (isNull($field))
            throw new AlertException("Impossible d'enlever un attribut personnalisé nulle ou vide à l'élément {$this->name}");

        $this->customAttributes = array_remove_key($this->customAttributes, $field);
    }

    /**
     * Supprime du champ tous les attributs personnalisés enregistrés.
     */
    public function clearCustomAttribute() {
        $this->customAttributes = array();
    }
    
    /**
     * retourne les attributs personnalisés enregistrés 
     * à ce champ sous la forme d'un tableau associatif
     * (nom attributs => valeur attributs)
     * @return array les attributs
     */
    public function getCustomAttributes(){
        return $this->customAttributes;
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
            if (!isNull($this->id))
                $s .= 'id=' . dbQuote($this->id) . ' ';
            if (!isNull($this->value))
                $s .= 'value=' . dbQuote($this->value) . ' ';
            if (!isNull($this->placeholder))
                $s .= 'placeholder=' . dbQuote($this->placeholder) . ' ';
            if (!isNull($this->name))
                $s .= 'name=' . dbQuote($this->name) . ' ';
            
            foreach ($this->datas as $data_type => $data_val) {
                $s .= 'data-' . $data_type . '=' . dbQuote($data_val) . ' ';
            }
            
            foreach ($this->customAttributes as $custom_type => $custom_val) {
                $s .= $custom_type . '=' . dbQuote($custom_val) . ' ';
            }
            
            if (!isNull($this->classes))
                $s .= 'class=' . dbQuote($this->classes) . ' ';
            
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
