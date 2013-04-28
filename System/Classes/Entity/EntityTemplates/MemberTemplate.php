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

    }

    public function seConnecter() {
        if ($this->exists()) {
            $_SESSION['login'] = $this->pseudo;
            return true;
        } else {
            return false;
        }
    }

    public static function seDeconnecter() {
        $_SESSION = array();

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
