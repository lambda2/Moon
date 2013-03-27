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
 * Effectue des tests élémentaire pour s'assurer que toute la structure 
 * du framework est correctement configurée.
 * @package Debug
 * @author lambda2
 */

class MoonChecker extends Core {
	
	use CheckTemplate;

	public static $lastException = null;


	/**
	 * Verifie que tous les aspects du systeme sont corrects.
	 * @return boolean True si tout est bon, lance une exception sinon.
	 */
	public static function checkSystem()
	{
		foreach ( get_class_methods('MoonChecker') as $key => $method) 
		{
			if(stristr($method, 'check_') != false)
			{
				$m = explode('_', $method);
				array_shift($m);
				if(call_user_func('MoonChecker::'.$method) === false)
				{
					throw new CoreException(
						"Problem when checking "
						.implode(' ', $m), 5);
					return false;
				}
			}
		}
		return true;
	}

	public static function showHtmlReport($e) 
    {
    	self::$lastException = $e;
    	
    	$mode = 'DEBUG';
    	if(MoonChecker::check_Generate_Config_Vars()){
    		$mode = self::$options->system->mode;
    	}

    	if($mode === 'DEBUG'){
    		include_once(__DIR__.'/HtmlPages/moonanalyze.php');
    	}
    	else
    	{
    		include_once(__DIR__.'/HtmlPages/unavailable.php');
    	}

    	die();
    }

    private static function check_User_Config_File() {
        if(file_exists(self::userConfigFile))
            return true;
        else
            return false;
    }

    private static function check_Default_Config_File() {
        if(file_exists(self::defaultConfigFile))
            return true;
        else
            return false;
    }

    private static function check_Generate_Config_Vars() {
    	if(
    		MoonChecker::check_User_Config_File()
    		&& MoonChecker::check_Default_Config_File()
    		){
	    	try 
	    	{
	    		self::loadOptions();
	            self::loadRoutes();
	    	} 
	    	catch (Exception $e) 
	    	{
	    		return false;
	    	}
	    	return true;
	    }
	    else
	    	return false;
    }

    private static function check_Database_Connexion() {
    	if(MoonChecker::check_User_Config_File()){
    			return Orm::checkConnexion(self::$options->database->childs());
    	}
    	else
    		return false;
    }

    private static function check_Engine_Start() {
    	try 
    	{
    		self::$instance = new Core(
                    self::$options->system->mode, 
                    self::$options->database->db_prefix);
    	} 
    	catch (Exception $e) 
    	{
    		return false;
    	}
    	return true;
    }


    private static function check_Table_Scheme_Generation() {
    	try {
    		self::$host_tables = OrmFactory::getOrm()->getAllTables();
    	}
    	catch (Exception $e) {
			return false;
		}
		return true;
    }

    



}


?>