<?php

/**
 * Permet de créer un champ de formulaire adapté
 * à son type.
 */
class FieldFactory {
    
    protected function __construct(){
        
    }
    
    /**
     * 
     */
    public static function createField(
        $type, $name, $null='NO',
        $default=null, $key='',$extra='')
    {
        
        $dataType = self::parseTypeValue($type);
        $field = null;
        
        switch ($dataType) {

            case 'varchar':
                $field = new Input($name,'text');
                break;

            case 'int':
                $field = new Input($name,'number');
                break;

            case 'enum':
                $field = new Select($name);
                $options = self::parseInnerParenthValue($type);
                foreach (explode(',', $options) as $opt) {
                    $field->addOption($opt);
                }
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
        if($null === 'YES')
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
