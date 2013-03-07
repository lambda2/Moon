<?php

/**
 * Element select d'un formulaire.
 * Autrement dit, une liste déroulante.
 * Les éléments de la liste, c'est à dire des [option] peuvent etre
 * rajoutés ou automatiquement générés.
 */
class Select extends Field {

    protected $options;

    /**
     * Construit un bouton de formulaire.
     * @param string $name l'attribut "name" du bouton
     * @param string $type le type du bouton
     * @param string $value la valeur du bouton
     */
    public function __construct($name, $options = array()) {
        parent::__construct();
        $this->name = $name;

        if (!$this->isValidOptionsArray($options)) {
            $this->options = array();
        }
        else
            $this->options = $options;

        $this->type = "select";
    }

    /**
     * Ajout l'instance de l'option passée en parametre
     * aux options de la liste déroulante.
     * @param Option $option
     * @return boolean true si l'objet est valide. False sinon.
     */
    public function addOptionObject($option) {
        $valid = true;

        if ($this->isAValidOption($option))
            $this->options[] = $option;
        else
            $valid = false;

        return $valid;
    }

    /**
     * Ajoute le tableau d'Options passé en parametre aux
     * options de la liste déroulante.
     * @param array $options le tableau d'options
     * @return boolean true si le tableau est valide. False sinon.
     */
    public function addOptionsArray($options) {
        $valid = true;
        if ($this->isValidOptionsArray($options)) {
            foreach ($options as $option)
                $this->options[] = $option;
        }
        else
            $valid = false;

        return $valid;
    }

    /**
     * Verifie qu'une variable soit bien un tableau d'Options
     * @param array $options la variable a tester
     * @return boolean true si le tableau est valide
     */
    protected function isValidOptionsArray($options) {
        $valid = false;
        if (is_array($options)) {
            $valid = true;
            foreach ($options as $option) {
                if (!$this->isAValidOption($option)) {
                    $valid = false;
                    throw new AlertException(
                    'Le tableau d\'options semble invalide... ['
                    . dbQuote($options)
                    . ']');
                }
            }
        }
        return $valid;
    }

    /**
     * Ajoute une option avec la value et le texte spécifié.
     * Si le texte est vide, alors le texte sera le meme que la value.
     * @param string $value la valeur de l'attribut "value" de l'option
     * @param string $text le texte de l'option
     * @return boolean true si tout c'est ien passé, false sinon
     */
    public function addOption($value, $text) {
        $o = new Option($value, $text);
        return $this->addOptionObject($o);
    }

    /**
     * Supprime toutes les options enregistrées dans le select.
     */
    public function clearOptions() {
        $this->options = array();
    }

    /**
     * Retourne les options enregistrées dans le select.
     * @return array(Option) les options
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Verifie qu'une variable est une option
     * @param Option $option la variable a tester
     * @return boolean true si l'option est valide
     */
    protected function isAValidOption($option) {
        return is_a($option, 'Option');
    }

    /**
     * Va retourner le code Html du bouton
     */
    public function getHtml() {
        $s = '<select ' . $this->getHtmlAttributesList();
        if ($this->required)
            $s .= 'required';
        else if (!$this->enabled)
            $s .= 'disabled';
        $s .= '>';

        foreach ($this->options as $option) {
            $s .= $option->getHtml();
        }

        $s .= '</select>';
        return $s;
    }

}

?>
