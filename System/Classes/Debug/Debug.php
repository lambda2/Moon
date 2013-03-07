<?php

class Debug {

    private $content;
    private $config;
    private $num;
    
    public function __construct($config) {
        $this->config = $config;
        $this->num = 0;
    }

    public function addReport($str) {
        $this->content.=$str;
        $this->num ++;
    }

    public function showReport() {
        $str = '<link rel="stylesheet" href="System/Debug/debug.css">
            <div class="debug-aera" id="debug-aera"><div class="debug-num"><p>'.$this->num.' Erreur(s)</p></div>
            <div class="debug-zone">'
        . $this->content . 
                '</div></div>';
        return $str;
    }
    
    public function isDebug(){
    if ($this->config->getDevMode() == "DEBUG") 
        return true;
    else
        return false;
    }

}

function dbg($msg, $severity = 0) {
    if (isset($GLOBALS['dev_mode']) && $GLOBALS['System']->debug()->isDebug()) {
       
            $report = "";
            if ($severity == -1)
                $report = '<p class="report-succes">' . $msg . '</p>';
            elseif ($severity == 0)
                $report = '<p class="report-warning">' . $msg . '</p>';
            elseif ($severity == 1)
                $report = '<p class="report-error">' . $msg . '</p>';
            else
                $report = '<p class="report-error">' . $msg . ' (Rapport de niveau ' . $severity . ')</p>';
            
            $GLOBALS['System']->debug()->addReport($report);
            echo $report;
    }
        else {
            echo "Erreur lors de l'initialisation du deboggeur";
        }
    
}



?>
