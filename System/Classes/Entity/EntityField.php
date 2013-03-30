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
    protected $defaultValue = null;
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

    public function getHtmlField()
    {
        $field = FieldFactory::createField(
            $this->type,$this->name);
        $field->setRequired(!$this->isNull);
        $field->setPlaceholder($this->defaultValue);
        $field->setVisible(!$this->isAuto);
        $field->setValue($this->value);

        if($this->type == 'enum'){
            foreach ($this->optionsValues as $key => $value) {
                $text = $value;
                if(!is_int($key)){
                    $text = $key;
                }
                $field->addOption($value, $text);
            }
            
        }

        return $field;
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

    /**
     * Gets the value of defaultValue.
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets the value of defaultValue.
     *
     * @param mixed $defaultValue the defaultValue
     *
     * @return self
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

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
}

?>