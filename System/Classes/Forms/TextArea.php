<?php

/**
 * Représente un champ de texte sur plusieurs lignes.
 * 
 */
class TextArea extends Field{
    
    protected $inner_text;
    protected $rows;
    
    /**
     * Construit un champ de texte multi-lignes de formulaire.
     * @param string $name l'attribut "name" du champ
     * @param string $text le texte présent dans le champ. A ne pas confondre
     * avec le defaultValue qui sera la valeur affichée lorsque le texte est vide.
     */
    public function __construct($name, $text="") {
        parent::__construct();
        $this->name = $name;
        $this->inner_text = $text;
        $this->rows = 2;
    }
    
    public function getInnerText() {
        return $this->inner_text;
    }

    public function setInnerText($inner_text) {
        $this->inner_text = $inner_text;
        return $this;
    }
    
    public function getRows() {
        return $this->rows;
    }

    /**
     * Va définir le nombre de lignes de la zone de texte.
     * Par défault, une zone de texte fait 2 lignes.
     * Au dela, un ascenceur apparait.
     * @param int $rows le nombre de lignes affichées.
     * @return \TextArea l'instance courante
     */
    public function setRows($rows) {
        $this->rows = $rows;
        return $this;
    }
            
    /**
     * Va retourner le code Html du champ de texte 
     */
    public function getHtml() {
        $s = '<textarea '.$this->getHtmlAttributesList()
                .'rows='.dbQuote($this->rows).' ';
        if($this->required)
            $s .= 'required';
        else if(!$this->enabled)
            $s .= 'disabled';
        $s .= '>'.$this->inner_text.'</textarea>';
        return $s;
    } 
}

?>
