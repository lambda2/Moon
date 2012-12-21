<?php

if (!isset($INDEX) || !isset($_GET['ajax'])) {
    die("Accès non autorisé");
    pageNotFound('',$relativePath);
} else {
    $realisation = new Realisation();
    $arg = $_GET['action'];

    switch ($arg) {
        case "detailReal":
            echo $realisation->getRealisationHtml($_GET['idReal']);
            break;

        default:
            die("real.php - l 16");
            //pageNotFound();
            break;
    }
}
?>
