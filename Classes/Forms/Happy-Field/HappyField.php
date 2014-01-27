<?php

/*
 * This file is part of HappyField, a field parser for the Moon Framework.
 * See more at the GitHub page :
 * - Of this project @[ https://github.com/lambda2/Happy-Field ]
 * - Of the Moon project @[ https://github.com/lambda2/Moon ]
 *
 * ----------------------------------------------------------------------------
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Happy;

include 'HappyRules.php';

use \Happy\HappyRules;

/**
 *
 * @package 	Happy
 * @subpackage	Rules
 * @category	Field Check
 * @copyright	Copyright (c) 2013, Lambdaweb
 * @author 		Andre Aubin <andre.aubin@lambdaweb.fr>
 * @since 		v1.0
 * @link		http://lambda2.github.io/Happy-Field/
 */
class HappyField {

    /**
     * @var $rules will contain all the rules
     */
    protected $rules = array();

    /**
     * @var $fields will contain all the fields to validate.
     */
    protected $fields = array();

    protected $showErrors = true;

    /**
     * @var $errorReport will contain all the errors of
     * the previous check
     */
    protected $errorReport = array();

    /**
     * Will construct a new HappyField
     */
    public function __construct()
    {

    }

    /**
     * Add a new rule for the specified field.
     * The different rules can be separated by a '|' ,
     * for example, if you want check than a value is greater
     * than 0 and lesser than 10, the rules are:
     * [ 'sup 0 | inf 10' ] OR [ array('sup 0','inf 10') ]
     *
     * @param string $fieldName the name of the field in the form
     * @param string|array $rules the rules to check
     * @param string $label the label of the field for the error message
     * @return boolean true if the rules is correct, false otherwise
     * @throws Exception if the rule isn't valid and [showErrors] is true
     *
     * @see HappyField::showErrors
     */
    public function addRule($fieldName, $rules, $label = '')
    {

	$valid = false;
	/*
	 * If the rule exists, we just add new rules to the
	 * existing one.
	 */
	if(array_key_exists($fieldName, $this->rules))
	{
	    $this->rules[$fieldName]->addRule($rules);
	    if($label != '')
	    {
		$this->rules[$fieldName]->setLabel($label);
	    }
	    $valid = true;
	}
	else
	{
	    /*
	     * We create the new rule and we check if the
	     * specified rule(s) are valid.
	     */
	    $hr = new HappyRules($fieldName, $rules, $label,$this);
	    $valid = $hr->checkRulesExists();

	    if(!$valid and $this->showErrors)
	    {
		throw new \Exception($hr->getStrDebugErrors(), 1);
	    }
	    else if($valid)
	    {
		$this->rules[$fieldName] = $hr;
	    }
	}

	return $valid;
    }

    /**
     * Will load all the rules contained in the
     * given array.
     * Example structure :
     *  ---------------------------------------------
     * |
     * | array(
     * |     'nom' =>
     * |         array(
     * |             'label' => 'family name',
     * |             'rules' => 'minLenght 3|maxLength 14|alpha'
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
     * @return HappyField self
     */
    public function loadRulesFromArray($array)
    {
	foreach ($array as $field => $rule) {

	    $label = '';
	    $rules = '';

	    if(array_key_exists('label', $rule))
	    {
		$label = $rule['label'];
	    }

	    if(array_key_exists('rules', $rule))
	    {
		$rules = $rule['rules'];
	    }

	    $this->addRule($field, $rules, $label);
	}

	return $this;
    }

    /**
     * @TODO : SERIOUSLY NEED TO HANDLE NICE ERROR MESSAGES !
     * return the rules who caused an error in the validation
     * process...
     */
    public function getRulesErrors()
    {
	return $this->errorReport;
    }

    /**
     * Will set the fields to be validated.
     *
     * @param array $fields an array with the fields names as
     * keys, and the fields values as values. You can
     * directly pass the $_POST or $_GET array.
     *
     * @throws Exception if the fields aren't valid and [showErrors] is true
     * @return boolean true if success, false otherwise.
     */
    public function setFields($fields)
    {
	$valid = is_array($fields) and count($fields);
	if($valid)
	{
	    $this->fields = $fields;
	}
	else if($this->showErrors)
	{
	    throw new Exception("Unable to set the fields : ".$fields, 1);
	}

	return $valid;
    }

    /**
     * Will launch the check of each added rule
     * according to the values of the supplied fields.
     *
     * @return boolean true if success, false otherwise.
     */
    public function check()
    {
	// If we have no fields or no rules, run away !
	/*
	   if(count($this->fields) > 0 && count($this->rules) == 0)
	   return false;
	 */

	$success = True;

	foreach ($this->rules as $rule) {

	    /*		// if we have a rule without his field, the validation fail.
			if(!array_key_exists($rule->getField(), $this->fields))
			{
			$success = False;
			echo "<p>key do not exist : ".$rule->getField()." in fields [ ".arr2str($this->fields)." ] </p>";
			}
			else
			{*/
	    $result = true;
	    if(array_key_exists($rule->getField(),$this->fields))
	    {
		$result = $rule->checkRules($this->fields[$rule->getField()]);
	    }

	    if(!$result)
	    {
		$this->errorReport[$rule->getField()] = $rule->getFieldErrors();
		$success = false;
	    }
	}
	if($success == False)
	    echo '<p>Resultat faux !</p>';

	return $success;
    }

    /**
     * Sets the value of showErrors.
     *
     * @param mixed $showErrors the showErrors
     *
     * @return self
     */
    public function showErrors($showErrors)
    {
	$this->showErrors = $showErrors;

	return $this;
    }


    /**
     * Gets the
     * @var $fields will contain all the fields to validate..
     *
     * @return mixed
     */
    public function getFields()
    {
	return $this->fields;
    }

    /**
     * Return the rule corresponding to the
     * specified field.
     * @param string the name of the field
     * @return HappyRules the rule for the specified field.
     */
    public function getRule($fieldName)
    {
	if(array_key_exists($fieldName, $this->rules))
	    return $this->rules[$fieldName];
	else
	    throw new \Exception("Key $fieldName not found in the rules !", 1);

	//return false;
    }

    /**
     * Return all the rules
     * @return array all the saved rules.
     */
    public function getRules()
    {
	return $this->rules;
    }

    /**
     * Clear all the previously saved rules.
     * @return self
     */
    public function clearRules()
    {
	$this->rules = array();
	return $this;
    }

}


?>
