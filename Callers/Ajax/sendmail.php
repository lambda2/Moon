<?php

if (!isset($INDEX) || !isset($_GET['ajax'])) {
    die("Accès non autorisé");
    pageNotFound('', $relativePath);
} else {
    $realisation = new Realisation();
    $arg = $_GET['action'];

    switch ($arg) {
        case "send":
            if (!isset($_GET['exp']) || !isset($_GET['msg']) || !isset($_GET['typemsg']))
                echo "0";
            else {
                if (envoyerMail(htmlentities($_GET['exp']), htmlentities($_GET['msg']), htmlentities($_GET['typemsg'])) == TRUE)
                    echo "1";
                else
                    echo "0";
            }
            break;

        default:
            pageNotFound();
            break;
    }
}
?>
