<?php

$contrat = new Contrat();

$contrat->getAccess()->getAccessRulesFromConfigFile();
echo $contrat;

?>
