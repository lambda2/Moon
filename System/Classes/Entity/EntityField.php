<?php

/*
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * Représente le champ d'une entité (pas le champ de formulaire).
 * @see Field
 */
class EntityField {

    protected $type;
    protected $name;
    protected $value = null;
    protected $isNull = false;
    protected $isPrimary = false;
    protected $isForeign = false;
    protected $foreignTarget = null;
    protected $foreignDisplayTarget = null;
    protected $placeHolder = null;
    protected $optionsValues = array();
    protected $isAuto = false;

    // Pour la data sémantique en html
    protected $microformat = null;

    /**
     * Contruit un champ avec le type et le nom fourni en 
     * parametre.
     */
    public function __construct($type, $name, $value=null) {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }

    public function __toString(){
        return $this->value;
    }

    public function getHtmlField()
    {
        $field = null;
        
        /** 
         * Dans le cas d'une clé étrangère, on va devoir générer
         * un champ de type select (dans un premier temps...) et
         * lister toutes les valeurs possibles pour la table distante.
         * @TODO : pour les relations (* -> *), il va falloir etre fort...
         */
        if ($this->isForeign and !isNull($this->foreignTarget))
        {
            $target = explode('.',$this->foreignTarget);
            $table = $target[0];
            $attr = $target[1];
            $values = Core::getBdd()->getAttributeFrom($attr, $table);
            if(isNull($this->foreignDisplayTarget))
            {
                $displayValues = $values;
            }
            else
            {
                $displayTarget = explode('.',$this->foreignDisplayTarget);
                $displayTable = $table;
                $displayAttr = $displayTarget[0];

                if(count($displayTarget) > 1)
                {
                    $displayTable = $displayTarget[0];
                    $displayAttr = $displayTarget[1];
                }

                $displayValues = Core::getBdd()->getAttributeFrom(
                    $displayAttr, $displayTable);
            }

            if(count($displayValues) != count($values))
            {
                echo "incohérence select généré pour la table distante...";
            }

            $field = FieldFactory::createField(
            'enum',$this->name);
            $field->setRequired(!$this->isNull);
            $field->setPlaceholder($this->placeHolder);
            $field->setVisible(!$this->isAuto);
            $field->setValue($this->value);

            if($this->isNull)
            {
                $o = new Option('', 'Aucun');
                if($this->value == '')
                {
                    $o->setSelected(true);
                }
                $field->addOptionObject($o);
            }

            for ($i=0; $i < count($values); $i++) { 
                $o = new Option($values[$i], $displayValues[$i]);
                if($this->value == $values[$i])
                {
                    $o->setSelected(true);
                }
                $field->addOptionObject($o);
            }

            

        }
        else 
        {
            $field = FieldFactory::createField(
            $this->type,$this->name);
            $field->setRequired(!$this->isNull);
            $field->setPlaceholder($this->placeHolder);
            $field->setVisible(!$this->isAuto);
            $field->setValue($this->value);

            if($this->type == 'enum'){
                foreach ($this->optionsValues as $key => $value) {
                    $text = $value;
                    if(!is_int($key)){
                        $text = $key;
                    }

                    $o = new Option($value, $text);
                    if($this->value == $value)
                    {
                        $o->setSelected(true);
                    }
                    
                    $field->addOptionObject($o);
                }
                
            }
        }

        return $field;
    }

    protected function autoloadForeignValues()
    {
        // Si ce champ référence le champ d'une autre table...
        if($this->isForeign)
        {

        }
    }

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of value.
     *
     * @param mixed $value the value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the value of isNull.
     *
     * @return mixed
     */
    public function isNull()
    {
        return $this->isNull;
    }

    /**
     * Sets the value of isNull.
     *
     * @param mixed $isNull the isNull
     *
     * @return self
     */
    public function setIsNull($isNull)
    {
        $this->isNull = $isNull;

        return $this;
    }

    /**
     * Gets the value of isPrimary.
     *
     * @return mixed
     */
    public function isPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * Sets the value of isPrimary.
     *
     * @param mixed $isPrimary the isPrimary
     *
     * @return self
     */
    public function setIsPrimary($isPrimary)
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    /**
     * Gets the value of isForeign.
     *
     * @return mixed
     */
    public function isForeign()
    {
        return $this->isForeign;
    }

    /**
     * Sets the value of isForeign.
     *
     * @param mixed $isForeign the isForeign
     *
     * @return self
     */
    public function setIsForeign($isForeign)
    {
        $this->isForeign = $isForeign;

        return $this;
    }

    public function setForeignTarget($target)
    {
        $this->foreignTarget = $target;
        return $this;
    }

    public function getForeignTarget()
    {
        return $this->foreignTarget;
    }

    public function setForeignDisplayTarget($target)
    {
        $this->foreignDisplayTarget = $target;
        return $this;
    }

    public function setLabelColumn($label)
    {
        return $this->setForeignDisplayTarget($label);
    }

    public function getForeignDisplayTarget()
    {
        return $this->foreignDisplayTarget;
    }

    /**
     * Gets the value of placeHolder.
     *
     * @return mixed
     */
    public function placeHolder()
    {
        return $this->placeHolder;
    }

    /**
     * Sets the value of placeHolder.
     *
     * @param mixed $placeHolder the placeHolder
     *
     * @return self
     */
    public function setPlaceHolder($placeHolder)
    {
        $this->placeHolder = $placeHolder;

        return $this;
    }

    /**
     * Gets the value of optionsValues.
     *
     * @return mixed
     */
    public function getOptionsValues()
    {
        return $this->optionsValues;
    }

    /**
     * Sets the value of optionsValues.
     *
     * @param mixed $optionsValues the optionsValues
     *
     * @return self
     */
    public function setOptionsValues($optionsValues)
    {
        $this->optionsValues = $optionsValues;

        return $this;
    }

    /**
     * Gets the value of isAuto.
     *
     * @return mixed
     */
    public function getIsAuto()
    {
        return $this->isAuto;
    }

    /**
     * Sets the value of isAuto.
     *
     * @param mixed $isAuto the isAuto
     *
     * @return self
     */
    public function setIsAuto($isAuto)
    {
        $this->isAuto = $isAuto;

        return $this;
    }

    /**
     * Gets the value of microformat.
     *
     * @return mixed
     */
    public function getMicroformat()
    {
        return $this->microformat;
    }

    /**
     * Sets the value of microformat.
     *
     * @param mixed $microformat the microformat
     *
     * @return self
     */
    public function setMicroformat($microformat)
    {
        $this->microformat = $microformat;

        return $this;
    }


    /**
     * Synonymes des getters et des setters ci dessus :
     */

    public function setRequired($required){
        $this->isNull = !$required;
    }
}

?>