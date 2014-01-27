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

/**
 * Will define a lot of basic functions
 * for validating the forms
 * @package 	Happy
 * @subpackage	Functions
 * @category	Validation functions
 * @copyright	Copyright (c) 2013, Lambdaweb
 * @author 		Andre Aubin <andre.aubin@lambdaweb.fr>
 * @since 		v1.0
 * @link		http://lambda2.github.io/Happy-Field/
 */
class HappyFunctions {


/************************************************************************
 *                            Basic functions                           *
 ************************************************************************/

	/**
	 * @return boolean true if $expression is strictly greater than $value
	 */
	public static function sup($expression, $value)
	{
		if ( ! is_numeric($expression))
		{
			return false;
		}
		return intval($expression) > $value;
	}

	/**
	 * @return boolean true if $expression is strictly less than $value
	 */
	public static function inf($expression, $value)
	{
		if ( ! is_numeric($expression))
		{
			return false;
		}
		return intval($expression) < $value;
	}

	/**
	 * @return boolean true if $expression equals $value
	 */
	public static function equ($expression, $value)
	{
		return $expression == $value;
	}

	/**
	 * @return boolean false if $expression is empty
	 */
	public static function required($expression)
	{
		if ( ! is_array($expression))
			return (trim($expression) == '') ? false : true;
		else
			return ( ! empty($expression));
	}


	/**
	 * @return boolean false if $expression is shorter then the parameter value
	 */
	public static function minLength($expression, $val)
	{
		if (preg_match("/[^0-9]/", $val))
		{
			return false;
		}

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($expression) < $val) ? false : true;
		}

		return (strlen($expression) < $val) ? false : true;
	}


	/**
	 * @return boolean false if $expression is longer then the parameter value
	 */
	public static function maxLength($expression, $val)
	{
		if (preg_match("/[^0-9]/", $val))
		{
			return false;
		}

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($expression) > $val) ? false : true;
		}

		return (strlen($expression) > $val) ? false : true;
	}


	/**
	 * @return boolean false if $expression is not exactly the parameter value.
	 */
	public static function exactLength($expression, $val)
	{
		if (preg_match("/[^0-9]/", $val))
		{
			return false;
		}

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($expression) != $val) ? false : true;
		}

		return (strlen($expression) != $val) ? false : true;
	}

/***********************************************************************
 *                             Type functions                          *
 ***********************************************************************/

	/**
	 * @return boolean false if $expression contains anything other
	 * than alphabetical characters.
	 */
	public static function alpha($expression)
	{
		return ( ! preg_match("/^([a-z])+$/i", $expression)) ? false : true;
	}


	/**
	 * @return boolean false if $expression contains anything other
	 * than  alpha-numeric characters.
	 */
	public static function alphaNum($expression)
	{
		return ( ! preg_match("/^([a-z0-9])+$/i", $expression)) ? false : true;
	}


	/**
	 * @return boolean false if $expression contains anything other
	 * than  alpha-numeric characters with underscores and dashes.
	 */
	public static function alphaNumDash($expression)
	{
		return ( ! preg_match("/^([-a-z0-9_-])+$/i", $expression)) ? false : true;
	}


	/**
	 * @return boolean false if $expression contains anything other
	 * than numeric characters.
	 */
	public static function num($expression)
	{
		return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $expression);

	}


	/**
	 * @return boolean false if $expression contains anything other
	 * than numeric characters.
	 */
	public static function isNum($expression)
	{
		return ( ! is_numeric($expression)) ? false : true;
	}


	/**
	 * @return boolean false if $expression contains anything other
	 * than an integer.
	 */
	public static function integer($expression)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+$/', $expression);
	}


	/**
	 * @return boolean false if $expression contains anything other
	 * than an double|float.
	 */
	public static function decimal($expression)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $expression);
	}


	/**
	 * @return boolean false if $expression contains anything other
	 * than a Natural number (0,1,2,3, etc.)
	 */
	public static function natural($expression)
	{
		return (bool) preg_match( '/^[0-9]+$/', $expression);
	}

	/**
	 * @return boolean false if $expression contains anything other
	 * than a Natural number exepted zero (1,2,3, etc.)
	 */
	public static function naturalNotZero($expression)
	{
		if ( ! preg_match( '/^[0-9]+$/', $expression))
		{
			return false;
		}

		if ($expression == 0)
		{
			return false;
		}

		return true;
	}



/***********************************************************************
 *                            Regex functions                          *
 ***********************************************************************/


	/**
	 * @return boolean false if $expression does not match
	 * the one in parameter
	 */
	public static function regMatch($expression, $regex)
	{
		if ( ! preg_match($regex, $expression))
		{
			return false;
		}

		return  true;
	}

	/**
	 * @return boolean false if $expression is not a valid email adress
	 */
	public static function email($expression)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $expression)) ? false : true;
	}


	/**
	 * @return boolean false if any value provided in a comma separated list
	 * is not a valid email.
	 */
	public static function emails($expression)
	{
		if (strpos($expression, ',') === false)
		{
			return self::email(trim($expression));
		}

		foreach (explode(',', $expression) as $email)
		{
			if (trim($email) != ''
			and self::email(trim($email)) === false)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Valid Base64
	 *
	 * Tests a string for characters outside of the Base64 alphabet
	 * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
	 *
	 * @return boolean false if $expression is not a valid base64 string.
	 */
	public static function isBase64($expression)
	{
		return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $expression);
	}




}




?>