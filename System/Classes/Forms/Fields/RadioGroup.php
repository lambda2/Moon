<?php

/*
 * Copyright 2013 lambda2.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * 
 * @author lambda2
 */


include_once 'Field.php';
include_once 'Radio.php';
/*
 * Représente un groupe de champ radio de formulaire.
 * C'est à dire une case à cocher dépendant des autres.
 */

class RadioGroup extends Field
{

    protected $radioItems;

    /**
     * Construit un groupe de champs radio
     * @param string $name l'attribut "name" du champ
     * @param array $values un ensemble de valeurs possibles
     */
    public function __construct ($name, $values = array ())
    {
        parent::__construct ();
        $this->radioItems = array();
        $this->name = $name;
        foreach ($values as $radiovalue)
        {
            $this->createRadioItem ($radioValue);
        }
    }

    public function addRadioItem ($radio)
    {
        if ( is_a ($radio, 'Radio') )
        {
            $this->radioItems[] = $radio;
        }
        else if ( is_string ($radio) || is_numeric ($radio) )
        {
            $this->createRadioItem ($radio);
        }
    }

    public function createRadioItem ($value)
    {
        $radio = new Radio ($this->name, $value);
        $this->radioItems[] = $radio;
    }

    public function clearRadioItems ()
    {
        $this->radioItems = array ();
    }

    public function getRadioItems ()
    {
        return ($this->radioItems);
    }

    /**
     * Va retourner le code Html du groupe radio
     */
    public function getHtml ()
    {
        if ( !$this->isVisible () )
            return '';

        $s = '';
        foreach ($this->radioItems as $radio)
        {
            $s .= "<div class='radio-group'>";
            $s .= $radio->getLabel();
            $s .= $radio->getHtml ();
            $s .= "</div>";
        }
        return ($s);
    }

}
