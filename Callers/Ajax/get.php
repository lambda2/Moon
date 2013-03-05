<?php

if (!isset($INDEX) || !isset($_GET['ajax'])) {
    die("Accès non autorisé");
    pageNotFound('', $relativePath);
} else {
    $arg = $_GET['action'];

    switch ($arg) {
        case "dataset":
            if (!isset($_GET['target']) || !isset($_GET['id']) || !isset($_GET['n']) || !isConnecte())
                echo "0";
            elseif ($membre->getId() != $_GET['id']) {
                echo "0";
            } else {
                try {
                    $t = explode(".", $_GET['target']);
                    if(count($t) < 2)
                        echo "0";
                    else {
                    $Req = $GLOBALS['bdd']->prepare("SELECT * FROM `$t[0]` WHERE 1");
                    $Req->execute(array());
                    echo '<datalist id="'.$_GET['n'].'">';
                     while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
                        echo '<option value="'.$res->$t[1].'">';
                    }
                    echo '</datalist>';
                    }
                } catch (Exception $exc) {
                    echo "0";
                }
            }
            break;


        default:
            pageNotFound();
            break;
    }
}
?>