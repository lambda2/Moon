<?php

class PathFinder {

    private static $res = array();
    private static $cache = true;

    public static function enableCache()
    {
        self::$cache = true;
    }

    public static function disableCache()
    {
        self::$cache = false;
    }

    public static function setCache($bool)
    {
        self::$cache = $bool;
    }

    public static function getClasses($dir) {

        if(self::$cache and apc_exists('get_classes_'.$dir))
        {
            return apc_fetch('get_classes_'.$dir);
        }

        self::$res = array();
        self::listDir($dir. DIRECTORY_SEPARATOR .'Classes');
        if(self::$cache) apc_store('get_classes_'.$dir,self::$res);
        return self::$res;
    }

    public static function getHelpers($dir) {

        if(self::$cache and apc_exists('get_helpers_'.$dir))
        {
            return apc_fetch('get_helpers_'.$dir);
        }

        self::$res = array();
        self::listDir($dir. DIRECTORY_SEPARATOR .'Helpers');
        if(self::$cache) apc_store('get_helpers_'.$dir,self::$res);
        return self::$res;
    }

    public static function getControllers($dir) {

        if(self::$cache and apc_exists('get_controllers_'.$dir))
        {
            return apc_fetch('get_controllers_'.$dir);
        }

        self::$res = array();
        self::listDir($dir. DIRECTORY_SEPARATOR .'Controllers');
        if(self::$cache) apc_store('get_controllers_'.$dir,self::$res);
        return self::$res;
    }

    public static function getModeles($dir) {

        if(self::$cache and apc_exists('get_models_'.$dir))
        {
            return apc_fetch('get_models_'.$dir);
        }

        self::$res = array();
        self::listDir($dir. DIRECTORY_SEPARATOR .'Modeles');
        if(self::$cache) apc_store('get_models_'.$dir,self::$res);
        return self::$res;
    }

    public static function getLibs($dir) {

        if(self::$cache and apc_exists('get_libs_'.$dir))
        {
            return apc_fetch('get_libs_'.$dir);
        }

        self::$res = array();
        self::listDir($dir. DIRECTORY_SEPARATOR .'Libs');
        if(self::$cache) apc_store('get_libs_'.$dir,self::$res);
        return self::$res;
    }


    private static function removeExt($string, $ext = '.php') {
        return substr($string, 0, strpos($string, $ext));
    }

    private static function listDir($dir) {
        if ($dossier = opendir($dir)) {
            while (false !== ($fichier = readdir($dossier))) {
                if ($fichier != '.' && $fichier != '..') {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $fichier)) {
                        $list = self::listDir($dir . DIRECTORY_SEPARATOR . $fichier);
                        if (is_array($list)) {
                            foreach ($list as $classe => $url) {
                                self::$res[self::removeExt($classe)] = $url;
                            }
                        }
                    }
                    else {
                        if (strpos($fichier, '.php') != false and substr($fichier,-4,4) == '.php')
                            self::$res[self::removeExt($fichier)] = $dir . DIRECTORY_SEPARATOR . $fichier;
                    }
                }
            }
            closedir($dossier);
        }
    }

}

?>
