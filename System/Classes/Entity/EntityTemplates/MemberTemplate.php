<?php


class MemberTemplate extends Entity {
    
    
    public function __construct() {
        parent::__construct();
    }

    public static function isConnecte() {
        return (isset($_SESSION['login']) ? true : false);
    }

    public function exists()
    {
    	return  $this->instance;
    }

    public static function logAndGet($fields)
    {
    	$cl = EntityLoader::getClass(get_called_class());
        $exists = $cl->loadByArray($fields);
        $cl->autoLoadLinkedClasses();

        if($exists)
        {
        	Core::getInstance()->setUser($cl->getPseudo()->getValue());
        	$cl->seConnecter();
        	return $cl;
        }
        else
        	return false;
    }

    public function seConnecter() {
        if ($this->exists()) {
            $_SESSION['login'] = $this->pseudo->getValue();
            return true;
        } else {
            return false;
        }
    }

    public static function seDeconnecter() {
        $_SESSION = array();
        Core::getInstance()->setUser(null);

		// le cookie de session.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }

		// Finalement, on détruit la session.
        session_destroy();
    }
    


}

?>
