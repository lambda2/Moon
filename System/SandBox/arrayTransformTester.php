<?php

/*
 * This file is part of the Lambda Web Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


//echo MoonChecker::run();

echo '<h3>Testing output of Arrays</h3>';

echo '<li> lets load forms ! </li>';
$f          = Spyc::YAMLLoad(Core::opts()->forms->form_files.'Forms.yml');
var_dump($f);


?>