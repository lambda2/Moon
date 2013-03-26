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
	
	public static function analyze()
	{
		$results = self::getReportArray();
		$output = '<table>';
		foreach ($results as $meth => $res) {
			$output .= '<tr>';
			if($res == false){
				$output .= '<td style="color: red;">✖</td>';
			}
			else if($res === true){
				$output .= '<td style="color: green;">✔</td>';
			}
			else {
				$output .= '<td style="color: gray;">❓</td>';
			}
			$output .= '<td style="color: lightgray;"> ----- </td>';
			$output .= '<td>'.$meth.'</td>';
			$output .= '</tr>';
		}
		$output .= '</table>';
		return $output;
	}

	public static function getReportArray()
	{
		$interrupted = false;
		$results = array();
		foreach ( get_class_methods('MoonChecker') as $key => $method) {
			if(stristr($method, 'check_') != false){
				$m = explode('_', $method);
				array_shift($m);
				if(!$interrupted)
					$r = call_user_func('MoonChecker::'.$method);
				else
					$r = 'o';
				$results[implode(' ', $m)] = $r;
				if(!$r)
					$interrupted = true;
			}
		}
		return $results;
	}

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

	public static function rtimeAnalyse() 
	{
		$results = array();
		foreach ( get_class_methods('MoonChecker') as $key => $method) {
			if(stristr($method, 'check_') != false){
				$m = explode('_', $method);
				array_shift($m);
				$results[implode(' ', $m)] = 
					call_user_func('MoonChecker::'.$method);
			}
		}
		var_dump($results);
		echo '<table>';
		foreach ($results as $meth => $res) {
			echo  '<tr>';
			echo  '<td>'.$meth.'</td>';
			if($res == false){
				echo  '<td style="color: red;">x</td>';
			}
			else
				echo  '<td style="color: green;">✔</td>';
			echo  '</tr>';
		}
		echo  '</table>';
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

    public static function generateHtmlReport($e) 
    {
    	$mode = 'DEBUG';
    	if(MoonChecker::check_Generate_Config_Vars()){
    		$mode = self::$options->system->mode;
    	}

    	if($mode === 'DEBUG'){

    	}
    	else 
    	{
    		include_once(__DIR__.'/htmlPages/unavailable.html');
    	}

    	die();
    }



}


?>