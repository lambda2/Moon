<?php

/*
 * This file is part of the Moon Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * La factory pour créer notre Orm.
 * En fonction du driver spécifié dans le fichier de configuration,
 * cette classe va renvoyer un orm adapté.
 * @author lambda2
 */
abstract class OrmFactory {
    
    /**
     * Retourne l'ORM adapté au driver spécifié dans la configuration.
     */
    public static function getOrm(){
        $driver = Core::opts()->database->driver;
        $instance = null;
        
        switch ($driver) {
            case 'mysql':
                echo 'Nouveau ORM MySql !';
                $instance = new OrmMysql('mysql');
                break;

            default:
                echo 'Nouveau ORM #ONNESAITPAS !';
                break;
        }
        
        return $instance;
    }
    
}

?>
