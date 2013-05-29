<?php

/*
 * This file is part of the Moon Framework.
 * See more at the GitHub page :
 * - Of the Moon project @[ https://github.com/lambda2/Moon ]
 *
 * ----------------------------------------------------------------------------
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package 	Form
 * @subpackage	Filter
 * @category	Field Filtering
 * @copyright	Copyright (c) 2013, Lambdaweb
 * @author 		Andre Aubin <andre.aubin@lambdaweb.fr>
 * @since 		v1.0
 */
class Filter {

    protected $rules = array();
    protected $value;

    public function __construct($fieldValue, $rules)
    {
        $this->value = $fieldValue;
        $this->addRule($rules);
    }

    /**
	 * Add a new filtering rule for the specified field.
	 * The different rules can be separated by a '|' ,
	 * for example, if you want convert the value to a md5 sum: [ 'md5' ]
	 * @param string|array $rules the rules to apply
	 * @return boolean true if the rules is correct, false otherwise
	 * @throws Exception if the rule isn't valid and Core is on debug mode
	 *
	 */
	public function addRule($rules)
	{
		$valid = true;
        var_dump($rules);
        $ru = explode('|',$rules);
     
        foreach ($ru as $key => $value)
        {
            if(FilterFunctions::isFilterFunction($value))
            {
                $this->rules[] = trim(FilterFunctions::getCallableName($value));
            }
            else if(is_callable(trim($value)))
            {
                $this->rules[] = trim($value);
            }
            else
            {
                if(Core::getInstance()->isDebug())
                {
                    var_dump($value);
                    throw new Exception(
                        "Unrecognized filter : $value to apply on ".$this->value);
                }
                $valid = false;
            }
        }
		return $valid;
	}

    /**
     * will execute all the filters on the value,
     * and return the final value.
     */
    public function execute()
    {
        $value = $this->value;

        if(!isNull($this->rules) and !isNull($value))
        {
            foreach($this->rules as $rule)
            {
                if(FilterFunctions::isFilterFunction($rule)){
                    $value = forward_static_call_array(
						array('FilterFunctions',$rule),
						array($value));
                }
                else
                    $value = $rule($value);
            }
        }
        else if(Core::getInstance()->isDebug())
        {
            throw new Exception(
                    "No filters to apply on ".$this->value);
        }
        return $value;
    }
}


?>
