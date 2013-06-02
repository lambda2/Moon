<?php

class Debug {

    private static $content = array();
    protected static $logs = array();
    protected static $instance;

    protected function __construct() {

    }

    
    public static function getInstance()
    {
        if (!(self::$instance instanceof Debug) or self::$instance == null)
            self::$instance = new Debug();
        return self::$instance;
 
    }

    public function addReport($str, $severity=0) {
        $a = array();
        $a['level'] = $severity;
        $a['msg']   = $str;
        self::$content[] = $a;
    }

    public static function log($str, $severity=0)
    {
        $a = array();
        $a['level'] = $severity;
        $a['msg']   = $str;
        self::$content[] = $a;
    }

    public static function getLogs()
    {
        return self::$logs;
    }

    private function getSeverityLabel($severity) {
        return '<button class="btn-mini btn btn-' . $this->getSeverityClass($severity) . '"> </button>';
    }

    private function getSeverityClass($severity) {
        $class = '[c="color:';
        switch ($severity) {
            case -1:
                $class .= 'green"]✔[c] ';
                break;
            case 0:
                $class .= 'orange"]o[c] ';
                break;
            case 1:
                $class .= 'red"]x[c] ';
                break;
            default:
                $class .= 'red"]x[c] ';
                break;
        }
        return $class;
    }

    /**
     * Va retourner la classe du bouton de debug en bas a gauche du template
     */
    private function getAlertLevel() {
        $error   = 0;
        $warning = 0;
        $info    = 0;
        $success = 0;
        if(count(self::$content) == 0)
            return "success";
        foreach (self::$content as $value) {
            if($value['level'] == -1)
                $success++;
            else if($value['level'] == 0)
                $info++;
            else if($value['level'] == 1)
                $warning++;
            else
                return "danger";
        }
        if($warning > 0){
            return "warning";
        }
        else if ($info > 0) {
            return "info";
        }
        else {
            return "success";
        }
        
    }

    /**
     * Todo, not already in use...
     */
    private function getHtmlLogsList()
    {
        $ret = '<ul>';
        foreach (self::$logs as $log) {
            $ret .= '<li>'.$log.'</li>';
        }
        $ret .= '</ul>';
        return $ret;
    }

    private function getModalContent() {
        $modalContent = '<script>'.$this->getReportHtml() .'</script>';
        return $modalContent;
    }

    private function getReportHtml() {
        $style= 'var head_style = \'font-family: "Oxygen", sans-serif; text-align:center;font-size: 26px; color: #aaa; padding: 8px 0; line-height: 40px\';';
        $style_sub= 'var sub_style = \'font-family: "Oxygen", font-weight:bold;sans-serif; font-size: 13px; color: #999; border-top : 1px solid #CCC; padding: 8px 0; line-height: 20px\';';

        $report = $style.'log.l(\'%cMoon Framework\',head_style);';
        $report .= $style_sub.'log.l(\'%cDebug content for the current running application\',sub_style);';

        if (count(self::$content) > 0) {
            foreach (self::$content as $erreur) {
                $report.= 'log(\'' .$this->getSeverityClass($erreur['level']).addslashes($erreur['msg']) . "');";
                
            }
        }
        return $report;
    }

    public function showReport() {
        $report = $this->getModalContent();
        return $report;
    }

    public function isDebug() {
        if (Core::opts()->system->mode == "DEBUG")
            return true;
        else
            return false;
    }

}

?>
