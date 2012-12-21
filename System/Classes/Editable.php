<?php

class Editable {

    private $fields = array();
    private $tableName;
    private $classe;

    public function __construct($classe, $tableName = "") {
        $this->classe = $classe;
        if ($tableName == "")
            $this->tableName = strtolower(get_class($this->classe));
        else
            $this->tableName = $tableName;
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

    public function reset() {
        $this->fields = array();
    }

    public function generateInsertForm($form = true, $label = true, $dataType = "userInfo", $formid = "") {
        $r = "";
        if ($formid == "") {
            $formid = 'insert' . $this->tableName;
        }
        if ($form)
            $r .= '<form id="' . $formid . '" class="form-inline">';
        foreach ($this->fields as $field) {
            //$r.= '<div class="control-group">';
            if ($label)
                $r.= '<label class="control-label form-lw gris-moyen" for="' . $field->getFieldId() . '">' . $field->getFieldPh() . '</label>';
            //$r.= '<div class="controls">';
            $r.= $field->genererChampHtml($dataType, $this->getFieldValue($field->nomChamp), "insert");
            $r.= '';
            //$r.= '</div>';
            //$r.= '</div>';
        }
        $r.= '<div class="control-group form_lw"><div class="controls">';
        $r.= '<button type="button" data-target="' . $this->tableName . '"  data-form="' . $formid . '" data-role="insert" class="btn button"><i class="icon-save"></i> Ajouter le ' . strtolower(get_class($this->classe)) . '</button>';
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
        $s = "Formulaire de la classe :<br>";
        foreach ($this->fields as $champ) {
            $s .= '  - ' . $champ->nomChamp . ' (' . $champ->typeChamp . ')<br>';
        }
        return $s;
    }
    
    public function getFieldByName($name){
        $f = null;
        foreach ($this->fields as $field) {
            if($field->nomChamp == $name){
                $f = $field;
                break;
            }
        }
        return $field;
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



            var_dump($this);
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
