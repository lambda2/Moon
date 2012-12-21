<?php


/*
 * Controleur frontal des services et des supports de requetes ajax
 */

// Si on a un service a charger
if (isset($_GET['service'])) {
    
    /**
     * A faire
     */
    pageNotFound();
    
}
else if (isset($_GET['ajax'])) {
    // Et que cette page existe
    if (file_exists('Callers/Ajax/' . strtolower($_GET['ajax']) . '.php')) {
        // On la charge
        include_once('Callers/Ajax/' . strtolower($_GET['ajax']) . '.php');
    } else {
        //echo('le fichier '.'Callers/Ajax/' . strtolower($_GET['ajax']) . '.php n\'existe pas !');
        pageNotFound();
    }
}
else  {
            pageNotFound();
}
?>
