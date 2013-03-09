<?php



$spyc = new Spyc();
$var = $spyc->loadFile('System/Classes/Configuration/configuration.yml');
$vardef = $spyc->loadFile('Config/access_defaults.yml');
echo '<h3>var_dump($var)</h3>';
var_dump($var);
//echo '<h3>var_dump($vardef)</h3>';
//var_dump($vardef);
//echo '<h3>var_dump(array_merge_recursive($var,$vardef))</h3>';
//var_dump(array_merge_recursive($vardef,$var));
//echo '<h3>var_dump(array_merge_recursive_distinct($var,$vardef))</h3>';
//var_dump(extendArray($var,$vardef));
?>

