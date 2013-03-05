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
    
    public function getAccessRulesFromConfigFile(){
        $yaml = "Fail to get access rules";
        try {
            $yaml = Spyc::YAMLLoad('Config/access.yml');
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $yaml;
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

    public function setMinRoleFor($action = "read", $role = 10) {
        if (isset($this->accessRules[$action])) {
            $this->accessRules[$action] = $role;
        } else {
            throw new Exception("L'action {$action} n'a pas été définie");
        }
    }

    public function getMinRoleFor($action = "read") {
        if (isset($this->accessRules[$action])) {
            return $this->accessRules[$action];
        } else {
            throw new Exception("L'action {$action} n'a pas été définie");
        }
    }

    public function addRoleForAction($action, $role = 10) {
        $this->accessRules[$action] = $role;
    }

    public static function haveDefinedRoles($table) {
        $bdd = Configuration::getBdd();
        $t = array();
        try {
            $Req = $bdd->prepare('
                SELECT can_create,can_update,can_delete,can_read 
                FROM access' . Configuration::getInstance()->getDbPrefix() . '
                WHERE class = ?');
            $o = strtolower($table);
            $Req->execute(array($o));
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $t = $res;
        }
        if (array_key_exists('can_create', $t) &&
                array_key_exists('can_update', $t) &&
                array_key_exists('can_delete', $t) &&
                array_key_exists('can_read', $t)) {
            return true;
        }
        else
            return false;
    }

    public function loadFromTable($table) {
        if (self::haveDefinedRoles($table)) {
            $bdd = Configuration::getBdd();
            $t = array();
            try {
                $Req = $bdd->prepare('
                SELECT can_create,can_update,can_delete,can_read 
                FROM access' . Configuration::getInstance()->getDbPrefix() . '
                WHERE class = ?');
                $o = strtolower($table);
                $Req->execute(array($o));
            } catch (Exception $e) { //interception de l'erreur
                die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
            }
            while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
                $this->setMinRoleForCreate($res->can_create);
                $this->setMinRoleForRead($res->can_read);
                $this->setMinRoleForUpdate($res->can_update);
                $this->setMinRoleForDelete($res->can_delete);
            }
            return true;
        }
        else
            return false;
    }

    public function __toString() {
        $s.= '<table class="table table-striped">';
        foreach ($this->accessRules as $key => $value) {
            $s.= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
        }
        $s.= '</table>';
        return $s;
    }

    public function isAccessGranted($action, $role) {
        $granted = false;
        switch ($action) {
            case 'c':
                if (intval($this->getMinRoleFor('create')) >= (intval($role)))
                    $granted = true;
                break;
            case 'r':
                if (intval($this->getMinRoleFor('read')) >= (intval($role)))
                    $granted = true;
                break;
            case 'u':
                if (intval($this->getMinRoleFor('update')) >= (intval($role)))
                    $granted = true;
                break;
            case 'd':
                if (intval($this->getMinRoleFor('delete')) >= (intval($role))) {
                    $granted = true;
                }
                break;
            default:
                if (array_key_exists($action, $this->accessRules)) {
                    if (intval($this->getMinRoleFor($action)) >= (intval($role))) {
                        $granted = true;
                    }
                }
                break;
        }
        return $granted;
    }

}

?>
