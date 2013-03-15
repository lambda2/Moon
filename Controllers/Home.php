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
    
    public function index(){
        $this->render();
        
        /*$time = array();
        $time['startOne'] = microtime(true);
        $astres = Moon::getAll('astre');
        $time['stopOne'] = microtime(true);
        $time['countOne'] = $time['stopOne'] - $time['startOne'];
        
        $time['startTwo'] = microtime(true);
        
        $time['stopTwo'] = microtime(true);
        $time['countTwo'] = $time['stopTwo'] - $time['startTwo'];
        $time['mult'] = round($time['countTwo'] - $time['countOne'], 4);*/
    }
    
    public function compterLesAstres(){
        $astres = Moon::getAllHeavy('astre');
        $this->addData('astres', $astres);
        $this->render();
    }
    
    
}

?>
