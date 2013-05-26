<?php

/**
 * Représente un ensemble d'entitées
 */
class Entities implements Iterator, Countable {

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
     * Surcharge de la méthode magique __get().
     * On va d'abord regarder si l'attribut demandé est une classe liée.
     * On délegue ensuite a la méthode __call()
     */
    public function __get($name) {

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
                                $nextEntities = Entity::loadAllEntitiesBy(
                                        $moonLinkValue->table,
                                        $moonLinkValue->attribute,
                                        $entity->getFields()[$moonLinkValue->destinationColumn]->getValue());
                            }
                        }
                        return $nextEntities; // On le renvoie !
                    }
                }
            }
        }
        // Si on ne référence pas la table demandée, et qu'aucune table ne nous référence
        // On renvoie faux.
        return false;
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
