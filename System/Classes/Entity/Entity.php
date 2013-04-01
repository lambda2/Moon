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

    protected $bdd;
    protected $fields;
    protected $linkedClasses;
    protected $access;
    protected $linkedClassesLoaded = false;

    public function __construct() {
        $this->table    = strtolower(get_class($this)) . Core::getInstance()->getDbPrefix();
        $this->bdd      = Core::getInstance()->bdd()->getDb();
        $this->clearLinkedClasses();

        if (get_class($this) != 'TableEntity') {
            $this->generateProperties();
            $this->generateFields();
        }
    }

    protected function reload($table) {
        $this->table    = strtolower($table) . Core::getInstance()->getDbPrefix();
        $this->bdd      = Core::getInstance()->bdd()->getDb();
        $this->generateProperties();
        $this->generateFields();
    }

    protected function setupFields()
    {
        // Reimplement for define custom properties in fields.
    }

    /**
     * Va tenter d'instancier les classes liées a l'instance courante.
     */
    public function autoLoadLinkedClasses($force = false) {
        if($this->linkedClassesLoaded == false or $force == true)
        {       
            $this->clearLinkedClasses();
            $this->linkedClasses = Core::getBdd()->getMoonLinksFrom($this->table);
            
            foreach ($this->linkedClasses as $linked) {
                if (!isNull($this->fields[$linked->attribute]->getValue()))
                {
                    $linked->loadLinkedInstance(
                        $this->fields[$linked->attribute]->getValue());
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


        // On charge les classes liées
        if($this->linkedClassesLoaded == false)
        {
            $this->autoLoadLinkedClasses();
        }

        // On regarde si on demande une instance liée
        $i = $this->getExternalInstance($name);

        if ($i != false)
        {
            return $i;
        }
        else // Sinon on regarde si c'est un attribut de la classe
        {
            $meth = 'get' . ucfirst($name);
            $methodResult = $this->$meth();

            if($methodResult != false)
            {
                return $methodResult;
            }
            else // Et enfin, on regarde si quelque chose référence cet attribut
            {
                //echo 'not methode !<br>';
                $externals = Core::getBdd()->getMoonLinksFrom($this->table,true);
                $t = array();
                //echo 'on a '.count($externals).' external(s)... <br>';
                if(!isNull($externals)){
                    //var_dump($externals);
                    foreach ($externals as $moonLinkKey => $moonLinkValue) 
                    {
                        if($moonLinkValue->table == $name)
                        {

                            $res = EntityLoader::getClass($moonLinkValue->table);
                            $res->loadBy(
                                $moonLinkValue->attribute, 
                                $this->fields[$moonLinkValue->destinationColumn]->getValue());
                            $res->reloadLinkedClasses();
                            $t[] = $res;

                        }
                    }
                    if(count($t) > 0){
                        return $t;
                    }
                }
            }
        }
        if (Core::getInstance()->debug()) {
            return '<b>[?]</b>';
        }
        return false;
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
     * Nécessite un vrai debug puis refactoring
     * @TODO: Voir ci-dessus.
     */
    public function __call($methodName, $args) {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!isset($this->fields[$property])) {
                if ($this->checkExternalRelation($property)) {
                    return $this->linkedClasses[$property];
                }
                else {
                    return false;
                }
            }
        }

        if(count($matches) < 2)
            return false;

        switch ($matches[1]) {
            case 'set':
            $this->checkArguments($args, 1, 1, $methodName);
            return $this->set($property, $args[0]);
            case 'get':
            $this->checkArguments($args, 0, 0, $methodName);
            return $this->get($property);
            case 'default':
            return false;
        }
        
    }

    /**
     * @author lambda2 
     * @return null no return value
     * 
     * @since v0.0.3 Les propriétés deviennent des classes.
     * -> source potentielle de bugs... be awake !
     */
    protected function generateProperties()
    {
        $prop = Core::getBdd()->getAllEntityFields($this->table);
        $this->fields = $prop;
    }

    /**
     * Va voir si l'objet courant a une relation avec la table spécifiée.
     * Si oui, va renvoyer l'instance de l'objet référencé.
     * @param type $table la table ciblée
     * @return mixed l'instance de la classe distante, false sinon
     */
    public function getExternalInstance($table) {

        foreach ($this->linkedClasses as $key => $value) {

            // Nouvelle version, basée sur le nom du champ local.
            if (strcasecmp($key, $table) == 0) {
                if (!isNull($value->instance))
                    return $value->instance;
            }
        }
        return false;
    }

    public function loadByArray($array)
    {
        $request = "";
        
        /**
         * Dans le cas ou on a plusieurs champs contraints (plusieurs clés primaires
         * par exemple...) on ajoute les contraintes dans la requete.
         */
        if(is_array($array))
        {
            $request = "SELECT * FROM {$this->table} WHERE ";
            $args = array();
            foreach ($array as $key=>$val)  
            {
                $args[] = $key." = ".$val;
            }
            $request .= implode(' AND ', $args);
        }
        else
        {
            throw new CriticalException(
                "Invalid parameters to load an object with array !", 1);
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
                    foreach ($res as $champ => $valeur) {
                        // Pour chaque champ, on met à jour sa valeur
                        // avec celle qu'on vient de récupérer dans la bdd.
                        $this->fields[$champ]->setValue($valeur);
                    }
                }
                else {
                    throw new OrmException('Les champs récupérés ne correspondent pas !');
                }
            }
            return true;
        } 
        else 
        {
            return false;
        }
    }

    /**
     * Charge un objet en fonction d'un ou plusieurs parametres.
     * @since 0.0.3, c'est la merde. L'introduction de EntityField
     * dans le système a un peu perturbé. 
     */
    public function loadBy($field, $value) {

        $request = "";
        
        /**
         * Dans le cas ou on a plusieurs champs contraints (plusieurs clés primaires
         * par exemple...) on ajoute les contraintes dans la requete.
         */
        if(is_array($field) 
            and is_array($value) 
            and count($field) == count($value))
        {
            $request = "SELECT * FROM {$this->table} WHERE ";
            $args = array();
            foreach ($field as $key=>$cle)  
            {
                $args[] = $cle." = ".$value[$key];
            }
            $request .= implode(' AND ', $args);
        }
        else
        {
            $request = "SELECT * FROM {$this->table} WHERE {$field} = '{$value}'";
        }

        //echo 'REQUEST = '.$request.'<br>';

        
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
                    foreach ($res as $champ => $valeur) {
                        // Pour chaque champ, on met à jour sa valeur
                        // avec celle qu'on vient de récupérer dans la bdd.
                        $this->fields[$champ]->setValue($valeur);
                    }
                }
                else {
                    throw new OrmException('Les champs récupérés ne correspondent pas !');
                }
            }
            return true;
        } 
        else 
        {
            return false;
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
            MoonChecker::showHtmlReport($e);
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $f          = EntityLoader::getClass($classe);
            $pri        = $f->getValuedPrimaryFields($res);
            if (!isNull($pri)) {
                $f->loadByArray($pri);
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
                MoonChecker::showHtmlReport($e);
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
        $this->fields[$property];
        return $this;
    }

    /**
     * Va retourner une longue description de la classe.
     * @return string
     */
    public function toLongString() {
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

    /**
     * Va retourner une courte et breve description de la classe.
     * @param boolean true pour afficher les relations (directes) exterieures.
     * @return string
     */
    public function toShortString($showExternalRelations = true) {
        $r = get_class($this)
            .'@'.$this->table.' ('
            .($this->linkedClassesLoaded ? 'loaded' : 'not loaded').')';
        if($showExternalRelations and !isNull($this->linkedClasses))
        {
            $r .= '<ul>';
            foreach ($this->linkedClasses as $key => $value) {
                $r .= '<li>'.$value->attribute;
                if(!isNull($value->instance))
                    $r .= ' -> '.$value->getTargetAdress().' ['.$value->instance.']';
                $r .= '</li>';

            }
            $r .= '</ul>';
        }
        return $r;
    }

    public function __toString() {
        return $this->toShortString();
    }

    protected function checkArguments(array $args, $min, $max, $methodName) {
        $argc = count($args);
        if ($argc < $min or $argc > $max) {
            throw new MemberAccessException('Method ' . $methodName . ' needs minimaly ' . $min . ' and maximaly ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($table) {
        $this->table = $table;
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
        $this->linkedClasses[$field] = new MoonLink($field, $name, $class);
    }

    /**
     * Renvoie TRUE si la chaine donnée en parametre correspond à une référence 
     * d'une table exterieure et que cette dernière n'est pas nulle.
     * Renvoie FALSE sinon.
     */
    protected function checkExternalRelation($query) {
        $f = false;
        if (array_key_exists($query, $this->linkedClasses)) {
            if ($this->linkedClasses[$query] != null)
                $f = true;
        }
        return $f;
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

    public function editField($fieldName){
        if(array_key_exists($fieldName, $this->fields))
            return $this->fields[$fieldName];
        else
            throw new AlertException(
                "The field $fieldName didn't exists in ".$this->table, 1);
    }

    /**
     * @deprecated on ne cherche plus a savoir si un champ
     * possède un id.
     */
    public function hasAnId($field) {
        if (strcmp(strtolower(substr($field, 0, 2)), 'id') == 0)
            return true;
        else
            return false;
    }

    /**
     * @deprecated pour les memes raisons que hasAnId()
     * @see Entity::hasAnId($field)
     */
    public function getNameWithoutId($field) {
        return $this->getForeignLink($field);
    }

    public function getRelationClassName($field) {
        $cl = split('\.', $this->getForeignLink($field));
        $cl = $cl[0];
        if (!Core::isValidClass($cl)) {
            $cl = null;
        }
        return $cl;
    }

    public function getRelationClassInstance($field, $target = null) {

        if(is_a($target, 'EntityField'))
            $target = $target->getValue();
        // Si on n'a pas spécifié de relation, on la cherche a la main
        if ($target == null) {
            $className = $this->getRelationClassName($field);
            if ($className == null or $className == $this->table) {
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
                    MoonChecker::showHtmlReport($e);
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
                MoonChecker::showHtmlReport($e);
            }
        }
    }

    public static function getProperName($name, $upper = false, $singularize = false) {
        $reducClassName = $name;
        if (Core::getInstance()->getDbPrefix() != '' and strstr($reducClassName, Core::getInstance()->getDbPrefix()) != FALSE) {
            $reducClassName = substr_replace($reducClassName, '', -(strlen(Core::getInstance()->getDbPrefix())), strlen(Core::getInstance()->getDbPrefix()));
        }
        if ($upper) {
            $reducClassName = ucfirst($reducClassName);
        }
        else {
            $reducClassName = strtolower($reducClassName);
        }
        $reducClassName = str_replace('_', ' ', $reducClassName);
          return $reducClassName;
    }

    /**
     * Retourne la liste du nom des champs
     * de la classe dans un tableau.
     */
    public function getFieldsList()
    {
        $list = array();
        foreach ($this->fields as $key => $value) {
            $list[] = $value->getName();
        }
        return $list;
    }

    /**
     * Retourne un tableau contenant le nom du champ
     * de la clé primaire et la valeur de cette clé
     * pour l'instance courante sous la forme clé=valeur.
     */
    public function getDefinedPrimaryFields()
    {
        $list = array();
        foreach ($this->fields as $key => $value) {
            if($value->isPrimary() and !isNull($value->getValue()))
                $list[$value->getName()] = $value->getValue();
        }
        return $list;
    }


    /**
     * Retourne un tableau contenant le nom du champ
     * de la clé primaire et la valeur de cette clé
     * fournie en parametre
     */
    public function getValuedPrimaryFields($res)
    {
        $list = array();
        foreach ($this->fields as $key => $value) {
            $n = $value->getName();
            if($value->isPrimary() and !isNull($res->$n))
                $list[$value->getName()] = $res->$n;
        }
        return $list;
    }

    /**
     * Retourne un tableau contenant la liste des clés primaires.
     */
    public function getPrimaryFields()
    {
        $list = array();
        foreach ($this->fields as $key => $value) {
            if($value->isPrimary())
                $list[] = $value->getName();
        }
        return $list;
    }

    /**
     * Génere un champ d'insertion en HTML.
     */
    public function generateInsertForm($name = '', $label='')
    {
        $this->setupFields();

        if(isNull($name))
            $formName = 'insert-'.get_class($this);
        else
            $formName = $name;

        $form = new Form($formName,
            Core::opts()->system->siteroot
            .'index.php?moon-action=insert&target='
            .$this->table);

        if(!isNull($label))
            $form->setButtonLabel($label);
        
        foreach ($this->fields as $champ => $valeur) {
            $form->addField($valeur->getHtmlField());
        }

        return $form;
    }

    /**
     * Génere un champ de mise à jour en HTML.
     */
    public function generateUpdateForm($name = '', $label='')
    {
        $this->setupFields();

        if(isNull($name))
            $formName = 'update-'.get_class($this);
        else
            $formName = $name;

        $keysList = $this->getDefinedPrimaryFields();

        $form = new Form($formName,
            Core::opts()->system->siteroot
            .'index.php?moon-action=update&target='
            .$this->table);

        if(!isNull($label))
            $form->setButtonLabel($label);

        foreach ($this->fields as $champ => $valeur) {
            $form->addField($valeur->getHtmlField());
        }

        $destination = new Input('ids','hidden');
        $destination->setValue(arr2param($keysList,','));
        $form->addField($destination);

        return $form;
    }

    /**
     * Génere un champ de supression en HTML.
     */
    public function generateDeleteForm($name = '', $label='')
    {
        $this->setupFields();

        if(isNull($name))
            $formName = 'delete-'.get_class($this);
        else
            $formName = $name;

        $keysList = $this->getDefinedPrimaryFields();

        $form = new Form($formName,
            Core::opts()->system->siteroot
            .'index.php?moon-action=delete&target='
            .$this->table);

        if(!isNull($label))
            $form->setButtonLabel($label);

        $destination = new Input('ids','hidden');
        $destination->setValue(arr2param($keysList,','));
        $form->addField($destination);

        return $form;
    }

    /**
     * Retire de la data fournie en parametre
     * tout ce qui n'est pas une donnée de la classe.
     * Cette méthode renvoie donc un tableau constitué
     * seuelement des champs appartenant a la classe.
     */
    protected function parseDataForAction($data)
    {
        $results = array();
        $fieldsList = $this->getFieldsList();
        foreach ($data as $key => $value) {
            if(in_array($key, $fieldsList))
            {
                $results[$key] = $value;
            }
        }
        return $results;
    }

    /**
     * Procède à l'insertion de la data fournie en parametre
     * dans la base de données.
     */
    public function processInsertForm($data=array())
    {
        $fields = $this->parseDataForAction($data);
        if(Core::getBdd()->insert($fields,$this->table))
            return true;
        else
            return false;
    }

    /**
     * Procède à la mise à jour de la data fournie en parametre
     * dans la base de données.
     */
    public function processUpdateForm($data=array())
    {
        $fields = $this->parseDataForAction($data);
        if(Core::getBdd()->update(
            $fields,
            $this->table,
            $this->getDefinedPrimaryFields())
            )
            return true;
        else
            return false;
    }

    // End of Entity class //

    }
?>