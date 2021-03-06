<?php

/**
 * Permet de créer un champ de formulaire adapté
 * à son type.
 */
class FieldFactory {
    
    protected function __construct(){
        
    }

    public static function createField($type, $name)
    {
        $field = null;
        
        switch ($type) {

            case 'varchar':
                $field = new Input($name,'text');
                break;

            case 'char':
                $field = new Input($name,'text');
                break;

            case 'int':
                $field = new Input($name,'number');
                break;

            case 'tinyint':
                $field = new RadioBoolGroup($name);
                break;

            case 'decimal':
                $field = new Input($name,'number');
                break;

            case 'date':
                $field = new Input($name,'date');
                break;

            case 'timestamp':
                $field = new Input($name,'date');
                break;

            case 'enum':
                $field = new Select($name);
                break;

            case 'text':
                $field = new TextArea($name);
                break;

            default:
                break;
        }

        return $field;
        
    }

    public static function loadFromStdObject($object){

        return self::createField(
            $object->Type,
            $object->Field,
            $object->Null,
            $object->Default,
            $object->Key,
            $object->Extra);
    }

    protected static function parseNullValue($nullValue)
    {
        if($nullValue === 'YES')
            return true;
        else
            return false;
    }

    protected static function parseTypeValue($typeValue)
    {
        $properTypeArray = explode('(', $typeValue);
        return $properTypeArray[0];
    }

    protected static function parseInnerParenthValue($value)
    {
        $result = array();
        preg_match('#\(+(.*)\)+#', $value, $result); 
        return implode('', explode('\'',$result[1]));
    }


}

?>
