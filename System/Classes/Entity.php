<?php

abstract class Entity {

    protected $table;
    protected $editable;
    protected $bdd;
    protected $fields;
    protected $access;

    public function __construct() {

        $this->table = strtolower(get_class($this)) . Configuration::getInstance()->getDbPrefix();
        $this->editable = new Editable($this, $this->table);
        $this->bdd = Configuration::getInstance()->bdd();
        $this->access = new Access();

        $this->generateProperties();
        $this->generateFields();
    }

    protected function reload() {

        $this->editable = new Editable($this, $this->table);
        $this->bdd = Configuration::getInstance()->bdd();
        $this->access = new Access();

        $this->generateProperties();
        $this->generateFields();
    }

    public function __call($methodName, $args) {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!isset($this->fields[$property])) {
                throw new MemberAccessException('Property ' . $property . ' not exists');
            }
            switch ($matches[1]) {
                case 'set':
                    $this->checkArguments($args, 1, 1, $methodName);
                    return $this->set($property, $args[0]);
                case 'get':
                    $this->checkArguments($args, 0, 0, $methodName);
                    return $this->get($property);
                case 'default':
                    throw new MemberAccessException('Method ' . $methodName . ' not exists');
            }
        }
    }

    protected function generateProperties() {
        $t = array();
        try {
            $Req = $this->bdd->prepare("SHOW COLUMNS FROM {$this->table}");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $t[$res->Field] = null;
        }
        $this->fields = $t;
    }

    /**
     * Génère les champs de formulaire 
     * depuis le shema de la base de données.
     */
    protected function generateFields() {
        $this->editable = new Editable($this, $this->table);
        try {
            $Req = $this->bdd->prepare("SHOW COLUMNS FROM {$this->table}");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $type = "text";
            if ($res->Key == "MUL") {
                $type = "select";
                $target = str_replace('id_', '', $res->Field) . Configuration::getInstance()->getDbPrefix() . '.' . $res->Field;
                $this->editable->add(new Champ($res->Field, $res->Field, ucfirst(str_replace('id_', '', $res->Field)), $type, $target, ''));
            } elseif ($res->Key == "") {
                $t = explode('_', $res->Field);
                $t = $t[0];
                $this->editable->add(new Champ($res->Field, $res->Field, ucfirst($t)));
            }
        }
    }

    protected function getIdFieldFrom($tableName) {
        
    }

    public function getAll() {
        $t = array();
        try {
            $Req = $this->bdd->prepare("SELECT * FROM {$this->table}");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $t[] = $res;
        }

        return $t;
    }

    public function get($property) {
        return $this->fields[$property];
    }

    public function set($property, $value) {
        $this->fields[$property] = $value;
        return $this;
    }

    public function __toString() {
        $s = 'Classe ' . get_class($this) . ' :<br>';
        $s .= 'Référencée par la table ' . $this->table . '.<br>';
        $i = 0;
        foreach ($this as $key => $value) {
            if (!is_a($value, "PDO"))
                $s .= "$key : $value ($i)<br>";
            $i++;
        }

        return $s;
    }

    protected function checkArguments(array $args, $min, $max, $methodName) {
        $argc = count($args);
        if ($argc < $min || $argc > $max) {
            throw new MemberAccessException('Method ' . $methodName . ' needs minimaly ' . $min . ' and maximaly ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function getEditable() {
        return $this->editable;
    }

    public function setEditable($editable) {
        $this->editable = $editable;
    }

    public function getBdd() {
        return $this->bdd;
    }

    public function setBdd($bdd) {
        $this->bdd = $bdd;
    }

    public function getFields() {
        return $this->fields;
    }

    public function setFields($fields) {
        $this->fields = $fields;
    }

}

/**
 * Classe permettant d'instancier une classe a partir
 * des valeurs d'une table spécifique
 */
class TableEntity extends Entity {

    /**
     * Crée une nouvelle instance référente à la table 
     * fournie en parametre. On effectue préalablement 
     * un scan des tables sur la base de données et on vérifie 
     * que la table demandée existe. 
     * Si ce n'est pas le cas, on jette une exception au visage 
     * de ce pauvre internaute.
     * 
     * @param string $name le nom de la table référente
     * @throws CriticalException si la table n'existe pas
     */
    public function __construct($name) {

        // Si la table existe dans la bdd
        if (!Configuration::isValidClass($name)) {
            // Splaff !
            throw new CriticalException($name . ' n\'est pas une classe valide (non instanciable)');
        } else {
            parent::__construct();
            // On redéfinit la table référente de l'instance
            $this->table = ($name) . Configuration::getInstance()->getDbPrefix();
            // Et on recharge
            $this->reload();
        }
    }

}

class MemberAccessException extends Exception {
    
}

?>