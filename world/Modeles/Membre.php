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
    	$this->editField('passwd')->setPlaceHolder('Mot de passe');
    	$this->editField('pseudo')->setPlaceHolder('Pseudo');
    	//$this->editField('email')->setPlaceHolder('Adresse e-mail');
        //$this->getDept_membre()->setLabelColumn('nom');
    }

    /**
     * Charge le membre défini dans la session.
     * En d'autres termes, charge l'utilisateur connecté.
     */
    public static function loadFromSession()
    {
	if(!isset($_SESSION['login']))
	{
		return false;
	}
	else
	{
		$membre = Moon::get('Membre','pseudo',$_SESSION['login']);
		return $membre;
	}
    }

    public function validateUpdateForm($data)
    {
    	return true;
    }

    /**
     * Surcharge de la fonction de callback qui est apellée
     * a la fin de chaque mise à jour (réussie) d'un Membre.
     * Ici, on met à jour la session et l'utilisateur dans le Core
     * pour ne pas avoir a se déconnecter / reconnecter.
     */
    protected function updateCallback($data)
    {
	if(isset($data['pseudo']))
	{
		Core::setUser($data['pseudo']);
		$_SESSION['login'] = $data['pseudo'];
		echo 'user modifié !';
		return true;
	}
	return false;
    }


}

?>
