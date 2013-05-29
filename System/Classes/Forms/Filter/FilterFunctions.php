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
class FilterFunctions {

    private function __construct() {}

	/**
	 * @return boolean true if the function
	 * is defined in the FilterFunctions
	 */
	public static function isFilterFunction($func)
	{
		return is_callable('FilterFunctions::'.$func);
	}

    public static function getCallableName($func)
    {
        if(self::isFilterFunction($func))
        {
            return $func;
        }
        else
            return '';
    }

    /* --- Here comes the filter functions ---*/


    /**
     * Convert the text from Markdown syntax
     * to html.
     */
    public static function mkd($data) { return Markdown::defaultTransform($data); }

    public static function htmlescape($data)  { return htmlentities($data); }

    public static function upper($data) { return strtoupper($data); }

    public static function lower($data) { return strtolower($data); }

}


?>
