<?php

/**
 * Représente un ensemble d'entitées
 */
class Entities implements Iterator, Countable, JsonSerializable {

    protected $table;
    protected $bdd;
    protected $loaded = false;

    /* -- Iterator attributes - */
    protected $position = 0;
    protected $entities = array();

    /* ------- Filters -------- */
    protected $filter = null;
    protected $history = null;

    /* ------------------------ */

    public function __construct($table) {
        $this->table = preg_replace(Entities::getFilter(),'',$table);
        $this->history = $table;
        $this->bdd = Core::getBdd()->getDb();
        $this->filter = new QueryFilter();
    }

    public function __toString()
    {
        return "Entities@".$this->table.'('.count($this->entities).')';
    }

    public function __invoke($name)
    {
        echo 'trying to invoke '.$name.' !!<br>';
    }

    /**
     * Will add the given entity to the array of entities
     * @throw AlertException if the given object isn't an entity
     */
    public function addEntity($entity)
    {
        if(!is_a($entity,'Entity'))
            throw new AlertException('Invalid Entity supplied : '.$entity);
        $this->entities[] = $entity;
    }

    /**
     * Will load all the entities in the given array in the
     * entity array of the current Entities Object
     * @throw AlertException if the given object isn't an entity array
     */
     public function addEntityFromArray($entityArray)
     {
        foreach($entityArray as $entity)
        {
            $this->addEntity($entity);
        }
     }

    /**
     * Va voir si l'objet courant a une relation avec la table spécifiée.
     * Si oui, va renvoyer l'instance de l'objet référencé.
     * @param type $table la table ciblée
     * @return mixed l'instance de la classe distante, false sinon
     */
    public function getExternalTables($table, $entity) {
        foreach ($entity->getLinkedClasses() as $key => $value) {
            if(strcasecmp($value->destinationTable,$table) == 0)
            {
                if (!isNull($value->instance))
                    return $value->instance;
            }
        }
        return false;
    }

    /**
     * Return the entity objects contained in
     * the current object.
     * @return array an array of the entities in the object.
     */
    public function getEntities()
    {
        return $this->entities;
    }

    public function addEntitiesObject($entities)
    {
        foreach($entities->getEntities() as $entity)
        {
            $this->entities[] = $entity;
        }
    }

    /**
     * Add the given string to the history.
     * @param string $hist the history to add.
     */
    public function addToHistory($hist)
    {
        $this->history = $hist.'.'.$this->table;
    }

    /**
     * @return String the current object history
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Remove all previously added history from entities.
     * @return Entities the current instance
     */
    public function clearHistory()
    {
        $this->history = $table;
        return ($this);
    }

    /**
     * Explain how the php engine have to serialize an
     * Entities object to json.
     */
    public function jsonSerialize() {
        $ret = array();
        foreach($this->entities as $entity)
        {
            $ret[] = $entity->jsonSerialize();
        }
        return $ret;
    }

    /**
     * Va rechercher les relation directes ou indirectes entre les deux tables
     * fournies en parametre, et retourner la relation la plus pertinente.
     */
    protected function getGoodRelationBetweenTables($origin, $destination)
    {
        $relation = Core::getBdd()->getAllRelationsBetween($origin,$destination);
        if(count($relation) == 1)
        {
           return $relation[0];
        }
        else
            return null;
    }

    /**
     * Will load all the entitiy object corresponding to the history query
     * from the database only if they are not already loaded.
     *
     * @see Entities#loadFromDatabase
     */
    public function loadIfNotLoadedFromDatabase()
    {
        if(!$this->loaded)
        {
            $this->loadFromDatabase();
        }
    }

    /**
     * Will load all the entitiy object corresponding to the history query
     * from the database, even if they are already loaded.
     * Use with caution, because consumes a lot of ressources.
     * The [loadIfNotLoadedFromDatabase] method can load the entities
     * from the dtabase only if they are not loaded.
     *
     * @return true if all's right.
     * @see Entities#loadIfNotLoadedFromDatabase
     */
    public function loadFromDatabase()
    {
        $query = $this->generateQueryFromHistory();

        // On récupère l'instance de l'ORM
        $this->loaded = true;
        $orm = Core::getBdd();
        $orm->setQuery($query);
        $this->addEntitiesObject($orm->fetchEntities());
        return true;
    }

    /**
     * Will transform the history to a single SQL query.
     * For example, the history 'team_member.team.project' will be
     * converted to :
     * -------------------------------------------------------------------
     * | SELECT * FROM project WHERE project.id_team IN (
     * |  SELECT team.id_team FROM team WHERE team.id_team IN (
     * |   SELECT team_member.id_team FROM team_member
     * |  )
     * | )
     * ------------------------------------------------------------------
     *
     * @return Query the query object corresponding to the generated request.
     * @since v0.5
     */
    public function generateQueryFromHistory()
    {
        // On récupère toutes les tables demandées
        $tables = $this->getTablesFromHistory();

        // On récupère leur nombre pour la boucle
        $len = count($tables);

        // On met le flag a true, ce qui signifie que on est sur la table courante
        $firstTable = false;
        $lastTable = true;

        $baseQuery = new Query();
        $nextTable = null;
        $previousTable = null;
        $relation = null;

        // Et on parcours les tables dans le sens inverse
        for($i=0; $i<$len; $i++)
        {
            $q = new Query();
            $originTable = $tables[$i]['table'];
            $originConstraints = $tables[$i]['constraints'];

            $q->convertConstraint($originConstraints);
            if($i > 0)
            {
                $previousTable = $tables[$i-1]['table'];
                $lastTable = false;
            }
            if($i < $len-1)
            {
                $nextTable = $tables[$i+1]['table'];
            }

            if($i == $len-1)
                $firstTable = true;

            // Si on n'est pas sur la derniere table, on suggère qu'il y a
            // une relation explicite définie entre les deux tables demandées.

            $relation = $this->getGoodRelationBetweenTables($originTable,$previousTable);
            $nrelation = $this->getGoodRelationBetweenTables($originTable,$nextTable);

            // If we are on the first table (the asked table)
            if($firstTable)
            {
                // We select all the fields
                $q->select('*')
                ->from($originTable);
                if($len > 1)
                {
                    // An we link ourself to the other queries
                    $linker = $this->getGoodFieldFromTableName($relation, $originTable);
                    $q->in($linker,$baseQuery);
                }
                $baseQuery = $q;
            }
            else
            {
                if(!$lastTable)
                {
                    $linker = $this->getGoodFieldFromTableName($nrelation, $originTable);
                    if($linker == '')
                        $linker = $this->getGoodFieldFromTableName($relation, $originTable);
                    $q->select($linker)->from($originTable);
                    $linker_in = $this->getGoodFieldFromTableName($relation, $originTable);
                    if($linker_in == '')
                        $linker_in = $this->getGoodFieldFromTableName($nrelation, $originTable);
                    $q->in($linker_in,$baseQuery);
                    $baseQuery = $q;
                }
                else
                {
                    // If we are on the last table, we juste have to select the next
                    // query field, without any linked query.
                    $linker = $this->getGoodFieldFromTableName($relation, $originTable);
                    if($linker == '')
                        $linker = $this->getGoodFieldFromTableName($nrelation, $originTable);

                    $q->select($linker)->from($originTable);
                    $baseQuery = $q;
                }
            }

        }
        return $baseQuery;
    }

    /**
     * Will return the good relation field for the given tablename
     * and scheme.
     * @param $scheme String the scheme to read, like 'user.id_role@role.id_role'
     * @param $tableName String the name of the table to search, like 'user'
     * @return String the good relation, like 'role.id_role'
     */
    protected function getGoodFieldFromTableName($scheme,$tableName)
    {
        if(isNull($scheme))
            return null;

        $expScheme = explode('@',$scheme);
        if($tableName == explode('.',$expScheme[0])[0])
        {
            return $expScheme[0];
        }
        else
        {
            return $expScheme[1];
        }
    }

    /**
     * Will return the good table names from the history string
     * @return Array an array containing the table names.
     */
    protected function getTablesFromHistory()
    {

        $tbls = explode('.',$this->history);
        $res = array();
        foreach($tbls as $tb)
        {
            $res[] = array(
                'table' => $this->clearAttributesFromHistory($tb),
                'constraints' => $tb
            );
        }
        return $res;
    }

    /**
     * Return the table with the given name.
     * Use this method when there is a ambiguity between
     * a table name and an attribute (same name) to explicitely
     * ask for the table object, so the linked Entities.
     * @param String $name the name of the table to return.
     * @return Entities the corresponding entities.
     */
    public function table($name)
    {
        $c = EntityLoader::getClass($name);
        $next = new Entities($c->getTable());
        $next->addToHistory($this->history);
        return $next;
    }

    /**
     * Surcharge de la méthode magique __get().
     * On va d'abord regarder si l'attribut demandé est une classe liée.
     * On délegue ensuite a la méthode __call()
     */
    public function __get($name) {

        /**
         * On regarde si c'est pas un attribut et non une table,
         * et si c'est le cas, on renvoie un tableau contenant la
         * valeur de cet attribut pour chaque élément.
         */
        $efields = EntityLoader::getClass($this->table)->getFields();
        if(array_key_exists($name,$efields))
        {
            $this->loadIfNotLoadedFromDatabase();
            $return = array();
            foreach($this->entities as $entity)
            {
                if($entity->getFields()[$name] != null)
                    $return[] = $entity->getFields()[$name]->getValue();
            }
            return $return;
        }
        else
        {
            return $this->table($name);
        }
    }

    /**
     * Implémentation nécessaire pour que twig accepte d'apeller __get()
     * @param type $name
     * @return boolean
     */
    public function __isset($name) {
        try {
            $e = $this->table;
        } catch (Exception $exc) {
            dbg($exc->getMessage(), 20);
            return false;
        }
        return true;
    }

    /*
     *  ------------------------------------------------
     *  |   Real database query filtering methods      |
     *  ------------------------------------------------
     */

    /**
     * Defines a limit of items for the result set.
     * @param $limit integer the limit to set
     * @return $this Entities self
     */
    public function setLimit($limit)
    {
        $this->filter->setLimit($limit);
        return $this;
    }

    /**
     * Defines an offset to begin for the result set.
     * @param $offset integer the offset to set
     * @return $this Entities self
     */
    public function setOffset($offset)
    {
        $this->filter->setOffset($offset);
        return $this;
    }

    /**
     * Defines an order to sort the result set.
     * @param $columns String the column(s) to sort by
     * @param $sort String the direction of sort (ASC or DESC)
     * @return $this Entities self
     * @see Entitites#setOrderSort
     */
    public function setOrder($columns,$sort=null)
    {
        $this->filter->setOrder($columns,$sort);
        return $this;
    }

    /**
     * Defines a direction to sort the result set.
     * @param $sort String the direction of sort (ASC or DESC)
     * @return $this Entities self
     * @see Entitites#setOrder
     */
    public function setOrderSort($sort)
    {
        $this->filter->setOrderSort($sort);
        return $this;
    }

    /**
     * All the results will be distinct on the given column name.
     * @param $column String the columns who have to be distinct.
     * @return $this Entities self
     */
    public function distinct($column=null)
    {
        $this->filter->distinct($column);
        return $this;
    }

    public function escapeString($string)
    {
        $escapes = array('.','[',']');
        $repl = array('#dot#','#obra#','#ebra');
            $string = str_replace($escapes,$repl,$string);
        return $string;
    }

    public function where($field,$value,$operator='=')
    {
        $this->history .= '['.$field.$operator.'"'.$this->escapeString($value).'"]';
        return $this;
    }

    /* -------- Database query filtering methods -------*/

    public static function getFilter()
    {
        return "/(?P<operand>\+)?\[(?P<attribute>([A-Za-z_]*))(\s)?(?P<operator>\=|!\=|is|<|>|<\=|>\=)(\s)?(?P<value>(?P<num>[\d]*)|(?P<expr>[\w]*(\([^\.\]\[]*\))?)|\"(?P<text>[^\.\]\[]*)\")\]/";
    }

    public function clearAttributesFromHistory($str)
    {
        return preg_replace($this->getFilter(),'',$str);
    }

    public function getAttributesFromString($str)
    {
        $res = array();
        $result = preg_match_all($this->getFilter(),$str,$res);
        return $res;
    }

    public function filter($filter)
    {
        $this->history .= $filter;
        /*
        $res = $this->getAttributesFromString($filter);
        if(isset($res[0]))
        {
            foreach($res[0] as $match)
            {
                $this->digestConstraint($match);
            }
        }
        */
    }

    /**
     * Juste affreux. Besoin d'une ame charitable pour trouver
     * une regex correcte qui va me permettre d'éviter ce bain
     * de sang. Cordialement.
     * @Todo : To Do It !
     *
     * Will transform a contraint (ie: "[id>=3]" ) into a filter
     * and will execute it on the current Entities.
     */
    protected function digestConstraint($constraint)
    {
        $constraint = $this->cleanConstraint($constraint);
        if(substr_count($constraint,'!='))
        {
            $matches = explode('!=',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a != $b; });
        }
        else if(substr_count($constraint,'=='))
        {
            $matches = explode('==',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a == $b; });
        }
        else if(substr_count($constraint,'>='))
        {
            $matches = explode('>=',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a >= $b; });
        }
        else if(substr_count($constraint,'<='))
        {
            $matches = explode('<=',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a <= $b; });
        }
        else if(substr_count($constraint,'>'))
        {
            $matches = explode('>',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a > $b; });
        }
        else if(substr_count($constraint,'<'))
        {
            $matches = explode('<',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a < $b; });
        }
        else if(substr_count($constraint,'='))
        {
            echo " [=] -> $constraint<br>";
            $matches = explode('=',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a == $b; });
        }
        else if(substr_count($constraint,' '))
        {
            $matches = explode(' ',$constraint);
            $this->removeFromArrayWhere($matches[0],$matches[1],
                function($a,$b){ return $a == $b; });
        }
        else
        {
            $matches = $constraint;
            $this->removeFromArrayWhere($matches,null,
                function($a,$b){ return $a != $b; });
        }



    }

    protected function cleanConstraint($constraint)
    {
        $withoutBrace = str_replace(
            '[','',str_replace(
                ']','',str_replace(
                    '"','',$constraint)));
        return $withoutBrace;
    }

    protected function removeFromArrayWhere($element,$value,$predicate)
    {
        $locationMarker = 0;
        if($value == "null")
            $value = null;
        foreach($this->entities as $entity)
        {
            $evalue = $this->getEntityValue($entity,explode('.',$element));
            if(!$predicate($evalue,$value))
            {
               unset($this->entities[$locationMarker]);
               $this->entities = array_values($this->entities);
               $locationMarker--;
            }
            $locationMarker ++;
        }
    }

    protected function getEntityValue($entity,$val)
    {
        $val = array_values($val);
        if(is_array($val) and count($val) > 1)
        {
            $value = $val[0];
            unset($val[0]);
            $val = array_values($val);
            $entity = $entity->table($value);
            return $this->getEntityValue($entity,$val);
        }
        else
        {
            if(is_array($val))
            {
                $val = array_values($val);
                $value = $val[0];
            }
            if(is_a($entity,'Entity') or is_a($entity,'Entities'))
            {
                return $entity->__get($value);
            }
            else
            {
                return $entity;
            }
        }
    }


    /* -------------- Iterator methods ---------------- */

    public function rewind() {
        $this->loadIfNotLoadedFromDatabase();
        $this->position = 0;
    }

    public function current() {
        $this->loadIfNotLoadedFromDatabase();
        return $this->entities[$this->position];
    }

    public function key() {
        $this->loadIfNotLoadedFromDatabase();
        return $this->position;
    }

    public function next() {
        $this->loadIfNotLoadedFromDatabase();
        ++$this->position;
    }

    public function valid() {
        $this->loadIfNotLoadedFromDatabase();
        return isset($this->entities[$this->position]);
    }

    public function count()
    {
        $this->loadIfNotLoadedFromDatabase();
        return count($this->entities);
    }
}

?>
