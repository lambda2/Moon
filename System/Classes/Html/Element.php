<?php

/*
 * Représente un élément HTML.
 * Cette classe regroupe toutes les propriétés communes
 * aux éléments HTML.
 */
abstract class Element {

    protected $title = '';
    protected $id = '';
    protected $accesskey = '';
    protected $dir = '';
    protected $lang = '';
    protected $tabIndex = '';
    protected $hidden = false;
    
    protected $classes;
    protected $datas;
    protected $customAttributes;
    
    protected $visible = true;


    public function __construct() {

        $this->classes = array();
        $this->datas = array();
        $this->customAttributes = array();

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
            throw new AlertException(
                "Impossible d'ajouter une classe nulle ou vide à l'élément {$this->name}");
        if (strcmp($classe[0], '.') == 0)
            throw new AlertException(
                "La classe [ $classe ] ne doit pas commencer par un point lors de l'ajout à l'élément {$this->name}");

        $this->classes[] = $classe;
    }

    
    /**
     * retourne les classes de ce champ
     * @return array les classes du champ
     */
    public function getClasses(){
        return $this->classes;
    }

    public function isVisible() {
        return $this->visible;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Supprime de cet élément toutes les classes enregistrées.
     */
    public function clearClasses() {
        $this->classes = array();
    }

    /**
     * Ajoute ou met à jour un élément "data" pour l'élément
     * @param String $field le nom du data
     * @param String $value la valeur du data
     * @throws AlertException si l'élément data est incorrect
     */
    public function addData($field, $value) {

        if (isNull($field))
            throw new AlertException("Impossible d'ajouter une data nulle ou vide à l'élément {$this->getHTML()}");

        $this->datas[$field] = $value;
    }
    
    /**
     * Retourne les datas enregistrées pour l'élément
     * sous la forme d'un tableau associatif
     * (nom data => valeur data)
     * @return array les datas
     */
    public function getDatas(){
        return $this->datas;
    }

    /**
     * Supprime un élément "data" pour l'élément
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
     * Ajoute ou met à jour un attribut "personnalisé" pour l'élément
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
     * Supprime un attribut "personnalisé" pour l'élément
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
     * Supprime de l'élément tous les attributs personnalisés enregistrés.
     */
    public function clearCustomAttribute() {
        $this->customAttributes = array();
    }
    
    /**
     * retourne les attributs personnalisés enregistrés 
     * à cet élément sous la forme d'un tableau associatif
     * (nom attributs => valeur attributs)
     * @return array les attributs
     */
    public function getCustomAttributes(){
        return $this->customAttributes;
    }

    /**
     * Va créer une chaine contenant la liste de tous les attributs de
     * l'élément sans la balise et les champs "required" et 
     * "disabled" (qui sont situés en fin de balise).
     * Les attributs vides ne sont pas affichés.
     * @return string la liste des attributs sous la forme :
     * id="uneId" class="maClasseUne maClasseDeux" etc...
     */
    protected function getHtmlAttributesList() {
        $s = '';
        if ($this->isVisible()) {
            if (!isNull($this->title))
                $s .= 'title=' . dbQuote($this->title) . ' ';
            if (!isNull($this->id))
                $s .= 'id=' . dbQuote($this->id) . ' ';
            if (!isNull($this->accesskey))
                $s .= 'accesskey=' . dbQuote($this->accesskey) . ' ';
            if (!isNull($this->lang))
                $s .= 'lang=' . dbQuote($this->lang) . ' ';
            if (!isNull($this->tabIndex))
                $s .= 'tabIndex=' . dbQuote($this->tabIndex) . ' ';
            
            if (!isNull($this->datas))
            {
                foreach ($this->datas as $data_type => $data_val) {
                    $s .= 'data-' . $data_type . '=' . dbQuote($data_val) . ' ';
                }
            }

            if (!isNull($this->customAttributes))
            {
                foreach ($this->customAttributes as $custom_type => $custom_val) {
                    $s .= $custom_type . '=' . dbQuote($custom_val) . ' ';
                }
            }

            if (!isNull($this->classes))
                $s .= 'class=' . dbQuote($this->classes) . ' ';
            
        }
        else if(Core::getInstance()->getDevMode() == 'DEBUG'){
            $s .= '<!--- Element de formulaire masqué : '.$this->name.' ('.$this->type.') -->';
        }
        
        return $s;
    }

    protected function getBeforeCloseTagAttributesList()
    {
        $s = '';
        if ($this->hidden)
            $s .= 'hidden';
        return $s;
    }

}

/**
 * @TODO : Ajouter plusieurs classes de conteuneurs de formulaire. Comme optgroup 
 * ou fieldset
 */

?>
