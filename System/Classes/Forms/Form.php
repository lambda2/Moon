<?php

/**
 * La classe représentant un formulaire et contenant
 * des champs de formulaires.
 * @author lambda2
 * @see Field
 */
class Form extends Element {

    protected $fields;
    protected $method;
    protected $name;
    protected $displayLabels = false;
    protected $action;
    protected $buttonLabel;
    protected $customButtons = array();

    /**
     * Construit un nouveau formulaire vide
     * @param string $name le nom du formulaire
     * @param string $action la page ou les données du formulaire seront envoyées.
     */
    public function __construct($name = "", $action = "#") {
        $this->fields       = array();
        $this->method       = "post";
        $this->name         = $name;
        $this->action       = $action;
        $this->buttonLabel  = 'valider';
    }

    /**
     * Will return the field with the given name
     * wich is already in the current form.
     * @param string $name the name of the field to get.
     * @return Field if the field exists, false otherwise.
     */
    public function getField($name) {
        if(array_key_exists($name,$this->fields))
        {
            return $this->fields[$name];
        }
        else
        {
            return false;
        }
    }

    /**
     * Ajoute un élément de formulaire au formulaire
     * @param Field $field l'élément de formulaire à ajouter
     * @throws AlertException si l'élément fourni n'est pas un élément valide.
     */
    public function addField($field) {
        if (is_a($field, 'Button')) {
            $this->addCustomButton($field);
        }
        else if (is_a($field, 'Field')) {
            $this->fields[$field->getName()] = $field;
        }
        else {
            throw new AlertException("Le champ $field ne semble pas etre
                    un élément de formulaire valide...");
        }
    }

    /**
     * Will add the given element to the form
     */
    public function addElement($element)
    {
        if (is_a($element, 'Element')) {
            $this->fields[] = $element;
        }
        else {
            throw new AlertException("L'élément $element ne semble pas etre
                    un élément html valide...");
        }
    }

    /**
	 * Will load all the data / classes / labels in the given array.
	 * Example structure : 
	 *  ---------------------------------------------
	 * |
	 * | array(
	 * |     'nom' => 
	 * |         array(
	 * |             'label' => 'family name'
	 * |         ),
	 * |     'email' => 
	 * |         array(
	 * |             'label' => 'email adress',
	 * |             'rules' => 'email'
	 * |         ),
	 * | )
	 * |
	 *  ---------------------------------------------
	 * 
	 * @return Form self
	 */
	public function loadDataFromArray($array)
	{
        if($array !== false)
        {
            foreach ($array as $field => $datas) {

                if($field == 'form')
                {
                    if(array_key_exists('align',$datas))
                    {
                        $this->addData('align',$datas['align']);
                    }
                }
                else if(array_key_exists($field, $this->fields))
                {

                    foreach ($datas as $key => $val)
                    {
                        $currentField = $this->fields[$field];

                        switch($key)
                        {
                            case 'label':
                                $currentField->setLabel($val)->setId($field);
                            break;

                            case 'editable':
                                $currentField->setEnabled($val);
                            break;

                            case 'required':
                                $currentField->setRequired($val);
                            break;

                            case 'visible':
                                $currentField->setVisible($val);
                            break;

                            case 'default':
                                $currentField->setValue($val);
                            break;

                            default:
                                $requestedMethod = 'set'.ucfirst($key);
                                if(method_exists($currentField, $requestedMethod))
                                {
                                    $currentField->$requestedMethod($val);
                                }
                            break;
                        }
                    }

                }
                else
                {
                    Debug::log("Le champ $field n'existe pas...");
                }
            }
        }
        return $this;
    }

    /**
     * Will search in the form files for a rules set
     * corresponding to the given $formName.
     * If the file exists, it apply the rules.
     * @return boolean true if found, false otherwise
     */
    public function searchForDefinedDatas($formName)
    {
        $return = false;
        $datas = Core::getFormDefinitionArray();
        if($data !== false)
        {
            if(array_key_exists($formName,$datas))
            {
                $datas = $datas[$formName];
                $this->loadDataFromArray($datas);
                $this->displayLabels = true;
                $return = true;
            }
            else
                $return = false;
        }

        return $return;
    }




    /**
     * Set to True for display field placeHolder
     * as the field label.
     */
    public function displayLabels($display = true)
    {
        $this->displayLabels = $display;
    }

    /**
     * Supprime les valeurs enregistrées dans le fomulaire.
     */
    public function clearFieldsValues()
    {
        foreach ($this->fields as $key => $field) {
            $field->setValue('');
        }
    }

    /**
     * Supprime tous les champs du formulaire
     */
    public function clearFields() {
        $this->fields = array();
    }

    public function getHtml($submitButton=true) {
        $s = $this->getFormOpenTag();
        $s.= $this->getFormFieldList();

        if($submitButton and isNull($this->customButtons)){
            $s.= $this->getDefaultSubmitButton();
        }
        else if (!isNull($this->customButtons)) {
            $s.= $this->getFormButtonsList();
        }

        $s .= $this->getFormCloseTag();
        return $s;
    }

    /**
     * Generate the html code for the openning of the 
     * form tag.
     * ex : [ <form action="#" method="post"> ]
     */
    public function getFormOpenTag()
    {
        $s = '<form action=' . dbQuote($this->findCustomActionTarget($this->action));
        $s .=  ' method=' . dbQuote($this->method) . ' ';
        $s .= parent::getHtmlAttributesList() .' ';
        if (!isNull($this->name)) {
            $s .= 'name=' . dbQuote($this->name).' ';
        }
        $s .= '>';
        return $s;
    }

    protected function findCustomActionTarget($target)
    {
        $acts = explode('/',$target);
        if(count($acts) > 1)
        {
            if(is_callable($acts[0].'::'.$acts[1])){
                $target = Core::opts()->system->siteroot.$target;
            }
        }
        return $target;
    }

    /**
     * Generate the html code for the closing of the 
     * form tag.
     * ex : [ </form> ]
     */
    public function getFormCloseTag()
    {
        return '</form>';
    }

    /**
     * Generate the html code of all the elements
     * contained in the form.
     */
    public function getFormFieldList()
    {
        $s = '';
        foreach ($this->fields as $field) {
            if($this->displayLabels)
            {
                $s .= $field->getLabel();
            }
            $s .= $field->getHtml();
        }
        return $s;
    }

    /**
     * Returns the HTML code of the form's button
     * list.
     */
    protected function getFormButtonsList()
    {
        $s = '';
        foreach ($this->customButtons as $button) {
            $s .= $button->getHtml();
        }
        return $s;
    }

    protected function getDefaultSubmitButton()
    {
        $s = '';
        $submit = new Button(
            'submit-'.$this->name, 
            "submit", 
            "", 
            $this->buttonLabel);
        $s .= $submit->getHtml();
        return $s;
    }

    public function getFields() {
        return $this->fields;
    }

    /* @TODO: vérifier que l'entrée est bien un tableau de champs (Field) */
    public function setFields($fields) {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Ajoute un bouton de formulaire personnalisé
     * qui va remplacer le bouton submit par défaut.
     * Il est possible d'ajouter plusieurs boutons,
     * par exemple un bouton submit, et un bouton reset.
     * 
     * Si un ou plusieurs boutons personalisés sont
     * définis, le formulaire de génerera pas de bouton
     * de soumission par défaut.
     * @param Input|Button $button le bouton a ajouter.
     */
    public function addCustomButton($button)
    {
        if(!is_a($button, 'Field'))
        {
            throw new AlertException(
                "Invalid button supplied for custom button", 1);
        }

        $this->customButtons[] = $button;

    }

    /**
     * Définit le tableaux de boutons fournis en parametre
     * comme étant les boutons de pied du formulaire.
     */
    public function setCustomButtonArray($buttons)
    {
        $this->customButtons[] = $buttons;
    }

    /**
     * Supprime tous les boutons personalisés 
     * définis pour ce formulaire.
     */
    public function clearCustomButtons()
    {
        $this->customButtons = array();
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

    public function getButtonLabel() {
        return $this->buttonLabel;
    }

    public function setButtonLabel($buttonLabel) {
        $this->buttonLabel = $buttonLabel;
        return $this;
    }

    public function __toString() {
        return $this->getHtml();
    }

}

?>
