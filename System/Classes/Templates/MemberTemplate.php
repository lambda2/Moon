<?php


class MemberTemplate extends Entity {
    
    
    public function __construct($table = 'membre_lw') {


        $this->table = $table;
        $this->editable = new Editable($this, $this->table);
        $this->bdd = Core::getInstance()->bdd();
        $this->access = new Access();

        if (get_class($this) != 'TableEntity') {
            $this->generateProperties();
            $this->generateFields();
        }
    }
    


}

?>
