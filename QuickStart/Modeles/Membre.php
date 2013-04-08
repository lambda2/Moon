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
class Membre extends MemberTemplate {
    
       
    public function __construct() {
        parent::__construct();
    }

    public function setupFields()
    {
    	$this->editField('pass')->setDefaultValue('Mot de passe');
    	$this->editField('pseudo')->setDefaultValue('Pseudo');
    	$this->editField('email')->setDefaultValue('Adresse e-mail');
    }

    public function validateUpdateForm($data)
    {
    	echo 'oui !';
    	var_dump($data);
    	var_dump($data['nom']);
    	var_dump($data['nom'] == 'Arragon');
    	if($data['nom'] == 'Arragon')
    	{
    		echo 'NON, ARRAGON !';
    		return false;
    	}
    	else
    		return true;
    }


    
}

?>
