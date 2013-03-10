<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class CoreException extends Exception {
    public function __construct($message, $code=0, $previous=null) {
        parent::__construct($message, $code, $previous);
        die('Erreur : Le système n\'a pas été correctement initialisé ('.$message.')');
    }
}


?>
