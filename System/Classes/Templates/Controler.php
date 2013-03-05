<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controler
 *
 * @author lambda2
 */
class Controler {
    
    public function __construct($request = '') {
        if($request == '')
            $this->index();
    }
    
    public function index(){
        
    }
}

?>
