<?php

class Access {

    protected $accessRules = array();

    public function __construct($acces = 1) {
        if (is_array($acces)) {
            $this->initialize();
            $this->accessRules[] = $acces;
        } elseif (is_int($acces)) {
            $this->setMinRoleForCrud($acces);
        } else {
            $this->initialize(true);
        }
    }

    private function initialize() {
        $this->accessRules['read'] = 10;
        $this->accessRules['create'] = 9;
        $this->accessRules['update'] = 9;
        $this->accessRules['delete'] = 9;
    }

    public function setMinRoleForCrud($role = 9) {
        $this->accessRules['read'] = $role;
        $this->accessRules['create'] = $role;
        $this->accessRules['update'] = $role;
        $this->accessRules['delete'] = $role;
    }

    public function setMinRoleForRead($role = 10) {
        $this->accessRules['read'] = $role;
    }

    public function setMinRoleForCreate($role = 9) {
        $this->accessRules['create'] = $role;
    }
    
    public function setMinRoleForUpdate($role = 9) {
        $this->accessRules['update'] = $role;
    }
    
    public function setMinRoleForDelete($role = 9) {
        $this->accessRules['delete'] = $role;
    }
    
    public function setMinRoleFor($action = "read",$role=10){
        if(isset($this->accessRules[$action])){
            $this->accessRules[$action] = $role;
        }
        else {
            throw new Exception("L'action {$action} n'a pas été définie");
        }
    }
    
    public function getMinRoleFor($action = "read"){
        if(isset($this->accessRules[$action])){
            return $this->accessRules[$action];
        }
        else {
            throw new Exception("L'action {$action} n'a pas été définie");
        }
    }
    
    public function addRoleForAction($action, $role=10){
        $this->accessRules[$action] = $role;
    }
    
    public function __toString() {
        $s = ' --------------------- <br>';
        $s.= '| Droits de la page :  <br>';
        foreach ($this->accessRules as $key => $value) {
            $s.= '| '.$key.'  :  '.$value.'  <br>';
        }
        $s.= ' --------------------- <br>';
        return $s;
    }

}

?>
