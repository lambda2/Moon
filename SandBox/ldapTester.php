<?php

/**
 * Va nous permettre d'effectuer quelques essais sur les formulaires.
 */
// La séquence de base avec LDAP est 
// connexion, liaison, recherche, interprétation du résultat
// déconnexion

echo '<h3>requête de test de LDAP</h3>';
echo 'Connexion ...';
$ds=ldap_connect("ldap.42.fr");  // doit être un serveur LDAP valide !
echo 'Le résultat de connexion est ' . $ds . '<br />';

if ($ds) { 
    echo 'Liaison ...'; 
    $ldapbind = ldap_bind($ds, $ldaprdn, $ldappass);
                                     // pour un accès en lecture seule.
    echo 'Le résultat de connexion est ' .var_dump($ldapbind) . '<br />';
/*
    echo 'Recherchons (sn=S*) ...';
    // Recherche par nom
    $sr=ldap_search($ds,"uid=aaubin", "sn=*");  
    echo 'Le résultat de la recherche est ' . $sr . '<br />';

    echo 'Le nombre d\'entrées retournées est ' . ldap_count_entries($ds,$sr) 
         . '<br />';

    echo 'Lecture des entrées ...<br />';
    $info = ldap_get_entries($ds, $sr);
    echo 'Données pour ' . $info["count"] . ' entrées:<br />';

    for ($i=0; $i<$info["count"]; $i++) {
     var_dump($info[$i]);
    }

    echo 'Fermeture de la connexion';
    ldap_close($ds);
*/
} else {
    echo '<h4>Impossible de se connecter au serveur LDAP.</h4>';
}


?>