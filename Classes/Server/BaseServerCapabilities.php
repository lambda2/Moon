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
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author lambda2
 */

/**
 * Will be the base class for any web server.
 */
abstract class BaseServerCapabilities
{

    protected $superpowers = array();
    protected $serverSoftware;
    protected static $instance;

    public function __construct()
    {
        $this->loadPowers();
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof BaseServerCapabilities))
        {
            self::$instance = CapabilitiesFacory::getCapabilities();
        }
        else
        {
            return self::$instance;
        }
    }

    /**
     * load all the known capabilities.
     * Here, we only check php modules.
     */
    protected function loadPowers()
    {
        $this->superpowers = get_loaded_extensions();
    }

    /**
     * Returns the server software name
     */
    public function getServerSoftware()
    {
        return $this->serverSoftware;
    }

    /**
     * Returns true if the server have the given capability
     * @param String $power the capability to check
     * @return Bool true if the server have the given capability, false otherwise
     */
    public function havePower($power)
    {
        return (in_array($power, $this->superpowers));
    }

}
