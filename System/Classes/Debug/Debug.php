<?php

class Debug {

    private $content = array();
    
    public function __construct() {
    }

    public function addReport($str) {
        echo "ajout du report : ".$str;
        $this->content[] = $str;
    }
    
    private function getModalLink($text, $nb){
        $class = 'warning';
        if($nb == 0)
            $class = 'success';
        return '<a href="#debug-modal" role="button" class="btn btn-'.$class.'" data-toggle="modal">'.$text.'</a>';
    }
    
    private function getModalContent(){
        $modalContent = '
            <div id="debug-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="debug-modallabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="debug-modallabel">Erreurs</h3>
  </div>
  <div class="modal-body">
    '.$this->getReportHtml().'
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>';
        return $modalContent;
    }
    
    private function getReportHtml(){
        $report = '';
        if(count($this->content) > 0){
            $report.= '<table class="table table-striped">';
            foreach ($this->content as $erreur) {
                $report.= "<tr><td>".$erreur."</tr></td>";
            }
            $report.= '</table>';
            
        }
        else {
            $report.= '<p>Tout va bien...</p>';
        }
        return $report;
    }

    public function showReport() {
        
        $report = '<div class="moon-debug-block">'
                .$this->getModalLink(count($this->content),count($this->content))
                .'</div>';
        
        $report.= $this->getModalContent();
        return $report.'';
    }
    
    public function isDebug(){
    if (Core::opts()->system->mode == "DEBUG") 
        return true;
    else
        return false;
    }

}




?>
