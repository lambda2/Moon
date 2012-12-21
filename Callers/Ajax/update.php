<?php

if (!isset($INDEX) || !isset($_GET['ajax'])) {
    die("Accès non autorisé");
    pageNotFound('', $relativePath);
} else {
    $arg = $_GET['action'];
    if ($membre->getId() != $_GET['id']) {
        echo "0";
    } else {
        switch ($arg) {
            case "update":
                if (!isset($_GET['field']) || !isset($_GET['target']) || !isset($_GET['id']) || !isset($_GET['newv']) || !isConnecte())
                    echo "0";
                else {
                    switch ($_GET['target']) {
                        case "membres":
                            try {
                                sleep(2);
                                $membre->majField(($_GET['field']), ($_GET['newv']));
                                echo "1";
                            } catch (Exception $exc) {
                                echo "0";
                            }
                            break;

                        default:
                            break;
                    }
                }
                break;
            
            case "insert":
                if (!isset($_GET['fields']) || !isset($_GET['target']) || !isset($_GET['id']) || !isConnecte())
                    echo "0";
                else {
                    $f = explode(",", $_GET['fields']);
                    switch ($_GET['target']) {
                        case "trajet_lw":
                            try {
                            $t = new Trajet($GLOBALS['bdd']);
                                foreach ($f as $assoc) {
                                    $k = explode(':', $assoc);
                                    $t->set($k[0], $k[1]);
                                    //echo 'On attribue la valeur '.$k[1].' a la propriété '.$k[0];
                                }
                                $t->insertToBdd();
                                echo "1";
                            } catch (Exception $exc) {
                                echo "0";
                            }
                            break;

                        default:
                            break;
                    }
                }
                break;

            default:
                pageNotFound();
                break;
        }
    }
}
?>