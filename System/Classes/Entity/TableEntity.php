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
 * Classe permettant d'instancier une classe a partir
 * des valeurs d'une table spécifique
 */
class TableEntity extends Entity {

    /**
     * Crée une nouvelle instance référente à la table 
     * fournie en parametre. On effectue préalablement 
     * un scan des tables sur la base de données et on vérifie 
     * que la table demandée existe. 
     * Si ce n'est pas le cas, on jette une exception au visage 
     * de ce pauvre internaute.
     * 
     * @param string $name le nom de la table référente
     * @throws CriticalException si la table n'existe pas
     */
    public function __construct($name) {

        // Si la table existe dans la bdd
        if (Core::isValidClass($name . Core::getInstance()->getDbPrefix()))
        {

            parent::__construct();
            // On recharge
            $this->reload($name);
        }
        else {
            throw new CriticalException($name . ' n\'est pas une classe valide (non instanciable)');
        }
    }

}

?>
