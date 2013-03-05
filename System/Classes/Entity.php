<?php

abstract class Entity {

    protected $table;
    protected $editable;
    protected $bdd;
    protected $fields;
    protected $linkedClasses;
    protected $access;

    public function __construct() {


        $this->table = strtolower(get_class($this)) . Configuration::getInstance()->getDbPrefix();
        $this->editable = new Editable($this, $this->table);
        $this->bdd = Configuration::getInstance()->bdd();
        $this->access = new Access();
        $this->access->loadFromTable(strtolower(get_class($this)));
        $this->clearLinkedClasses();

        if (get_class($this) != 'TableEntity') {
            $this->generateProperties();
            $this->generateFields();
        }
    }

    protected function reload($table) {
        $this->table = strtolower($table) . Configuration::getInstance()->getDbPrefix();
        $this->editable = new Editable($this, $this->table);
        $this->bdd = Configuration::getInstance()->bdd();
        $this->access = new Access();
        $this->access->loadFromTable(strtolower(get_class($this)));

        $this->generateProperties();
        $this->generateFields();
        //$this->clearLinkedClasses();
        //$this->autoLoadLinkedClasses();
    }

    /**
     * Va tenter d'instancier les classes liées a l'instance courante.
     */
    public function autoLoadLinkedClasses() {
        $this->clearLinkedClasses();
        foreach ($this->fields as $key => $value) {

            // Si le champ n'a pas de valeur, il ne peux y avoir de classe associée...
            if ($value != null) {
                $s = $this->getRelationClassInstance($key);
                /*if ($s != null)
                    dg($s->getTable() . ' pour ' . $key . '(' . $value . ')');
                else
                    dg('aucune relation trouvée pour ' . $key . '(' . $value . ')');*/
                $className = split('\.',$this->getNameWithoutId($key))[0];
                if ($s != null && strcmp($className . Configuration::getInstance()->getDbPrefix(), $this->table) != 0) {
                    $this->addLinkedClass($s, $this->getForeignLink($key));
                }
            }
        }
        //var_dump($this->linkedClasses);
    }

    public function __call($methodName, $args) {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!isset($this->fields[$property])) {
                if (!isset($this->fields[$property . '_' . $this->getProperName($this->table)])) {
                    if (!isset($this->fields[$property . '_' . $this->getProperName($this->table, false, true)])) {
                        if ($this->checkExternalRelation($property)) {
                            return $this->linkedClasses[$property];
                        } else {
                            throw new MemberAccessException('Property ' . $property . ' not exists');
                        }
                    } else {
                        $property = $property . '_' . $this->getProperName($this->table, false, true);
                    }
                } else {
                    $property = $property . '_' . $this->getProperName($this->table);
                }
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

    public function loadBy($field, $value) {
        try {
            $Req = $this->bdd->prepare("SELECT * FROM {$this->table} WHERE {$field} = '{$value}'");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        if ($Req->rowCount() != 0) {
            while ($res = $Req->fetch(PDO::FETCH_ASSOC)) {
                if (count($this->fields) >= count($res)) {
                    $this->fields = $res;
                } else {
                    throw new ErrorException('Les champs récupérés ne correspondent pas !');
                }
            }
            return true;
        } else if (strripos($field, '_' . $this->table) == false) {
            return $this->loadBy($field . '_' . $this->table, $value);
        } else {
            //throw new ErrorException("L'objet {$this->table} dont le champ $field est égal à $value n'a pas été trouvé...");
            return false;
        }
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
                $target = $this->getForeignLink($res->Field);
                $this->editable->add(new Champ($res->Field, $res->Field, ucfirst(str_replace('id_', '', $res->Field)), $type, $target, ''));
            } elseif ($res->Key == "") {
                $t = explode('_', $res->Field);
                $t = $t[0];
                $this->editable->add(new Champ($res->Field, $res->Field, ucfirst($t)));
            }
        }
    }

    /**
     * Essaie de retourner le champ visé par la relation étrangère fournie en paramètre
     * @param String $rowName le nom du champ (ex : 'id_client_contrat' )
     * @return String la table et le nom de la colonne ciblée (ex : 'client.id_client')
     */
    protected function getForeignLink($rowName) {
        $foreignTable = "";
        $foreignRow = "";
        $split = split('_', $rowName);
        if (count($split) > 2) {
            $foreignTable = join('_', array_slice($split, 1, -1)) . Configuration::getInstance()->getDbPrefix();
            $foreignRow = join('_', array_slice($split, 0, -1));
        }
        return $foreignTable . '.' . $foreignRow;
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
        $s = '<div class="row"><h4>Classe ' . get_class($this) . ' :</h4>';
        $s .= '<h5>Référencée par la table ' . $this->table . '.</h5>';
        foreach ($this as $key => $value) {
            if (!is_a($value, "PDO")){
                if(is_array($value)){
                    $s .= "<h5>$key : </h5>";
                    $s .= '<table class="table">';
                    
                    foreach ($value as $k => $v) {
                        if($key == "linkedClasses"){
                            $s .= '<tr><td></td><td>'.$k.'</td><td> ==> </td><td>'.get_class($v).'::'.$v->getTable().'()</td></tr>';
                        }
                        else
                            $s .= '<tr><td></td><td>'.$k.'</td><td> ==> </td><td>'.$v.'</td></tr>';
                    }
                    if(count($value) == 0)
                        $s .= '<tr><td></td><td>Aucune valeur enregistrée</td><td></td><td></td></tr>';
                    $s .= "</table>";
                }
                else
                    $s .= "<h5>$key : </h5><pre>$value </pre>";
            }
        }
        $s .= '</div>';
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

    public function getLinkedClasses() {
        return $this->linkedClasses;
    }

    protected function setLinkedClasses($linkedClasses) {
        if (is_array($linkedClasses))
            $this->linkedClasses = $linkedClasses;
        else
            $this->linkedClasses[] = $linkedClasses;
    }

    protected function addLinkedClass($class, $name) {

        $this->linkedClasses[$name] = $class;
    }

    protected function checkExternalRelation($query) {
        $f = false;
        if (array_key_exists($query, $this->linkedClasses)) {
            if ($this->linkedClasses[$query] != null)
                $f = true;
        }
        return $f;
    }

    public function addRelation($field, $target, $display = null) {
        if ($display == null)
            $display = $field;
        $t = explode(".", $target);
        if (count($t) < 2)
            throw new Exception('cible invalide pour le bind  ' . $target);
        else {
            $this->linkedClasses[$display] = $this->getRelationClassInstance($field, $target);
        }
    }

    public function getAccess() {
        return $this->access;
    }

    public function setAccess($access) {
        $this->access = $access;
    }

    public function clearLinkedClasses() {
        $this->linkedClasses = array();
    }

    public function getFields() {
        return $this->fields;
    }

    public function setFields($fields) {
        $this->fields = $fields;
    }

    public function hasAnId($field) {
        if (strcmp(strtolower(substr($field, 0, 2)), 'id') == 0)
            return true;
        else
            return false;
    }

    public function getNameWithoutId($field) {
        return $this->getForeignLink($f);
    }

    public function getRelationClassName($field) {
        //var_dump(split('\.',$this->getForeignLink($field)));
        $cl = split('\.',$this->getForeignLink($field));
        $cl = $cl[0];
        if (!Configuration::isValidClass($cl)) {
            $cl = null;
        }
        return $cl;
    }

    public function getRelationClassInstance($field, $target = null) {
        
        // Si on n'a pas spécifié de relation, on la cherche a la main
        if ($target == null) {
            $className = $this->getRelationClassName($field);
            if ($className == null || $className == $this->table) {
                return null;
            } else {
                try {
                    $inst = EntityLoader::loadInstance($this->getForeignLink($field), $this->fields[$field]);
                    if (strcmp($inst->getTable(), $this->getTable()) == 0) {
                        return null;
                    }
                    return $inst;
                } catch (Exception $e) {
                    echo $e;
                }
            }
            
        // Sinon on la charge directement
        } else {
            try {
                $inst = EntityLoader::loadInstance($target, $this->fields[$field]);
                if (strcmp($inst->getTable(), $this->getTable()) == 0) {
                    return null;
                }
                return $inst;
            } catch (Exception $e) {
                echo $e;
            }
        }
    }

    public static function getProperName($name, $upper = false, $singularize = false) {
        $reducClassName = $name;
        if (strstr($reducClassName, Configuration::getInstance()->getDbPrefix()) != FALSE) {
            $reducClassName = substr_replace($reducClassName, '', -(strlen(Configuration::getInstance()->getDbPrefix())), strlen(Configuration::getInstance()->getDbPrefix()));
        }
        if ($upper) {
            $reducClassName = ucfirst($reducClassName);
        } else {
            $reducClassName = strtolower($reducClassName);
        }
        $reducClassName = str_replace('_', ' ', $reducClassName);

        if ($singularize) {
            $reducClassName = singularize($reducClassName);
        }
        return $reducClassName;
    }

}

class EntityLoader {

    public static function getClass($className) {
        $return = null;
        try {
            if (class_exists($className) && in_array('Entity', class_parents($className))) {
                $return = new $className();
            } else {
                $reducClassName = $className;
                if (strstr($reducClassName, Configuration::getInstance()->getDbPrefix()) != FALSE) {
                    $reducClassName = substr_replace($className, '', -(strlen(Configuration::getInstance()->getDbPrefix())), strlen(Configuration::getInstance()->getDbPrefix()));
                }

                if (Configuration::isValidClass($reducClassName . Configuration::getInstance()->getDbPrefix())) {
                    $return = new TableEntity($reducClassName);
                } else if (Configuration::isValidClass($reducClassName)) {
                    $return = new TableEntity($reducClassName);
                } else {
                    throw new CriticalException('Impossible d\'instancier la classe ' . $reducClassName);
                }
            }
        } catch (Exception $e) {
            var_dump($e);
        }
        return $return;
    }

    /**
     * Va [faire un essai pour] charger <b>une</b> instance <b>unique</b> de la
     * classe spécifiée.
     * @param String $fieldSchema le nom de la classe et du champ 
     * a rechercher (de préférence un identifiant) sous la forme table.champ
     * @param String $fieldValue la valeur de ce champ
     * @return Mixed une instance de la classe
     */
    public static function loadInstance($fieldSchema, $fieldValue) {

        $target = split('\.',$fieldSchema);
        $class = self::getClass($target[0]);

        if ($target[1] != "" && $fieldValue != null) {
            $class->loadBy($target[1], $fieldValue);
        }
        return $class;
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
        if (Configuration::isValidClass($name . Configuration::getInstance()->getDbPrefix())) {

            parent::__construct();
            // On recharge
            $this->reload($name);
        } else {
            throw new CriticalException($name . ' n\'est pas une classe valide (non instanciable)');
        }
    }

}

?>