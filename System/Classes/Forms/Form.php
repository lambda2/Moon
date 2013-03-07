<?php

/**
 * La classe représentant un formulaire et contenant
 * des champs de formulaires.
 * @author lambda2
 * @see Field
 */
class Form {

    protected $fields;
    protected $method;
    protected $name;
    protected $action;

    /**
     * Construit un nouveau formulaire vide
     * @param string $name le nom du formulaire
     * @param string $action la page ou les données du formulaire seront envoyées.
     */
    public function __construct($name = "", $action = "#") {
        $this->fields = array();
        $this->method = "post";
        $this->name   = $name;
        $this->action = $action;
    }

    /**
     * Ajoute un élément de formulaire au formulaire
     * @param Field $field l'élément de formulaire à ajouter
     * @throws AlertException si l'élément fourni n'est pas un élément valide.
     */
    public function addField($field) {
        if (!is_a($field, 'Field')) {
            throw new AlertException("Le champ $field ne semble pas etre
                    un élément de formulaire valide...");
        }
        else {
            $this->fields[] = $field;
        }
    }

    /**
     * Supprime tous les champs du formulaire
     */
    public function clearFields() {
        $this->fields = array();
    }

    public function getHtml() {
        $s = '<form action=' . dbQuote($this->action)
                . ' method=' . dbQuote($this->method) . ' ';
        if (!isNull($this->name)) {
            $s .= 'name=' . dbQuote($this->name).' ';
        }
        $s .= '>';
        foreach ($this->fields as $field) {
            $s .= $field->getHtml();
        }
        $s .= '</form>';
    }

    public function getFields() {
        return $this->fields;
    }

    /* @TODO: vérifier que l'entrée est bien un tableau de champs (Field) */
    public function setFields($fields) {
        $this->fields = $fields;
        return $this;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        if (strcasecmp($method, 'get') || strcasecmp($method, 'post')) {
            $this->method = $method;
        }
        else {
            throw new AlertException("La méthode $method ne semble etre une 
                    methode valide pour un formulaire html. 
                    @see http://www.w3.org/TR/html-markup/form.html#form.attrs.method");
        }
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getAction() {
        return $this->action;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

}

?>
