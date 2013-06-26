<?php

/*
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class OrmException extends Exception {
    public function __construct($message, $code=0, $previous=null) {
        parent::__construct('Erreur ORM : '.$message, $code, $previous);
        dbg('Erreur ORM : '.$message, 1);
    }
}


?>
