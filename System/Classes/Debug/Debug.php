<?php

class Debug {

    private $content = array();
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
        $this->content[] = $a;
    }

    public static function log($str, $severity=0)
    {
        self::$logs[] = $str;
    }

    public static function getLogs()
    {
        return self::$logs;
    }

    private function getSeverityLabel($severity) {
        return '<button class="btn-mini btn btn-' . $this->getSeverityClass($severity) . '"> </button>';
    }

    private function getSeverityClass($severity) {
        $class = 'error';
        switch ($severity) {
            case -1:
                $class = 'success';
                break;
            case 0:
                $class = 'info';
                break;
            case 1:
                $class = 'warning';
                break;
            default:
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
        if(count($this->content) == 0)
            return "success";
        foreach ($this->content as $value) {
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

    private function jsTransformDebugCode()
    {
        $jsCode = "
        <script>
            $('#debug-content').css(
                {
                    'bottom' : '0px',
                    'left' : '0px',
                    'right' : '1px',
                    'height' : '50px',
                    'position' : 'fixed',
                    'display' : 'block',
                    'border-top' : '1px solid #CCC',
                    'background-color' : 'rgba(255,255,255,0.9)'
                }
            );
            $('#debug-content *').css(
                {
                    'display' : 'inline-block',
                    'max-height' : '50px'
                }
            );
        </script>";
        return $jsCode;
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

    private function getModalLink($text) {
        return '<a href="#debug-modal" role="button" class="btn btn-' . $this->getAlertLevel() . '" data-toggle="modal">' . $text . '</a>';
    }

    private function getModalContent() {
        $modalContent = '
            <div id="debug-content">
  <div class="modal-header">
    <button type="button" data-to-close="debug-content">×</button>
  </div>
  <div class="modal-body">
    ' .$this->getModalLink(count($this->content)). ' erreur(s) : '. $this->getReportHtml() . '.<a id="debug-log" href="#">Logs</a>'.'
  </div>
  <!-- <div id="debug-log-aera">'.$this->getHtmlLogsList().'</div> -->
</div>' . $this->jsTransformDebugCode();
        return $modalContent;
    }

    private function getReportHtml() {
        $report = '';
        if (count($this->content) > 0) {
            $report.= '<table class="table table-striped table-condensed">
                <thead><tr><td>Rapports :</td></tr> </thead>';
            foreach ($this->content as $erreur) {
                $report.= "<tr class=\"" .$this->getSeverityClass($erreur['level'])."\"><td>" . $erreur['msg'] . "</td></tr>";
            }
            $report.= '</table>';
        }
        else {
            $report.= '<p>Tout va bien...</p>';
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
