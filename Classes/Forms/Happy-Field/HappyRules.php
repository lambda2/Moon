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

include 'HappyFunctions.php';

use \Happy\HappyFunctions;


/**
 * Will define the rules to check for one (form) field.
 * @package 	Happy
 * @subpackage	Rules
 * @category	Field Check
 * @copyright	Copyright (c) 2013, Lambdaweb
 * @author 		Andre Aubin <andre.aubin@lambdaweb.fr>
 * @since 		v1.0
 * @link		http://lambda2.github.io/Happy-Field/
 */
class HappyRules {

	protected $field;
	protected $label;
	protected $rules = array();
	
	protected $fieldErrors = array();
	protected $debugErrors = array();

	protected $hf;

	protected static $func_ns = 'Happy\HappyFunctions';


	/**
	 * Will construct a new HappyRules
	 * @param string $fieldName the name of the field in the form
	 * @param string|array $rules the rules to check
	 * @param string $label the label of the field for the error message
	 * @param HappyField $hf the HappyField to refer to.
	 */
	public function __construct($fieldName, $rules, $label = '', &$hf=null)
	{
		$this->field = $fieldName;
		$this->rules = $this->parseRules($rules);

		$label == ''
		? $this->label = $fieldName
		: $this->label = $label;

		$this->hf = $hf;
	}



	/**
	 * Will parse the rules string for return
	 * an array of rules.
	 * The rules must be an array or a string
	 * where rules are separed by the | character.
	 * @param string/array $rules
	 * @throws Exception if $rules is not a string or an array
	 * @return An array containing the parsed rules
	 */
	protected function parseRules($rules)
	{
		if(is_string($rules))
			return self::cleanArray(explode('|', $rules));
		else if(is_array($rules))
			return self::cleanArray($rules);
		else if($rules == null)
			return array();
		else
			throw new Exception(
				"Invalid rules (type) submitted", 1);
		return false;
	}

	/**
	 * Clean the given array for the rules parsing.
	 */
	protected static function cleanArray($array)
	{
		$retArray = array();

		foreach ($array as $key => $value) {
			if(strlen($value) > 0 and $value != ' ')
			{
				$retArray[$key] = $value;
			}
		}
		$retArray = array_values($retArray);
		return $retArray;
	}

	/**
	 * @return boolean true if the method
	 * is a HappyRules method.
	 */
	public function isRuleMethod($func)
	{
		return method_exists(get_class($this),$func);
	}

	/**
	 * @return boolean true if the function
	 * is defined in HappyFunctions
	 */
	public function isHappyFunction($func)
	{
		return is_callable(self::$func_ns.'::'.$func);
	}

	/**
	 * Will check if each rule exists.
	 * @return true if all the rules exists, false otherwise.
	 */
	public function checkRulesExists()
	{
		$valid = True;
		$errors = array();

		foreach ($this->rules as $key => $rule) {

			$ruleArr = self::cleanArray(explode(' ',$rule));

			// echo 'class_exists('.self::$func_ns.') ? ';
			// var_dump(class_exists(self::$func_ns));
			// echo 'is_callable('.self::$func_ns.'::'.$ruleArr[0].') ? ';
			// var_dump(is_callable(self::$func_ns.'::'.$ruleArr[0]));

			if(
				count($ruleArr) > 0
				and !$this->isRuleMethod($ruleArr[0])
				and !$this->isHappyFunction($ruleArr[0])
				and !is_callable($ruleArr[0]))
			{
				$errors[$rule] = 'The rule ['.$rule.'] doesn\'t exists !';
				$valid = False;
			}
		}

		/**
		 * We add the incorrect rules to display them
		 * in a debug environment.
		 */
		if(count($errors))
			$this->debugErrors = $errors;

		return $valid;
	}

	/**
	 * Add the rule to the other rules.
	 * The rule can be a simple rule, an array
	 * of rules or a string where rules are
	 * separed by the | character.
	 * @param string|array the rule to add
	 * @return true if succes, false otherwise.
	 */
	public function addRule($rule)
	{
		$r = 0;
		foreach ($this->parseRules($rule) as $parsedRule) {
			$r = array_push($this->rules,$parsedRule);
		}
		return $r>0 ;
	}

	/**
	 * Clear all the rules
	 */
	public function clearRules()
	{
		$this->rules = array();
	}


	/**
	 * Will check each rule.
	 * @param array $rules the rules to check
	 * @return true if all the rules are checked, false otherwise.
	 */
	public function checkRules($testValue)
	{

		if($this->checkRulesExists())
		{
			$valid = True;
			$errors = array();

			foreach ($this->rules as $key => $rule) {

				$result = false;

				$ruleArr = explode(' ',$rule);
				$function_to_call = array_shift($ruleArr);
				array_unshift($ruleArr, $testValue);

				if($this->isHappyFunction($function_to_call))
				{
					$result = forward_static_call_array(
						array(self::$func_ns,$function_to_call),
						$ruleArr);
				}
				else if($this->isRuleMethod($function_to_call))
				{
					$result = call_user_func_array(
						array($this, $function_to_call),
						$ruleArr);
				}
				else if(is_callable($function_to_call))
				{
					$result = call_user_func_array($function_to_call,
						$ruleArr);
				}

				//$result = self::$func_ns.'::'.$function_to_call($ruleArr);
				if($result !== true)
				{
					$errors[$testValue] = $function_to_call;
					
					$valid = False;
				}

			}

			/**
			 * We add the unchecked rules to the error
			 * array
			 */
			if(count($errors) > 0)
				$this->fieldErrors = $errors;

			return $valid;
		}
		else
			return false;
	}

    /**
     * Gets the value of field.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Sets the value of field.
     *
     * @param mixed $field the field
     *
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Gets the value of label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the value of label.
     *
     * @param mixed $label the label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Gets the value of rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Sets the value of rules.
     *
     * @param mixed $rules the rules
     *
     * @return self
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Gets the value of fieldErrors.
     *
     * @return array
     */
    public function getFieldErrors()
    {
        return $this->fieldErrors;
    }

    /**
     * Gets the string value of fieldErrors.
     *
     * @return string
     */
    public function getStrFieldErrors()
    {
        return implode('\n - ',$this->fieldErrors);
    }

    /**
     * Sets the value of fieldErrors.
     *
     * @param mixed $fieldErrors the fieldErrors
     *
     * @return self
     */
    public function setFieldErrors($fieldErrors)
    {
        $this->fieldErrors = $fieldErrors;

        return $this;
    }

    /**
     * Gets the value of debugErrors.
     *
     * @return array
     */
    public function getDebugErrors()
    {
        return $this->debugErrors;
    }

    /**
     * Gets the string value of debugErrors.
     *
     * @return string
     */
    public function getStrDebugErrors()
    {
        return implode('\n - ',$this->debugErrors);
    }

    /**
     * Sets the value of debugErrors.
     *
     * @param mixed $debugErrors the debugErrors
     *
     * @return self
     */
    public function setDebugErrors($debugErrors)
    {
        $this->debugErrors = $debugErrors;

        return $this;
    }



/***********************************************************************
 *                        Multifields functions                        *
 ***********************************************************************/

	public function sameThat($expression, $field)
	{
		if($this->hf != null and array_key_exists($field, $this->hf->getFields()))
		{
			$f = $this->hf->getFields();
			return $f[$field] == $expression;
		}
		else
		{
			return false;
		}
	}
}

?>
