<?php


class Contrat extends Entity{
    
    protected $id_client_contrat;
    protected $prix_contrat;
    protected $etat_contrat;
    protected $date_contrat;
    protected $description_contrat;
    protected $nom_contrat;
    protected $id_contrat;
    
    public function __construct() {
        parent::__construct();
        $this->editable->setTableName("contrat_lw");
        $this->editable->add(new Champ("nom_contrat","nom_contrat", "Nom du contrat"));
        $this->editable->add(new Champ("description_contrat","description_contrat", "Description"));
        $this->editable->add(new Champ("prix_contrat","prix_contrat", "Prix"));
        $this->editable->add(new Champ("date_contrat", "date_contrat", "Date du contrat", "date"));
        $this->editable->add(new Champ("etat_contrat", "etat_contrat","Etat","select","etat_contrat_lw.id_etat_contrat","nom_etat_contrat"));
    }


    protected function majField($field, $value){
    }

    protected function chargerBdd() {
        
    }
}

?>
