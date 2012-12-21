<?php

/**
  Verifie que l'email entrÃ© est correct a l'aide d'expressions rÃ©guliÃ¨res.
 */
function verifierEmail($email) {
    $ve = false;
    if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)) {
        $ve = true;
    }
    return $ve;
}

function verifierChamps($email, $message, $type) {
    if ($email != "" && $message != "" && $type != "" && verifierEmail($email))
        return true;
    else
        return false;
}

function envoyerMail($expediteur, $msg, $type = "bonjour", $destinataire = "andre.aubin@lambdaweb.fr") {
    if (!verifierChamps($expediteur, $msg, $type))
        return false;
    else {
        $message = " Bonjour ! Quelqu'un vous a laissé un message depuis le formulaire de contact de Lambdaweb !\n";
        $message .= "Type : $type \n";
        $message .= "Email : $expediteur \n";
        $message .= "Message : $msg \n";
        sleep(2);
        mail("andre.aubin.ldaw@gmail.com", "Demande de contact de lambdaweb", $message);
        return mail($destinataire, "Demande de contact de lambdaweb", $message);
    }
}

?>
