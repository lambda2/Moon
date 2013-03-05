<?php

class Editable {

    private $fields = array();
    private $buttons = array();
    private $tableName;
    private $classe;
    private $display;
    private $insertBL;

    public function __construct($classe, $tableName = "", $display="inline") {
        $this->classe = $classe;
        if ($tableName == "")
            $this->tableName = strtolower(get_class($this->classe));
        else
            $this->tableName = $tableName;
        
        $this->display = $display;
        
        $this->generateLegend();
        $this->generateButtons();
    }
    
    private function generateButtons(){
        $insertButton = new Bouton($this->insertBL, "insert", 'insert' . $this->tableName, $this->tableName);
        $this->buttons[] = $insertButton;
    }
    
    private function generateLegend(){
        $createLeg = 'Ajouter';
        $updateLeg = 'Mettre a jour';
        $readLeg = 'Voir';
        $deleteLeg = 'Supprimer';
        $linker = 'le';
        
        $name = Entity::getProperName($this->tableName);
        if(substr($name, -1) == 's'){
            $linker = 'les';
        }
        else if(substr($name, -1) == 'e'){
            $linker = 'la';
        }
        
        $this->insertBL = $createLeg.' '.$linker.' '.$name;
    }

    public function getFields() {
        return $this->fields;
    }



        
    public function setFields($fields) {
        $this->fields = $fields;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    public function getClasse() {
        return $this->classe;
    }

    public function setClasse($classe) {
        $this->classe = $classe;
    }

    public function add($field) {
        $this->fields[] = $field;
        $field->setClass($this->classe);
        $field->setTable($this->tableName);
    }

    public function getDisplay() {
        return $this->display;
    }

    public function setDisplay($display) {
        $this->display = $display;
    }

        
    public function reset() {
        $this->fields = array();
    }
    
    private function getFormClass(){
        $r = "";
        switch ($this->display) {
            case "inline":
                $r = 'class="form-inline"';
                break;
            case "none":
                $r = '';
                break;
            case "block":
                $r = 'class="form-horizontal"';
                break;
            
            default:
                $r = '';
                break;
        }
        return $r;
    }
    
    private function openControlFormTag($label){
        $r = "";
        switch ($this->display) {
            case "inline":
                $r = $label;
                break;
            case "none":
                $r = $label;
                break;
            case "block":
                $r = '<div class="control-group">'.$label.'<div class="controls">';
                break;
            
            default:
                $r = '';
                break;
        }
        return $r;
    }
    
    private function closeControlFormTag(){
        $r = "";
        switch ($this->display) {
            case "inline":
                $r = '';
                break;
            case "none":
                $r = '';
                break;
            case "block":
                $r = '</div></div>';
                break;
            default:
                $r = '';
                break;
        }
        return $r;
    }
    
    private function generateLabel($field){
        $lab = '<label class="control-label form-lw gris-moyen" for="' . $field->getFieldId() . '">' . $field->getFieldPh() . '</label>';
        return $lab;
    }


    public function generateInsertForm($form = true, $label = true, $dataType = "userInfo", $formid = "") {
        $r = "";
        $formClass="";
        
        if ($formid == "") {
            $formid = 'insert' . $this->tableName;
        }
        if ($form)
            $r .= '<form id="' . $formid . '" '.$this->getFormClass().' >';
        foreach ($this->fields as $field) {
            $lab="";
            if ($label)
                $lab = $this->generateLabel ($field);
            $r.= $this->openControlFormTag($lab);
            $r.= $field->genererChampHtml($dataType, $this->getFieldValue($field->nomChamp), "insert");
            $r.= $this->closeControlFormTag();
        }
        $r.= '<div class="control-group form_lw"><div class="controls">';
        $r.= $this->getButton('insert')->generateHtml();
        $r.= ' </div></div>';
        if ($form)
            $r .= '</form>';

        return $r;
    }

    public function generateUpdateForm($form = true, $label = true, $dataType = "userInfo") {
        $r = "";
        if ($form)
            $r .= '<form class="form-horizontal">';
        foreach ($this->fields as $field) {
            $r.= '<div class="control-group">';
            if ($label)
                $r.= '<label class="control-label gris-moyen" for="' . $field->getFieldId() . '">' . $field->getFieldPh() . '</label>';
            $r.= '<div class="controls">' . $field->genererChampHtml($dataType, $this->getFieldValue($field->nomChamp));
            $r.= '';
            $r.= '</div></div>';
        }
        $r.= '<div class="control-group"><div class="controls">
            <button class="btn button"><i class="icon-arrow-left"></i> Retour</button>
            </div></div>';
        if ($form)
            $r .= '</form>';

        return $r;
    }

    public function getFieldValue($field) {
        if ($this->classe->get($field) == null)
            return "";
        else
            return $this->classe->get($field);
    }

    public function __toString() {
        $s.= "<p><b>Formulaire de la classe :</b></p>";
        $s.= '<table class="table table-striped">';
        foreach ($this->fields as $champ) {
            $s .= '<tr><td>' . $champ->nomChamp . '</td><td><b>' . $champ->typeChamp . '</b></td></tr>';
        }
        $s.= '</table>';
        return $s;
    }

    public function getFieldByName($name) {
        try {
            $f = null;
            foreach ($this->fields as $field) {
                if ($field->nomChamp == $name) {
                    $f = $field;
                }
            }
            if ($f == null)
                throw new AlertException('le champ ' . $name . ' n\'a pas été trouvé');
            return $f;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    
       public function getButton($role) {
        try {
            $f = null;
            foreach ($this->buttons as $but) {
                if ($but->getRole() == $role) {
                    $f = $but;
                }
            }
            if ($f == null)
                throw new AlertException('le boutton ' . $role . ' n\'a pas été trouvé');
            return $f;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}

class Bouton {
    private $role;
    private $type;
    private $target;
    private $form;
    private $icon;
    private $label;
    
    public function __construct($label,$role, $form, $target) {
        $this->icon = "icon-save";
        $this->role = $role;
        $this->form = $form;
        $this->target = $target;
        $this->type = "button";
        $this->label = $label;
    }
    
    public function generateHtml(){
        $r = '<button 
            type="'.$this->type.'" 
            data-target="' . $this->target . '"  
            data-form="' . $this->form . '" 
                data-role="' . $this->role . '" class="btn button">
                <i class="icon-' . $this->icon . '"></i> '.$this->label . 
                '</button>';
        return $r;
    }
    
    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
    }
    
    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getTarget() {
        return $this->target;
    }

    public function setTarget($target) {
        $this->target = $target;
    }

    public function getForm() {
        return $this->form;
    }

    public function setForm($form) {
        $this->form = $form;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }
    
    


    
}

class Champ {

    public $nomChamp;
    public $nomBdd;
    public $desc;
    public $typeChamp = "text";
    public $targetChamp = "";
    public $targetTable = "";
    public $displayChamp = "";
    private $targetData = array();
    private $class;
    private $table;

    public function __construct($champ, $nomBdd, $desc = "", $type = "text") {
        $this->nomChamp = $champ;
        $this->nomBdd = $nomBdd;
        if ($desc != "")
            $this->desc = $desc;
        else
            $this->desc = ucfirst($champ);
        $this->typeChamp = $type;
    }

    protected function getProperType($type) {
        echo '<br>on replace ' . $type . ' par ' . preg_replace("#\(([0-9]+)\)#", '', $type);
        return preg_replace("#\(([0-9]+)\)#", '', $type);
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    public function bindFieldTo($target, $display) {

        if ($target != "" && $display != "") {
            $t = explode(".", $target);
            if (count($t) < 2)
                throw new Exception('cible invalide pour le bind du champ ' . $this->nomChamp);
            else {
                $this->targetChamp = $t[1];
                $this->targetTable = $t[0];
            }

            $this->displayChamp = $display;
            $data = new Entities($this->targetTable);
            $this->targetData = $data->getValuesList($this->displayChamp);
        }
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function genererChampHtml($dataType, $value = "", $type = "update") {
        $r = "";
        switch ($this->typeChamp) {
            case "text":
                $r .= $this->genererInputHtml($dataType, $value, $type);
                break;
            case "password":
                $r .= $this->genererInputHtml($dataType, $value, $type);
                break;
            case "select":
                $r .= $this->genererSelectHtml($dataType, $type);
                break;
            case "date":
                $r .= $this->genererInputHtml($dataType, $value, $type);
                break;
            default:
                $r .= $this->genererInputHtml($dataType, $value, $type);
                break;
        }
        return $r;
    }

    public function genererInputHtml($dataType, $value = "", $type = "update") {
        $r = '<input 
            class="gris-moyen form-lw " 
            type="' . $this->typeChamp . '"
            data-type="' . $dataType . '" 
            data-target="' . $this->table . '" 
            data-catr="' . $this->nomChamp . '" 
            id="input' . $this->getFieldId() . '" 
            placeholder="' . $this->getFieldPh() . '" 
            data-field="' . $this->nomBdd . '" 
            value="' . $value . '">';
        if ($type == "update") {
            $r .= ' <button 
            data-role="update" 
            data-butrefer="input' . $this->getFieldId() . '" 
            data-field="' . $this->nomBdd . '" 
            data-type="' . $dataType . '" 
            type="button" 
            class="button btn">
                <i class="icon-ok-circle"></i>
            </button>';
        }
        return $r;
    }

    public function genererSelectHtml($dataType, $type = "update") {



        //var_dump($this);
        $r = '<select 
                    data-type="' . $dataType . '" 
                    data-target="' . $this->table . '" 
                    data-field="' . $this->nomBdd . '" 
                    data-catr="' . $this->nomChamp . '" 
                    class="gris-moyen form-lw " 
                    id="select' . $this->getFieldId() . '">';

        foreach ($this->targetData as $val) {
            if ($this->class->get($this->nomChamp) == $val)
                $r .= '<option value="' . $val . '" selected>' . $val . '</option>';
            else
                $r .= '<option value="' . $val . '">' . $val . '</option>';
        }
        $r .= '</select>';
        if ($type == "update") {
            $r .= '<button 
            data-role="update" 
            data-butrefer="select' . $this->getFieldId() . '" 
            data-field="' . $this->nomBdd . '" 
            data-type="' . $dataType . '" 
            type="button" 
            class="button btn">
                <i class="icon-ok-circle"></i>
            </button>';
        }


        return $r;
    }

    public function getFieldId() {
        return ucfirst(trim($this->nomChamp));
    }

    public function getFieldPh() {
        return ucfirst($this->desc);
    }

}

?>
