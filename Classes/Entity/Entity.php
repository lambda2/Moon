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
abstract class Entity implements JsonSerializable
{

    protected $table;
    protected $bdd;
    protected $fields;
    protected $linkedClasses;
    protected $happyFields;
    protected $access;
    protected $linkedClassesLoaded = false;
    protected $instance = false;
    protected $customButtons = array ();

    public function __construct ()
    {
        $this->table = strtolower (get_class ($this)) . Core::getInstance ()->getDbPrefix ();
        $this->bdd = Core::getInstance ()->bdd ()->getDb ();
        $this->clearLinkedClasses ();
        $this->happyFields = new Happy\HappyField();

        if ( get_class ($this) != 'TableEntity' )
        {
            $this->generateProperties ();
            $this->generateFields ();
        }
    }

    protected function reload ($table)
    {
        $this->table = strtolower ($table) . Core::getInstance ()->getDbPrefix ();
        $this->bdd = Core::getInstance ()->bdd ()->getDb ();
        $this->generateProperties ();
        $this->generateFields ();
    }

    protected function setupFields ()
    {
        // Reimplement for define custom properties in fields.
    }

    /**
     * Va tenter d'instancier les classes liées a l'instance courante.
     */
    public function autoLoadLinkedClasses ($force = false)
    {
        if ( $this->linkedClassesLoaded == false or $force == true )
        {
            $this->clearLinkedClasses ();
            $this->linkedClasses = Core::getBdd ()->getMoonLinksFrom ($this->table);
            foreach ($this->linkedClasses as $linked)
            {
                if ( !isNull ($this->fields[$linked->attribute]->getValue ()) )
                {
                    $linked->loadLinkedInstance (
                            $this->fields[$linked->attribute]->getValue ());
                }
            }
            $this->linkedClassesLoaded = true;
        }
    }

    /**
     * Va effectuer un rechargement de force des instances liées.
     */
    public function reloadLinkedClasses ()
    {
        $this->autoLoadLinkedClasses (true);
    }

    /**
     * Permet d'attribuer une valeur a nos champs
     */
    public function __set ($name, $value)
    {
        if ( array_key_exists ($name, $this->fields) )
        {
            $this->fields[$name]->setValue ($value);
        }
    }

    /**
     * Return the table with the given name.
     * Use this method when there is a ambiguity between
     * a table name and an attribute (same name) to explicitely
     * ask for the table object, so the linked Entities.
     * @param String $name the name of the table to return.
     * @return Entities the corresponding entities.
     */
    public function table ($name)
    {
        /*
          $a = $this->constraintEntities(new Entities($this->table));
          $a = $a->table($name);
          return $a;
         */
        $iTable = $this->getExternalTables ($name, $this);
        if ( $iTable != false )
        {
            return $iTable;
        }
        else // Sinon on regarde si c'est un attribut de la classe
        {
            // Et enfin, on regarde si quelque chose référence cet attribut
            $externals = Core::getBdd ()->getMoonLinksFrom ($this->table, true);
            $t = array ();
            if ( !isNull ($externals) )
            {
                foreach ($externals as $moonLinkKey => $moonLinkValue)
                {
                    if ( $moonLinkValue->table == $name )
                    {
                        $res = EntityLoader::getClass ($moonLinkValue->table);
                        if ( $res != null )
                        {
                            $t = new Entities ($this->table);
                            $t = $this->constraintEntities ($t);
                        }
                        return $t->table ($name);
                    }
                }
            }
        }
        return null;
    }

    protected function constraintEntities ($entity)
    {
        foreach ($this->fields as $field)
        {
            if ( $field->getValue () != null )
            {
                $entity->where ($field->getName (), $field->getValue ());
            }
        }
        return $entity;
    }

    /**
     * Surcharge de la méthode magique __get().
     * On va d'abord regarder si l'attribut demandé est une classe liée.
     * On délegue ensuite a la méthode __call()
     */
    public function __get ($name)
    {

        if ( array_key_exists ($name, $this->fields) )
        {
            return $this->fields[$name]->getValue ();
        }

        $meth = 'get' . ucfirst ($name);
        $methodResult = $this->$meth ();

        if ( $methodResult != false )
        {
            return $methodResult;
        }

        // On charge les classes liées
        if ( $this->linkedClassesLoaded == false )
        {
            $this->autoLoadLinkedClasses ();
        }

        // On regarde si on demande une instance liée
        $i = $this->getExternalInstance ($name);

        if ( $i != false )
        {
            return $i;
        }

        $ext = $this->table ($name);
        if ( $ext !== false )
            return $ext;

        if ( Core::getInstance ()->debug () )
        {
            throw new AlertException ("The attribute $name doesn't exists.", 1);
        }
        return null;
    }

    /**
     * Implémentation nécessaire pour que twig accepte d'apeller __get()
     * @param type $name
     * @return boolean
     */
    public function __isset ($name)
    {
        try
        {
            $e = $this->$name;
        }
        catch (Exception $exc)
        {
            dbg ($exc->getMessage (), 20);
            return false;
        }
        return true;
    }

    /**
     * Véritable nid à bugs de l'application.
     * Nécessite un vrai debug puis refactoring
     * @TODO: Voir ci-dessus.
     *    ^----- @since 0.0.3 c'est quand meme un peu mieux...
     * @TODO: prendre une décision : peut on apeller getnom()
     * ou se limite on [strictement] à getNom ?
     * -> regex ([A-Za-z]) ou ([A-Z]) ? (ci dessous)
     */
    public function __call ($methodName, $args)
    {
        if ( preg_match ('~^(set|get)([A-Za-z])(.*)$~', $methodName, $matches) )
        {
            $property = strtolower ($matches[2]) . $matches[3];
            if ( !isset ($this->fields[$property]) )
            {
                if ( $this->checkExternalRelation ($property) )
                {
                    return $this->linkedClasses[$property];
                }
                else
                {
                    return null;
                }
            }
        }

        if ( count ($matches) < 2 )
            return false;

        switch ($matches[1])
        {
            case 'set':
                $this->checkArguments ($args, 1, 1, $methodName);
                return $this->set ($property, $args[0]);
            case 'get':
                $this->checkArguments ($args, 0, 0, $methodName);
                return $this->get ($property);
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
    protected function generateProperties ()
    {
        $prop = Core::getBdd ()->getAllEntityFields ($this->table);
        $this->fields = $prop;
    }

    /**
     * Va voir si l'objet courant a une relation avec la table spécifiée.
     * Si oui, va renvoyer l'instance de l'objet référencé.
     * @param type $table la table ciblée
     * @return mixed l'instance de la classe distante, false sinon
     */
    public function getExternalInstance ($table)
    {

        foreach ($this->linkedClasses as $key => $value)
        {
            // Nouvelle version, basée sur le nom du champ local.
            if ( strcasecmp ($value->attribute, $table) == 0 )
            {
                if ( !isNull ($value->instance) )
                    return $value->instance;
            }
        }
        return false;
    }

    /**
     * Va voir si l'objet courant a une relation avec la table spécifiée.
     * Si oui, va renvoyer l'instance de l'objet référencé.
     * @param type $table la table ciblée
     * @return mixed l'instance de la classe distante, false sinon
     */
    public function getExternalTables ($table, $entity)
    {
        foreach ($entity->getLinkedClasses () as $key => $value)
        {
            if ( strcasecmp ($value->destinationTable, $table) == 0 )
            {
                if ( !isNull ($value->instance) )
                    return $value->instance;
            }
        }
        return false;
    }

    public function loadByArray ($array)
    {
        $request = "";
        /**
         * Dans le cas ou on a plusieurs champs contraints (plusieurs clés primaires
         * par exemple...) on ajoute les contraintes dans la requete.
         */
        if ( is_array ($array) )
        {
            $request = "SELECT * FROM {$this->table} WHERE ";
            $args = array ();
            foreach ($array as $key => $val)
            {
                $args[] = $key . " = " . dbQuote ($val);
            }
            $request .= implode (' AND ', $args);
        }
        else
        {
            throw new CriticalException (
            "Invalid parameters to load an object with array !", 1);
        }
        try
        {
            Profiler::addRequest ($request);
            $Req = $this->bdd->prepare ($request);
            $Req->execute (array ());
        }
        catch (Exception $e) //interception de l'erreur
        {
            throw new OrmException ("Error Processing Request");
        }

        // Si on récupère quelque chose :
        if ( $Req->rowCount () != 0 )
        {
            while ($res = $Req->fetch (PDO::FETCH_ASSOC))
            {
                if ( count ($this->fields) >= count ($res) )
                {
                    foreach ($res as $champ => $valeur)
                    {
                        // Pour chaque champ, on met à jour sa valeur
                        // avec celle qu'on vient de récupérer dans la bdd.
                        $this->fields[$champ]->setValue ($valeur);
                    }
                }
                else
                {
                    throw new OrmException ('Les champs récupérés ne correspondent pas !');
                }
            }
            $this->instance = true;
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
    public function loadBy ($field, $value)
    {

        $request = "";
        /**
         * Dans le cas ou on a plusieurs champs contraints (plusieurs clés primaires
         * par exemple...) on ajoute les contraintes dans la requete.
         */
        if ( is_array ($field) and is_array ($value) and count ($field) == count ($value) )
        {
            $request = "SELECT * FROM {$this->table} WHERE ";
            $args = array ();
            foreach ($field as $key => $cle)
            {
                $args[] = $cle . " = " . $value[$key];
            }
            $request .= implode (' AND ', $args);
        }
        else
        {
            $request = "SELECT * FROM {$this->table} WHERE {$field} = '{$value}'";
        }

        try
        {
            Profiler::addRequest ($request);
            $Req = $this->bdd->prepare ($request);
            $Req->execute (array ());
        }
        catch (Exception $e) //interception de l'erreur
        {
            throw new OrmException ("Error Processing Request");
        }

        // Si on récupère quelque chose :
        if ( $Req->rowCount () != 0 )
        {
            while ($res = $Req->fetch (PDO::FETCH_ASSOC))
            {
                if ( count ($this->fields) >= count ($res) )
                {
                    foreach ($res as $champ => $valeur)
                    {
                        // Pour chaque champ, on met à jour sa valeur
                        // avec celle qu'on vient de récupérer dans la bdd.
                        $this->fields[$champ]->setValue ($valeur);
                    }
                }
                else
                {
                    throw new OrmException ('Les champs récupérés ne correspondent pas !');
                }
            }
            $this->instance = true;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @return True if the instance exists
     */
    public function exists ()
    {
        return $this->instance;
    }

    /**
     * Explain how the php engine have to serialize an
     * Entity object to json.
     */
    public function jsonSerialize ()
    {
        $ret = array ();
        foreach ($this->fields as $field)
        {
            $ret[$field->getName ()] = $field->getValue ();
        }
        return $ret;
    }

    /**
     * Essaie de retourner le champ visé par la relation étrangère fournie en paramètre
     * @param String $rowName le nom du champ (ex : 'id_client_contrat' )
     * @return String la table et le nom de la colonne ciblée (ex : 'client.id_client')
     */
    protected function getForeignLink ($rowName)
    {
        $foreignTable = "";
        $foreignRow = "";
        $split = split ('_', $rowName);
        if ( count ($split) > 2 )
        {
            $foreignTable = join ('_', array_slice ($split, 1, -1)) . Core::getInstance ()->getDbPrefix ();
            $foreignRow = join ('_', array_slice ($split, 0, -1));
        }
        return $foreignTable . '.' . $foreignRow;
    }

    /**
     * Retourne toutes les instances de l'objet définies dans la base de données
     * dans un tableau.
     * @return array toutes les instances de l'objet dans la bdd
     */
    public static function getAllObjects ($classe)
    {
        $c = EntityLoader::getClass ($classe);
        $t = array ();

        try
        {
            $Req = Core::getBdd ()->getDb ()->prepare ("SELECT * FROM {$c->getTable ()}");
            Profiler::addRequest ("SELECT * FROM {$c->getTable ()}");
            $Req->execute (array ());
        }
        catch (Exception $e)
        { //interception de l'erreur
            MoonChecker::showHtmlReport ($e);
        }
        while ($res = $Req->fetch (PDO::FETCH_OBJ))
        {
            $f = EntityLoader::getClass ($classe);
            $pri = $f->getValuedPrimaryFields ($res);
            if ( !isNull ($pri) )
            {
                $f->loadByArray ($pri);
                $f->autoLoadLinkedClasses ();
                $t[] = $f;
            }
        }

        return $t;
    }

    /**
     * A checker... Elle semble identique à celle du dessus...
     * @TODO : Voir au dessus...
     */
    public function getAll ()
    {
        $t = array ();
        try
        {
            $Req = $this->bdd->prepare ("SELECT * FROM {$this->table}");
            Profiler::addRequest ("SELECT * FROM {$this->getTable ()}");
            $Req->execute (array ());
        }
        catch (Exception $e)
        { //interception de l'erreur
            MoonChecker::showHtmlReport ($e);
        }
        while ($res = $Req->fetch (PDO::FETCH_ASSOC))
        {
            $newTab = array ();
            foreach ($res as $key => $value)
            {
                $newTab[str_replace ('_' . $this->table, '', $key)] = $value;
            }
            $t[] = $newTab;
        }

        return $t;
    }

    public function get ($property)
    {
        return $this->fields[$property];
    }

    public function set ($property, $value)
    {
        $this->fields[$property];
        return $this;
    }

    /**
     * Va retourner une longue description de la classe.
     * @return string
     */
    public function toLongString ()
    {
        $s = '<div><h4>Classe ' . get_class ($this) . ' :</h4>';
        $s .= '<h5>Référencée par la table ' . $this->table . '.</h5>';
        foreach ($this as $key => $value)
        {
            if ( !is_a ($value, "PDO") )
            {
                if ( is_array ($value) )
                {
                    $s .= "<h5>$key : </h5>";
                    $s .= '<table class="table">';

                    foreach ($value as $k => $v)
                    {
                        $s .= '<tr><td></td><td>' . $k . '</td><td> ==> </td><td>' . $v . '</td></tr>';
                    }
                    if ( count ($value) == 0 )
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
    public function toShortString ($showExternalRelations = true)
    {
        $r = get_class ($this)
                . '@' . $this->table . ' ('
                . ($this->linkedClassesLoaded ? 'loaded' : 'not loaded') . ')';
        if ( $showExternalRelations and !isNull ($this->linkedClasses) )
        {
            $r .= '<ul>';
            foreach ($this->linkedClasses as $key => $value)
            {
                $r .= '<li>' . $value->attribute;
                if ( !isNull ($value->instance) )
                    $r .= ' -> ' . $value->getTargetAdress () . ' [' . $value->instance . ']';
                $r .= '</li>';
            }
            $r .= '</ul>';
        }
        return $r . '<br>';
    }

    public function isLinkedClassesLoaded ()
    {
        return $this->linkedClassesLoaded;
    }

    public function __toString ()
    {
        return $this->toShortString (false);
    }

    protected function checkArguments (array $args, $min, $max, $methodName)
    {
        $argc = count ($args);
        if ( $argc < $min or $argc > $max )
        {
            throw new MemberAccessException ('Method ' . $methodName . ' needs minimaly ' . $min . ' and maximaly ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }

    public function getTable ()
    {
        return $this->table;
    }

    public function setTable ($table)
    {
        $this->table = $table;
    }

    public function getBdd ()
    {
        return $this->bdd;
    }

    public function setBdd ($bdd)
    {
        $this->bdd = $bdd;
    }

    public function getLinkedClasses ()
    {
        return $this->linkedClasses;
    }

    protected function setLinkedClasses ($linkedClasses)
    {
        if ( is_array ($linkedClasses) )
            $this->linkedClasses = $linkedClasses;
        else
            $this->linkedClasses[] = $linkedClasses;
    }

    protected function addLinkedClass ($field, $class, $name)
    {
        $this->linkedClasses[$field] = new MoonLink ($field, $name, $class);
    }

    /**
     * Renvoie TRUE si la chaine donnée en parametre correspond à une référence
     * d'une table exterieure et que cette dernière n'est pas nulle.
     * Renvoie FALSE sinon.
     */
    protected function checkExternalRelation ($query)
    {
        $f = false;
        if ( array_key_exists ($query, $this->linkedClasses) )
        {
            if ( $this->linkedClasses[$query] != null )
                $f = true;
        }
        return $f;
    }

    public function clearLinkedClasses ()
    {
        $this->linkedClasses = array ();
    }

    public function getFields ()
    {
        return $this->fields;
    }

    public function setFields ($fields)
    {
        $this->fields = $fields;
    }

    public function editField ($fieldName)
    {
        if ( array_key_exists ($fieldName, $this->fields) )
            return $this->fields[$fieldName];
        else
            throw new AlertException (
            "The field $fieldName didn't exists in " . $this->table, 1);
    }

    /**
     * @deprecated on ne cherche plus a savoir si un champ
     * possède un id.
     */
    public function hasAnId ($field)
    {
        if ( strcmp (strtolower (substr ($field, 0, 2)), 'id') == 0 )
            return true;
        else
            return false;
    }

    /**
     * @deprecated pour les memes raisons que hasAnId()
     * @see Entity::hasAnId($field)
     */
    public function getNameWithoutId ($field)
    {
        return $this->getForeignLink ($field);
    }

    public function getRelationClassName ($field)
    {
        $cl = split ('\.', $this->getForeignLink ($field));
        $cl = $cl[0];
        if ( !Core::isValidClass ($cl) )
        {
            $cl = null;
        }
        return $cl;
    }

    public function getRelationClassInstance ($field, $target = null)
    {

        if ( is_a ($target, 'EntityField') )
            $target = $target->getValue ();
        // Si on n'a pas spécifié de relation, on la cherche a la main
        if ( $target == null )
        {
            $className = $this->getRelationClassName ($field);
            if ( $className == null or $className == $this->table )
            {
                return null;
            }
            else
            {
                try
                {
                    $inst = EntityLoader::loadInstance ($this->getForeignLink ($field), $this->fields[$field]);
                    if ( strcmp ($inst->getTable (), $this->getTable ()) == 0 )
                    {
                        return null;
                    }
                    return $inst;
                }
                catch (Exception $e)
                {
                    MoonChecker::showHtmlReport ($e);
                }
            }

            // Sinon on la charge directement
        }
        else
        {
            try
            {
                $inst = EntityLoader::loadInstance ($target, $this->fields[$field]);
                if ( strcmp ($inst->getTable (), $this->getTable ()) == 0 )
                {
                    return null;
                }
                return $inst;
            }
            catch (Exception $e)
            {
                MoonChecker::showHtmlReport ($e);
            }
        }
    }

    public static function getProperName ($name, $upper = false, $singularize = false)
    {
        $reducClassName = $name;
        if ( Core::getInstance ()->getDbPrefix () != '' and strstr ($reducClassName, Core::getInstance ()->getDbPrefix ()) != FALSE )
        {
            $reducClassName = substr_replace ($reducClassName, '', -(strlen (Core::getInstance ()->getDbPrefix ())), strlen (Core::getInstance ()->getDbPrefix ()));
        }
        if ( $upper )
        {
            $reducClassName = ucfirst ($reducClassName);
        }
        else
        {
            $reducClassName = strtolower ($reducClassName);
        }
        $reducClassName = str_replace ('_', ' ', $reducClassName);
        return $reducClassName;
    }

    /**
     * Retourne la liste du nom des champs
     * de la classe dans un tableau.
     */
    public function getFieldsList ()
    {
        $list = array ();
        foreach ($this->fields as $key => $value)
        {
            $list[] = $value->getName ();
        }
        return $list;
    }

    /**
     * Retourne un tableau contenant le nom du champ
     * de la clé primaire et la valeur de cette clé
     * pour l'instance courante sous la forme clé=valeur.
     */
    public function getDefinedPrimaryFields ()
    {
        $list = array ();
        foreach ($this->fields as $key => $value)
        {
            if ( $value->isPrimary () and !isNull ($value->getValue ()) )
                $list[$value->getName ()] = $value->getValue ();
        }
        return $list;
    }

    /**
     * Retourne un tableau contenant le nom du champ
     * de la clé primaire et la valeur de cette clé
     * fournie en parametre
     */
    public function getValuedPrimaryFields ($res)
    {
        $list = array ();
        foreach ($this->fields as $key => $value)
        {
            $n = $value->getName ();
            if ( $value->isPrimary () and !isNull ($res->$n) )
                $list[$value->getName ()] = $res->$n;
        }
        return $list;
    }

    /**
     * Retourne un tableau contenant la liste des clés primaires.
     */
    public function getPrimaryFields ()
    {
        $list = array ();
        foreach ($this->fields as $key => $value)
        {
            if ( $value->isPrimary () )
                $list[] = $value->getName ();
        }
        return $list;
    }

    /**
     * Will search in the form files for a rules set
     * corresponding to the given $formName.
     * If the file exists, it apply the rules.
     * @return boolean true if found, false otherwise
     * @TODO : replace this method by [getRulesForForm]
     */
    public function searchForDefinedDatas ($formName)
    {
        return $this->getRulesForForm ($formName);
    }

    protected function applyDataToEntityFields ($data)
    {
        if ( $data === false )
            return false;
        else
        {
            foreach ($data as $field => $datas)
            {

                if ( array_key_exists ($field, $this->fields) )
                {

                    if ( array_key_exists ('foreignLabel', $datas) )
                    {
                        $this->fields[$field]->setForeignDisplayTarget ($datas['foreignLabel']);
                    }
                }
                else
                {
                    Debug::log ("Le champ $field n'existe pas...");
                }
            }
            return true;
        }
    }

    /**
     * Generate an Form object for the targeted action.
     *
     * @param string $action the acton name, like 'update', 'insert' or 'delete'
     * @param string $name the name of the form, default : [$action-$table]
     * @param string $label the text of the submit button
     * @param mixed $fields the fields to generate, in an array. default: all the fields
     * @param boolean $empty if true, the form will not contain form elements
     * @return \Form the form object
     */
    public function generateFormFor ($action, $name = '', $label = '', $fields = 'all', $empty = false)
    {
        if ( isNull ($name) )
        {
            $name = $action . '-' . strtolower ($this->table);
        }

        $this->setupFields ();

        if ( isNull ($name) )
            $formName = $action . '-' . strtolower ($this->table);
        else
            $formName = $name;

        $datas = $this->searchForDefinedDatas ($name);
        $this->applyDataToEntityFields ($datas);

        $form = new Form ($formName, Core::opts ()->system->siteroot
                . 'index.php?moon-action=' . $action . '&target='
                . strtolower ($this->table) . '&formName='
                . $formName);

        if ( !isNull ($label) )
            $form->setButtonLabel ($label);

        if ( !$empty && $fields == 'all')
        {
            foreach ($this->fields as $champ => $valeur)
            {
                $form->addField ($valeur->getHtmlField ());
            }
        }
        else if (!$empty && is_array ($fields))
        {
            // ex : array("nom", "prenom")
            foreach ($fields as $value)
            {
                $form->addField($this->fields[$value]->getHtmlField());
            }
        }

        $form->displayLabels (true);
        $form->loadDataFromArray ($datas);

        return $form;
    }

    /**
     * Génere un champ d'insertion en HTML.
     */
    public function generateInsertForm ($name = '', $label = '')
    {
        $form = $this->generateFormFor ('insert', $name, $label);
        $form->addField ($this->getContextField ());
        $form->addData ('type', 'smart-input');
        return $form;
    }

    /**
     * Génere un champ de mise à jour en HTML.
     */
    public function generateUpdateForm ($name = '', $label = '', $fields='all')
    {
        $form = $this->generateFormFor ('update', $name, $label, $fields);
        $keysList = $this->getDefinedPrimaryFields ();
        $destination = new Input ('ids', 'hidden');
        $destination->setValue (arr2param ($keysList, ','));

        $form->addField ($this->getContextField ());
        $form->addField ($destination);

        return $form;
    }

    /**
     * Génere un champ de supression en HTML.
     */
    public function generateDeleteForm ($name = '', $label = '')
    {

        $form = $this->generateFormFor ('delete', $name, $label, 'all', true);

        $keysList = $this->getDefinedPrimaryFields ();
        $destination = new Input ('ids', 'hidden');
        $destination->setValue (arr2param ($keysList, ','));
        $form->addField ($destination);

        return $form;
    }

    /**
     * Génere une url de supression en HTML.
     */
    public function generateDeleteLink ($ajax = False)
    {
        $aj = '';
        if ( $ajax )
        {
            $aj = '&ajax=true';
        }
        $keysList = $this->getDefinedPrimaryFields ();
        $href = Core::opts ()->system->siteroot
                . 'index.php?moon-action=delete&target='
                . strtolower ($this->table) . $aj . '&ids=' . arr2param ($keysList, ',');
        return $href;
    }

    /**
     * will apply defined filters to the fields to 
     * insert / update.
     */
    protected function applyDefinedFilters ($data)
    {
        $rules = $this->getRulesForForm ($_GET['formName']);

        if ( $rules !== false )
        {
            if ( isset ($rules['form']) )
                unset ($rules['form']);
            $filteredData = $data;

            foreach ($data as $key => $field)
            {
                if ( array_key_exists ($key, $rules) )
                {
                    if ( array_key_exists ('filter', $rules[$key]) )
                    {
                        $toApply = $rules[$key]['filter'];
                        $filter = new Filter ($field, $toApply);
                        $filteredData[$key] = $filter->execute ();
                    }
                }
            }
            return $filteredData;
        }
        else
            return $data;
    }

    protected function getRulesForForm ($formName)
    {
        $return = false;
        $rules = Core::getFormDefinitionArray ();
        if ( array_key_exists ($formName, $rules) )
            $return = $rules[$formName];
        else
            return false;
        return $return;
    }

    /**
     * Retire de la data fournie en parametre
     * tout ce qui n'est pas une donnée de la classe.
     * Cette méthode renvoie donc un tableau constitué
     * seuelement des champs appartenant a la classe.
     */
    protected function parseDataForAction ($data)
    {
        $results = array ();
        $fieldsList = $this->getFieldsList ();
        foreach ($data as $key => $value)
        {
            if ( in_array ($key, $fieldsList) )
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
    public function processInsertForm ($data = array ())
    {
        if ( $this->validateInsertForm ($data) and $this->happyFields->check () )
        {
            $data = $this->applyDefinedFilters ($data);
            $fields = $this->parseDataForAction ($data);
            $result = Core::getBdd ()->insert ($fields, $this->table);
            if ( $result !== false )
            {
                $data['moon_id'] = $result;
                return $this->insertCallback ($data);
            }
            else
            {
                echo 'Echec lors de l\'insertion... ';
                echo 'Peut etre que l\'entrée existe déja ?';
                return false;
            }
        }
        else /** @TODO : Gestion des messages d'erreur */
        {
            var_dump ($this->happyFields);
            var_dump ($this->happyFields->getRulesErrors ());
            echo '<span style="color: red">rules NOT validated !</span>';
            return false;
        }
    }

    /**
     * Procède à la supression de la data fournie en parametre
     * dans la base de données.
     */
    public function processDeleteForm ($data = array ())
    {
        if ( $this->beforeDelete( $data ))
        {
        $fields = $this->parseDataForAction ($data);
        if ( Core::getBdd ()->delete ($this->table, $fields) )
        {
            return $this->deleteCallback($data);
        }
        else
            return false;
        }
        return false;
    }

    /**
     * Procède à la mise à jour de la data fournie en parametre
     * dans la base de données.
     */
    public function processUpdateForm ($data = array ())
    {
        if ( $this->validateUpdateForm ($data) and $this->happyFields->check () 
                and $this->beforeUpdate( $data ))
        {
            $data = $this->applyDefinedFilters ($data);
            $fields = $this->parseDataForAction ($data);

            if ( Core::getBdd ()->update (
                            $fields, $this->table, $this->getDefinedPrimaryFields ()) )
            {
                return $this->updateCallback ($data);
            }
            else
            {
                echo 'Echec lors de la mise à jour...';
                return false;
            }
        }
        else /** @TODO : Gestion des messages d'erreur */
        {
            var_dump ($this->validateUpdateForm ($data));
            var_dump ($this->happyFields->check ());
            var_dump ($this->happyFields->getRulesErrors ());
            echo '<span style="color: red">rules NOT validated !</span>';
            return false;
        }
    }

    protected function updateCallback ($data)
    {
        return True;
    }

    protected function insertCallback ($data)
    {
        return True;
    }

    protected function deleteCallback ($data)
    {
        return True;
    }

    protected function beforeUpdate ($data)
    {
        return True;
    }

    protected function beforeInsert ($data)
    {
        return True;
    }

    protected function beforeDelete ($data)
    {
        return True;
    }

    /**
     * Will search in the form files for a rules set
     * corresponding to the given $formName.
     * If the file exists, it apply the rules.
     * @return boolean true if found, false otherwise
     *
     * @TODO : Introduire la notion de domaine dans le Core.
     * Par exemple, le domain de Profile/Project est [Profile]
     * et doit etre set dans le Controlleur.
     */
    protected function searchForDefinedRules ($formName)
    {
        $return = false;
        $rules = $this->getRulesForForm ($formName);
        if ( $rules !== false )
        {
            if ( isset ($rules['form']) )
                unset ($rules['form']);
            $this->happyFields->clearRules ()->loadRulesFromArray ($rules);
            $return = true;
        }

        return $return;
    }

    public function initProcess ($data = array ())
    {
        $this->happyFields->setFields ($data);
        if ( isset ($_GET['formName']) )
            $rulesExists = $this->searchForDefinedRules ($_GET['formName']);
        else
            $rulesExists = false;
        return $rulesExists;
    }

    /**
     * Will return a hidden field wich will
     * contain the current context.
     * @return Input the hidden field.
     */
    protected function getContextField ()
    {
        $context = new Input ('moon-context', 'hidden');
        $context->setValue (Core::getContext ());
        return $context;
    }

    /*     * *******************************************************
     *                  Database CRUD methods                *
     * ******************************************************* */

    public function insert ()
    {
        return Core::getBdd ()->insertEntity ($this);
    }

    /*     * *******************************************************
     * All the user functions that must be redefined :       *
     * ******************************************************* */

    /**
     * This method is called before each insertion
     * in the database.
     * It checks if the data supplied is correct.
     * Innitially, there is no verifications.
     * The developper have to redefine this method.
     * @param array data the data to check.
     * The data is an array like this :
     * $data [ field name ]  => field value
     *
     * @return boolean true if success, false otherwise.
     */
    public function validateInsertForm ($data)
    {
        return true;
    }

    /**
     * This method is called before each update
     * in the database.
     * It checks if the data supplied is correct.
     * Innitially, there is no verifications.
     * The developper have to redefine this method.
     * @param array data the data to check.
     * The data is an array like this :
     * $data [ field name ]  => field value
     *
     * @return boolean true if success, false otherwise.
     */
    public function validateUpdateForm ($data)
    {
        return true;
    }

    // End of Entity class //
}

?>
