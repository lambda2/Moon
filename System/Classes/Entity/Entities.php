<?php

/**
 * Représente un ensemble d'entitées
 */
class Entities implements Iterator, Countable, JsonSerializable {

    protected $table;
    protected $bdd;

    /* --- Iterator attributes --- */
    protected $position = 0;
    protected $entities = array();


    public function __construct($table) {
        $this->table = $table;
        // echo 'entity created ! (table = '.$table.')<br>';
        $this->bdd = Core::getBdd()->getDb();
    }

    /**
     * @deprecated
     */
   /* public function getValuesList($columnName){
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
    } */

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
     * Surcharge de la méthode magique __get().
     * On va d'abord regarder si l'attribut demandé est une classe liée.
     * On délegue ensuite a la méthode __call()
     */
    public function __get($name) {
        
        if(count($this->entities) == 0)
            return false;
        
        /**
         * On regarde si c'est pas un attribut et non une table,
         * et si c'est le cas, on renvoie un tableau contenant la
         * valeur de cet attribut pour chaque élément.
         */
        $efields = $this->entities[0]->getFields();
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
                            $res = EntityLoader::getClass($moonLinkValue->table);
                            if($res != null)
                            {
                                $nextEntities->addEntitiesObject(Entity::loadAllEntitiesBy(
                                        $moonLinkValue->table,
                                        $moonLinkValue->attribute,
                                        $entity->getFields()[$moonLinkValue->destinationColumn]->getValue()));
                            }
                        }
                        return $nextEntities; // On le renvoie !
                    }
                }
            }
        }
        // Si on ne référence pas la table demandée, et qu'aucune table ne nous référence
        // On renvoie faux.
        return null;
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
