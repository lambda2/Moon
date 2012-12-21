<?php
$real = "Controleurs";
/*$MyDirectory = opendir($real) or die('Erreur');
while ($Entry = readdir($MyDirectory)) {
    if (strlen($Entry) > 3 && (substr($Entry, strlen($Entry)-3) == "php")) {
        
        $lol = basename($Entry,".php");
        if(Page::pageExiste($lol, $GLOBALS['bdd'])){
            //dbg("la page $lol existe !"); 
        }
        else {
            Page::ajouterPage($lol, $GLOBALS['bdd']);
            //dbg("la page $lol a été ajoutée !<br>"); 
        }
    }
}
closedir($MyDirectory);*/
?>
