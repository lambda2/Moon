<?php



$spyc = new Spyc();
$var = $spyc->loadFile('System/Classes/Configuration/configuration.yml');
$vardef = $spyc->loadFile('Config/access_defaults.yml');
echo '<h3>var_dump($var)</h3>';
var_dump($var);
?>

