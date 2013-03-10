<?php

/*
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class CriticalException extends Exception {
    public function __construct($message, $code=0, $previous=null) {
        parent::__construct($message, $code, $previous);
        if(Core::isStarted())
            dbg ($message, 1);
        else
            echo $this->getMessage();
    }
}

?>
