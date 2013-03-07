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
    public static function createField($dataType){
        
        $dataType = strtolower($dataType);
        
        switch ($dataType) {
            case $value:


                break;

            default:
                break;
        }
        
    }
}

?>
