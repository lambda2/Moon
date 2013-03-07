<?php

class PathFinder {

    private static $res = array();

    public static function getClasses($dir) {
        self::$res = array();
        self::listDir($dir. DIRECTORY_SEPARATOR .'Classes');
            return self::$res;
    }

    public static function getHelpers($dir) {
        self::$res = array();
        self::listDir($dir. DIRECTORY_SEPARATOR .'Helpers');
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
                        if (strpos($fichier, '.php') != false)
                            self::$res[self::removeExt($fichier)] = $dir . DIRECTORY_SEPARATOR . $fichier;
                    }
                }
            }
            closedir($dossier);
        }
    }

}

?>