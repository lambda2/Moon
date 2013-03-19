<?php

/**
 * Classe représentant une entitée (généralement issue de la base de données)
 * @TODO: Faire des **TAINS de tests unitaires sur cette classe en carton.
 * ...carton mouillé.
 * ...avec des escargots dessus.
 * ...qui sucent ce carton.
 * ...et font des trous.
 * ...et il pleut.
 * 
 * @TODO : enlever tous les vieux echo(...) dégeulasses après avoir testé
 * [serieusement] cette classe. 
 * 
 * @TODO : [UNE FOIS LES TESTS UNITAIRES FAITS] Gerer les relations multiples
 * et récursives. Pour l'instant on ne gere que les relations 1..0 à 1..0 et
 * seulement une relation par table ! Voir l'attribut [$linkedClasses] qui est 
 * un put*** de tableau associatif ou la clé est la table distante référencée 
 * et qui ne peut pas contenir deux clés de meme valeur, donc pas deux fois
 * la meme table... Pas de parrains pour les filleuls quoi...
 */
abstract class Entity {

    protected $table;
    protected $editable;
    protected $bdd;
    protected $fields;
    protected $linkedClasses;
    protected $access;
    protected $linkedClassesLoaded = false;

    public function __construct() {


        $this->table    = strtolower(get_class($this)) . Core::getInstance()->getDbPrefix();
        $this->editable = new Editable($this, $this->table);
        $this->bdd      = Core::getInstance()->bdd()->getDb();
        //$this->access   = new Access();
        //$this->access->loadFromTable(strtolower(get_class($this)));
        $this->clearLinkedClasses();

        if (get_class($this) != 'TableEntity') {
            $this->generateProperties();
            $this->generateFields();
        }
    }

    protected function reload($table) {
        $this->table    = strtolower($table) . Core::getInstance()->getDbPrefix();
        $this->editable = new Editable($this, $this->table);
        $this->bdd      = Core::getInstance()->bdd()->getDb();
        /* $this->access   = new Access();
        $this->access->loadFromTable(strtolower(get_class($this))); */

        $this->generateProperties();
        $this->generateFields();
        //$this->clearLinkedClasses();
        //$this->autoLoadLinkedClasses();
    }

    /**
     * Va tenter d'instancier les classes liées a l'instance courante.
     */
    public function autoLoadLinkedClasses($force = false) {
        if($this->linkedClassesLoaded == false || $force == true)
        {       
            $this->clearLinkedClasses();
            $this->linkedClasses = Core::getBdd()->getMoonLinksFrom($this->table);
            
            foreach ($this->linkedClasses as $linked) {
                    // echo "trying to link " . $linked->attribute . " ... [value inside : " . $this->fields[$linked->attribute] . "]<br>";
                if (!isNull($this->fields[$linked->attribute]))
                {
                    $linked->loadLinkedInstance(
                        $this->fields[$linked->attribute]);
                }
            }
            
            $this->linkedClassesLoaded = true;
        }
    }

    /**
     * Va effectuer un rechargement de force des instances liées.
     */
    public function reloadLinkedClasses(){
        $this->autoLoadLinkedClasses(true);
    }

    /**
     * Surcharge de la méthode magique __get().
     * On va d'abord regarder si l'attribut demandé est une classe liée.
     * On délegue ensuite a la méthode __call()
     */
    public function __get($name) {
        
        if($this->linkedClassesLoaded == false){
            $this->autoLoadLinkedClasses();
        }

        $i = $this->getExternalInstance($name);
        if ($i != false)
            return $i;
        else {
            $meth = 'get' . ucfirst($name);
            return $this->$meth();
        }
    }

    /**
     * Implémentation nécessaire pour que twig accepte d'apeller __get()
     * @param type $name
     * @return boolean
     */
    public function __isset($name) {
        try {
            $e = $this->$name;
        } catch (Exception $exc) {
            dbg($exc->getMessage(), 20);
            return false;
        }
        return true;
    }

    /**
     * Véritable nid à bugs de l'application.
     * Nécessite un vrai débug puis refactoring
     * @TODO: Voir ci-dessus.
     */
    public function __call($methodName, $args) {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!isset($this->fields[$property])) {
                if (!isset($this->fields[$property . '_' . $this->getProperName($this->table)])) {
                    if (!isset($this->fields[$property . '_' . $this->getProperName($this->table, false, true)])) {
                        if ($this->checkExternalRelation($property)) {
                            return $this->linkedClasses[$property];
                        }
                        else {
                            throw new MemberAccessException('Property ' . $property . ' not exists');
                        }
                    }
                    else {
                        $property = $property . '_' . $this->getProperName($this->table, false, true);
                    }
                }
                else {
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

    /**
     * @author lambda2 
     * @return null no return value
     */
    protected function generateProperties()
    {
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
     * Va voir si l'objet courant a une relation avec la table spécifiée.
     * Si oui, va renvoyer l'instance de l'objet référencé.
     * @param type $table la table ciblée
     * @return mixed l'instance de la classe distante, false sinon
     */
    public function getExternalInstance($table) {

        foreach ($this->linkedClasses as $key => $value) {
            
            // Ancienne version avec la table de detination comme argument de 
            // recherche. Ne marche donc pas si deux champs pointent sur la meme
            // Colonne distante...

            // if (strcasecmp($value->destinationTable, $table) == 0) {
            //     if (!isNull($value->instance))
            //         return $value->instance;
            // }

            // Nouvelle version, basée sur le nom du champ local.
            if (strcasecmp($key, $table) == 0) {
                if (!isNull($value->instance))
                    return $value->instance;
            }
        }
        return false;
    }

    /**
     * Charge un objet en fonction d'un ou plusieurs parametres.
     */
    public function loadBy($field, $value) {
        
        $request = "SELECT * FROM {$this->table} WHERE {$field} = '{$value}'";
        
        /**
         * Dans le cas ou on a plusieurs champs contraints (plusieurs clés primaires
         * par exemple...) on ajoute les contraintes dans la requete.
         */
        if(is_array($field) 
            && is_array($value) 
            && count($field) == count($value)){
            $request = "SELECT * FROM {$this->table} WHERE ";
        $args = array();
        foreach ($field as $key=>$cle) {
            $args[] = $cle." = ".$value[$key];
        }
        $request .= implode(' AND ', $args);
    }
    
    try 
    {
        $Req = $this->bdd->prepare($request);
        $Req->execute(array());
    } 
        catch (Exception $e) //interception de l'erreur
        {
            throw new OrmException("Error Processing Request");
        }

        // Si on récupère quelque chose :
        if ($Req->rowCount() != 0) {
            while ($res = $Req->fetch(PDO::FETCH_ASSOC)) {
                if (count($this->fields) >= count($res)) {
                    $this->fields = $res;
                }
                else {
                    throw new OrmException('Les champs récupérés ne correspondent pas !');
                }
            }
            return true;
        } // Sinon on relance la méthode avec d'autres arguments :
        else if (strripos($field, '_' . $this->table) == false) 
        {
            if (!$this->loadBy($field . '_' . $this->table, $value)) 
            {
                $prefix = Core::getInstance()->getDbPrefix();
                
                if (strripos($field . '_' . $this->table, $prefix) != FALSE) 
                {
                    echo "la table finit par le préfixe ! Beurk !";
                    $field = $field . substr_replace(
                        '_' . $this->table, '', -(strlen($prefix)), strlen($prefix));
                    return $this->loadBy($field, $value);
                }
                else 
                {
                    //echo "la table ne finit pas par le préfixe ! Ouf !";
                    return false;
                }
            }
            else 
            {
                return true;
            }
        }
        else 
        {
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
            $type   = "select";
            $target = $this->getForeignLink($res->Field);
            $this->editable->add(new Champ($res->Field, $res->Field, ucfirst(str_replace('id_', '', $res->Field)), $type, $target, ''));
        }
        elseif ($res->Key == "") {
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
        $foreignRow   = "";
        $split        = split('_', $rowName);
        if (count($split) > 2) {
            $foreignTable = join('_', array_slice($split, 1, -1)) . Core::getInstance()->getDbPrefix();
            $foreignRow   = join('_', array_slice($split, 0, -1));
        }
        return $foreignTable . '.' . $foreignRow;
    }

    /**
     * Retourne toutes les instances de l'objet définies dans la base de données
     * dans un tableau.
     * @return array toutes les instances de l'objet dans la bdd
     */
    public static function getAllObjects($classe) {
        $c = EntityLoader::getClass($classe);
        $t = array();
        try {
            $Req = Core::getBdd()->getDb()->prepare("SELECT * FROM {$c->getTable()}");
            $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
    while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
        $f        = EntityLoader::getClass($classe);
        $priField = array();
        $priValues = array();
        foreach (Core::getBdd()->getAllColumnsFrom($c->getTable()) as $col) {
            if ($col->Key == 'PRI'){
                $primaryKeyField = $col->Field;
                $priField[] = $primaryKeyField;
                $priValues[] = $res->$primaryKeyField;
            }
        }
        if (!isNull($priField)) {
            $f->loadBy($priField, $priValues);
            $f->autoLoadLinkedClasses();
            $t[] = $f;
        }
    }

    return $t;
}

public function getAll() {
    $t = array();
    try {
        $Req = $this->bdd->prepare("SELECT * FROM {$this->table}");
        $Req->execute(array());
        } catch (Exception $e) { //interception de l'erreur
        die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
    }
    while ($res = $Req->fetch(PDO::FETCH_ASSOC)) {
        $newTab = [];
        foreach ($res as $key => $value) {
            $newTab[str_replace('_' . $this->table, '', $key)] = $value;
        }
        $t[] = $newTab;
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
    $s = '<div><h4>Classe ' . get_class($this) . ' :</h4>';
    $s .= '<h5>Référencée par la table ' . $this->table . '.</h5>';
    foreach ($this as $key => $value) {
        if (!is_a($value, "PDO")) {
            if (is_array($value)) {
                $s .= "<h5>$key : </h5>";
                $s .= '<table class="table">';

                foreach ($value as $k => $v) {
                    $s .= '<tr><td></td><td>' . $k . '</td><td> ==> </td><td>' . $v . '</td></tr>';
                }
                if (count($value) == 0)
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
        $this->linkedClasses   = $linkedClasses;
    else
        $this->linkedClasses[] = $linkedClasses;
}

protected function addLinkedClass($field, $class, $name) {
        //echo "( $field => $name )<br>";
    $this->linkedClasses[$field] = new MoonLink($field, $name, $class);
        //var_dump($this->linkedClasses[$field]);
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
        /* $t = explode(".", $target);
          if (count($t) < 2)
          throw new Exception('cible invalide pour le bind  ' . $target);

          if ($display == null)
          $display = $t[0];

          else {
          $this->linkedClasses[$display] =
          new MoonLink(
          $field, $target, $this->getRelationClassInstance($field, $target)
          );
} */
}


    /* public function getAccess() {
      return $this->access;
      }

      public function setAccess($access) {
      $this->access = $access;
  } */

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
    return $this->getForeignLink($field);
}

public function getRelationClassName($field) {
        //var_dump(split('\.',$this->getForeignLink($field)));
    $cl = split('\.', $this->getForeignLink($field));
    $cl = $cl[0];
    if (!Core::isValidClass($cl)) {
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
        }
        else {
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
    }
    else {
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
    if (Core::getInstance()->getDbPrefix() != '' && strstr($reducClassName, Core::getInstance()->getDbPrefix()) != FALSE) {
        $reducClassName = substr_replace($reducClassName, '', -(strlen(Core::getInstance()->getDbPrefix())), strlen(Core::getInstance()->getDbPrefix()));
    }
    if ($upper) {
        $reducClassName = ucfirst($reducClassName);
    }
    else {
        $reducClassName = strtolower($reducClassName);
    }
    $reducClassName = str_replace('_', ' ', $reducClassName);

        /* if ($singularize) {
          $reducClassName = singularize($reducClassName);
      } */
      return $reducClassName;
  }

}

?>