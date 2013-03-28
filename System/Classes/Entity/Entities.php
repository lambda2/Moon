<?php

/**
 * Représente un ensemble d'entitées
 */
class Entities {
    protected $table;
    protected $bdd;
    
    public function __construct($table) {
        $this->table = $table;
        $this->bdd = Core::getBdd()->getDb();
    }
    
    public function getValuesList($columnName){
        $list = array();
        try {
            $Req = $this->bdd->prepare("SELECT {$columnName} FROM {$this->table}");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            MoonChecker::showHtmlReport($e);
        }
        while ($res = $Req->fetch(PDO::FETCH_NUM)) {
            $list[] = $res[0];
        }
        return $list;
    }
}

?>
