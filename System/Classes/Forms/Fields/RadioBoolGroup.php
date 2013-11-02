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
 */
/*
 *
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * 
 * @author lambda2
 */

include_once 'Field.php';
include_once 'RadioGroup.php';
/*
 * Représente un groupe de champ radio de formulaire.
 * C'est à dire une case à cocher dépendant des autres.
 */

class RadioBoolGroup extends RadioGroup
{

    public function __construct ($name)
    {
        parent::__construct ($name);
        $this->name = $name;
        $true = new Radio ($name, "1");
        $false = new Radio ($name, "0");
        $this->radioItems['false'] = ($false);
        $this->radioItems['true'] = ($true);
    }

}
