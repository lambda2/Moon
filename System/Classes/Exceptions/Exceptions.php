<?php

class CoreException extends Exception {
    public function __construct($message, $code=0, $previous=null) {
        parent::__construct($message, $code, $previous);
        die('Erreur : Le système n\'a pas été correctement initialisé ('.$message.')');
    }
}

class AlertException extends Exception {
    public function __construct($message, $code=0, $previous=null) {
        parent::__construct($message, $code, $previous);
        if(Configuration::isStarted())
            dbg ($message, 0);
        else
            echo $this->getMessage();
    }
}

class CriticalException extends Exception {
    public function __construct($message, $code=0, $previous=null) {
        parent::__construct($message, $code, $previous);
        if(Configuration::isStarted())
            dbg ($message, 1);
        else
            echo $this->getMessage();
    }
}


class MemberAccessException extends Exception {
    public function __construct($message, $code=0, $previous=null) {
        parent::__construct($message, $code, $previous);
        if(Configuration::isStarted())
            dbg ($message, 1);
        else
            echo $this->getMessage();
    }
}

?>
