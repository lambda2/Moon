<?php
if (!isset($INDEX) || !isset($_GET['ajax'])) {
    die("Accès non autorisé");
    pageNotFound('', $relativePath);
} else {
    $arg = $_GET['action'];

    switch ($arg) {
        case "c":
            if (!isset($_GET['u']) || !isset($_GET['p']))
                echo "0";
            else {
                sleep(4);
                if (verifConnexion($GLOBALS['bdd'], htmlentities($_GET['u']), sha1(htmlentities($_GET['p']))) == TRUE){
                    $_SESSION['login'] = htmlentities($_GET['u']);
                    echo "1";
                }
                else
                    echo "0";
            }
            break;
            case "d":
                if(isConnecte()){
                    $membre->deconnexion();
                    echo("1");
                }
                else {
                    echo("0");
                }
                
                break;

        default:
            pageNotFound();
            break;
    }
}
?>
