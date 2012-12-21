<?php

if (!isset($INDEX) || !isset($_GET['service'])) {
    die("Accès non autorisé");
    pageNotFound('',$relativePath);
} else {
    
    $arg = $_GET['action'];
/*
    switch ($arg) {
        case "download":
            echo $realisation->getRealisationHtml($_GET['idReal']);
            break;

        default:
            die("real.php - l 16");
            //pageNotFound();
            break;
    }*/
}
?>
