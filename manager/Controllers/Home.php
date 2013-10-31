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
 * Description of Home
 *
 * @author lambda2
 */
class Home extends Controller {
    
    public function index($params=array())
    {
        $this->registerParams($params);
        $this->render();
    }
}

?>