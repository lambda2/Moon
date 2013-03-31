<?php


class Astre extends Entity
{

	public function __construct() {
		echo '<!-- constructeur de Astre ! -->';
		parent::__construct();
	}


    protected function setupFields(){
		
		/*$this->editField('type')->setRequired(true);
        $this->editField('systeme')->setRequired(true);
        $this->getSysteme()->setLabelColumn('nom');*/ 
    }
}

?>