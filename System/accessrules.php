<?php

function gererAcces($role, $rolePage) {
    if ($role > $rolePage) {
        if (!headers_sent()) {
            header('Location: index.php?err=6');
        }
        else {
            echo('<script language="javascript">document.location.href="index.php?err=6"</script>');
        }
        die("Acces interdit.");
    }
}

?>
