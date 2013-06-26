<?php

/**
 * Représente un ensemble d'entitées
 */
class Entities implements Iterator, Countable, JsonSerializable {

    protected $table;
    protected $bdd;

    /* -- Iterator attributes - */
    protected $position = 0;
    protected $entities = array();

    /* ------- Filters -------- */
    protected $filter = null;
    protected $history = null;

    /* ------------------------ */

    public function __construct($table) {
        $this->table = $table;
        $this->history = $table;
        // echo 'entity created ! (table = '.$table.')<br>';
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

    public function addToHistory($hist)
    {
        $this->history = $hist.'.'.$this->table;
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

    protected function applyFilters()
    {
        
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

    public function loadFromDatabase()
    {
        // On récupère toutes les tables demandées
        $tables = $this->getTablesFromHistory();

        // On récupère leur nombre pour la boucle
        $len = count($tables);

        // On met le flag a true, ce qui signifie que on est sur la table courante
        $firstTable = false;
        $lastTable = true;

        // On récupère l'instance de l'ORM
        $orm = Core::getBdd();

        $baseQuery = new Query();
        $nextTable = null;
        $previousTable = null;
        $relation = null;

        // Et on parcours les tables dans le sens inverse
        for($i=0; $i<$len; $i++)
        {
            $q = new Query();
            $originTable = $tables[$i];
            
            if($i > 0)
            {
                $previousTable = $tables[$i-1];
                $lastTable = false;
            }
            if($i < $len-1)
            {
                $nextTable = $tables[$i+1];
            }

            if($i == $len-1)
                $firstTable = true;
            
            // Si on n'est pas sur la derniere table, on suggère qu'il y a 
            // une relation explicite définie entre les deux tables demandées.

            $relation = $this->getGoodRelationBetweenTables($originTable,$previousTable);
            $nrelation = $this->getGoodRelationBetweenTables($originTable,$nextTable);

            if($firstTable)
            {
                $q->select('*')
                ->from($originTable);
                if($len > 1)
                {
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
                        $linker = $this->getGoodFieldFromTableName($nrelation, $originTable);
                    $q->select($linker)->from($originTable);
                    $linker_in = $this->getGoodFieldFromTableName($relation, $originTable);
                    $q->in($linker_in,$baseQuery);
                    $baseQuery = $q;
                }
                else
                {
                    $linker = $this->getGoodFieldFromTableName($relation, $originTable);
                    if($linker == '')
                        $linker = $this->getGoodFieldFromTableName($nrelation, $originTable);

                    $baseQuery->select($linker)->from($originTable);
                }
            }
            
        }
        return $baseQuery;
    }

    protected function getGoodFieldFromTableName($scheme,$tableName)
    {
        $expScheme = explode('@',$scheme);
        if($tableName == explode('.',$expScheme[0])[0])
        {
            return $expScheme[0];
        }
        else
            return $expScheme[1];
    }

    protected function getTablesFromHistory()
    {
        return explode('.',$this->history);
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

        /* ### old code */
        $nextEntities = array();
        $direct = False;

        /*
         * On regarde si on demande une instance liée directement.
         * On va donc voir quelles tables la notre référence et chercher
         * une correspondance avec la table demandée.
         */
        foreach($this->entities as $entity)
        {
            if($entity->isLinkedClassesLoaded() == false)
            {
                $entity->autoLoadLinkedClasses();
            }

            $i = $this->getExternalTables($name,$entity);
            if ($i != false)
            {
                $nextEntities[] = $i;
                $direct = True;
            }
        }
        if($direct) // Si on a trouvé une correspondance
        {
            // On crée un nouveau lot d'entitées et on le retourne.
            $entities = new Entities($name);
            $entities->addEntityFromArray($nextEntities);
            return $entities;
        }
        else
        {
            /*
             * Sinon, c'est qu'on demande une classe liée indirectement.
             * On va donc récuperer la table qui référence la notre par une clé étrangère.
             */

            // On récupère toute les tables qui nous référencent
            $externals = Core::getBdd()->getMoonLinksFrom($this->table,true,true);

            // Et si on a des résultats
            if(!isNull($externals))
            {
                foreach ($externals as $moonLinkKey => $moonLinkValue)
                {
                    // Et qu'un des résultats est la table que nous voulons
                    if($moonLinkValue->table == $name)
                    {
                        // On crée un paquet d'entitiées vides
                        $nextEntities = new Entities($moonLinkValue->table);
                        foreach ($this->entities as $entity)
                        {
                            // Et on ajoute a ce paquet chaque entitée qu'on arrive a créer.
                            /* @TODO : Ci dessous, une aberration. Refactoring necessaire. */
                            $res = EntityLoader::getClass($moonLinkValue->table);
                            if($res != null)
                            {
                                $nextEntities->addEntitiesObject(EntityLoader::loadAllEntitiesBy(
                                        $moonLinkValue->table,
                                        $moonLinkValue->attribute,
                                        $entity->getFields()[$moonLinkValue->destinationColumn]->getValue(),
                                        $this->filter));
                            }
                        }
                        return $nextEntities; // On le renvoie !
                    }
                }
            }
        }
        // Si on ne référence pas la table demandée, et qu'aucune table ne nous référence
        // On renvoie null.
        return null;
    }

    /**
     * Charge tous les objets en fonction d'un ou plusieurs parametres
     * et renvoie l'ensemble sous forme de tableau.
     */
    public static function loadAllBy($classe,$field, $value) {
        $c = self::getClass($classe);
        $request = "";

        /**
         * Dans le cas ou on a plusieurs champs contraints (plusieurs clés primaires
         * par exemple...) on ajoute les contraintes dans la requete.
         */
        if(is_array($field)
            and is_array($value)
            and count($field) == count($value))
        {
            $request = "SELECT * FROM {$c->getTable()} WHERE ";
            $args = array();
            foreach ($field as $key=>$cle)
            {
                $args[] = $cle." = ".$value[$key];
            }
            $request .= implode(' AND ', $args);
        }
        else
        {
            $request = "SELECT * FROM {$c->getTable()} WHERE {$field} = '{$value}'";
        }

        try
        {
            $Req = Core::getBdd()->getDb()->prepare($request);
            $Req->execute(array());
        }
        catch (Exception $e) //interception de l'erreur
        {
            throw new OrmException("Error Processing Request");
        }
        $t = array();
        // Si on récupère quelque chose :
        if ($Req->rowCount() != 0)
        {
            while ($res = $Req->fetch(PDO::FETCH_OBJ))
            {
                $f          = self::getClass($classe);
                $pri        = $f->getValuedPrimaryFields($res);
                if (!isNull($pri))
                {
                    $f->loadByArray($pri);
                    $f->autoLoadLinkedClasses();
                    $t[] = $f;
                }

            }
        }
        return $t;
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

    /* -------- Database query filtering methods -------*/

    public function filter($filter)
    {
        $res = array();
        $regex = "/\[([\w\.]*)+(\=|!\=|<|>|<\=|>\=)?(([\d]*)|(\"[\w\.]*\"))\]/";
        $result = preg_match_all($regex,$filter,$res);
        if($result > 0 and isset($res[0]))
        {
            foreach($res[0] as $match)
            {
                $this->digestConstraint($match);
            }
        }
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
        var_dump($element);
        var_dump($value);
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
        $this->position = 0;
    }

    public function current() {
        return $this->entities[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->entities[$this->position]);
    }

    public function count()
    {
        return count($this->entities);
    }


}

?>
