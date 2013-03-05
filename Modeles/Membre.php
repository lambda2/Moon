<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Membre
 *
 * @author lambda2
 */
class Membre extends MemberTemplate{
    
        public function __construct() {
        parent::__construct();
        
        $this->loadBy('id_membre', 1);
        $this->autoLoadLinkedClasses();
    }
    
}

?>
