<?php

/*
 * This file is part of the moon framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Classe controleur. 
 */
abstract class Controller {

    protected $loader;
    protected $twig;

    /**
     * @var string template la template qui va etre affichée. 
     */
    protected $template;

    /**
     * @var array webdata le tableau contenant toutes les infos système
     * a passer à la template (comme le debug, ou la template mere...) 
     */
    protected $webdata = array();

    /**
     * @var array data le tableau contenant les données que le controleur doit
     * passer à la vue.
     */
    protected $data = array();

    public function __construct($request = '') {
        /* $this->webdata = array();
          $this->loader = new Twig_Loader_Filesystem(array('Vues/','System/Templates/'));
          $this->twig = new Twig_Environment($this->loader, array(
          // 'cache' => 'System/Cache/',
          )); */
        $this->initialize();


        if ($request == '')
            $this->index();
    }

    /**
     * Va generer un tableau contenant les options de configuration du 
     * moteur de templates en fonction des options définies dans le fichier de 
     * configuration.
     * Ce tableau va ensuite etre passé au Twig_Environment lors de son
     * instanciation.
     * 
     * @return array les options de configuration du moteur de template
     * 
     * @see Twig_Environment
     */
    final protected function getTemplateLoaderOptionsArray() {
        /**
         * On charge les options de configuration du moteur de templates
         * en fonction des options définies dans le fichier de configuration
         */
        $tplConfig = array();

        if (Core::opts()->templates->cache_enabled) {
            $tplConfig['cache'] = Core::opts()->templates->cache_path;
        }
        if (Core::opts()->system->mode == 'DEBUG') {
            $tplConfig['debug'] = true;
        }

        return $tplConfig;
    }

    /**
     * Va renvoyer un tableau contenant les chemins des dossiers contenant 
     * les templates
     * @return array les chemins des dossiers contenant les templates
     */
    final protected function getTemplatePathsArray() {
        /**
         * On récupère depuis le fichier de configuration les
         * chemins des dossiers contenant les templates,
         * C'est à dire le chemin des templates utilisateurs (les vues)
         * et le chemin des templates Système.
         */
        $tplPaths = array(Core::opts()->templates->user_path);

        return $tplPaths;
    }

    /**
     * Va initialiser le controleur, c'est à dire initialiser toutes les 
     * propriétés du controleur à leur valeur par défaut.
     */
    final protected function initialize() {

        $this->template = strtolower(get_class($this)) . '.twig';


        $this->getMainTwigEngine();

        $this->initializeWebData();

        // Le template par défaut à étendre est défini dans le fichier de conf
    }

    final protected function getMainTwigEngine() {
        $this->loader = new Twig_Loader_Filesystem(
                $this->getTemplatePathsArray()
        );

        $this->twig = new Twig_Environment(
                $this->loader, $this->getTemplateLoaderOptionsArray()
        );
        $escaper    = new Twig_Extension_Escaper(false);
        $this->twig->addExtension($escaper);

        $this->twig->addExtension(new MoonTwig());
        
        if (Core::opts()->system->mode == 'DEBUG') {
            $this->twig->addExtension(new Twig_Extension_Debug());
        }
        // add custom filter
        /* $this->twig->addFilter('moonlink', 
          new Twig_Filter_Function('Controller::moonlink', array('is_safe' => array('html')))
          ); */
    }

    /*
      public static function moonlink($str, $text='') {
      $pl = explode('.',$str);
      if(strcmp($text,'') == 0){
      $text = $pl[0];
      }
      $str = Core::opts()->system->siteroot.implode(DIRECTORY_SEPARATOR, $pl);
      return '<a href="'.$str.'">' . $text . '</a>';
      } */

    /**
     * Crée la webdata de base et supprime les anciennes webdatas enregistrées.
     */
    final protected function initializeWebData() {
        // On nettoie tout ça...
        $this->webdata = array();

        $this->webdata['info']            = Core::opts()->info->childs();
        $this->webdata['base']            = Core::opts()->system->siteroot;
        $this->webdata['stylesheets']     = array();
        $this->webdata['scripts']         = array();
    }

    /**
     * Ajoute la feuille de style spécifiée aux feuilles de style de la page.
     * @param string $name le nom de la css
     */
    final public function addCss($name) {
        if (file_exists($name)) {
            $this->webdata['stylesheets'][] = $name;
        }
        else if (file_exists(Core::opts()->system->siteroot
                        . Core::opts()->templates->stylesheets_path
                        . $name)) {
            $this->webdata['stylesheets'][] =
                    Core::opts()->system->siteroot
                    . Core::opts()->templates->stylesheets_path
                    . $name;
        }
        else if (file_exists(Core::opts()->templates->stylesheets_path
                        . $name)) {
            $this->webdata['stylesheets'][] =
                    Core::opts()->templates->stylesheets_path
                    . $name;
        }
        else if (file_exists(Core::opts()->templates->stylesheets_path
                        . $name . '.css')) {
            $this->webdata['stylesheets'][] =
                    Core::opts()->templates->stylesheets_path
                    . $name . '.css';
        }
        else {
            /** @TODO : Retirer cet echo malfaisant, et gerer une exception. */
            /* echo "il semblerait que " . Core::opts()->system->siteroot
              . Core::opts()->templates->stylesheets_path
              . $name . " ne soit pas un fichier..."; */
            dbg("la feuille de style [$name] est introuvable.", 1);
        }
    }

    /**
     * Ajoute le script (javascript) spécifié aux scripts de la page.
     * @param string $name le nom du script
     */
    final public function addJs($name) {
        if (file_exists($name)) {
            $this->webdata['scripts'][] = $name;
        }
        else if (file_exists(Core::opts()->system->siteroot
                        . Core::opts()->templates->js_path
                        . $name)) {
            $this->webdata['scripts'][] =
                    Core::opts()->system->siteroot
                    . Core::opts()->templates->js_path
                    . $name;
        }
        else if (file_exists(Core::opts()->templates->js_path
                        . $name)) {
            $this->webdata['scripts'][] =
                    Core::opts()->templates->js_path
                    . $name;
        }
        else if (file_exists(Core::opts()->templates->js_path
                        . $name . '.js')) {
            $this->webdata['scripts'][] =
                    Core::opts()->templates->js_path
                    . $name . '.js';
        }
        else {
            /** @TODO : Retirer cet echo malfaisant, et gerer une exception. */
            /* echo "il semblerait que " . Core::opts()->system->siteroot
              . Core::opts()->templates->js_path
              . $name . " ne soit pas un fichier..."; */

            dbg("le script [$name] est introuvable.", 1);
        }
    }

    /**
     * Va definir quels sont les fichiers css et js à charger par défaut.
     * --> <b>Surchargez la</b> dans vos controlleurs pour inclure 
     * systématiquement les memes fichiers dans vos templates.
     */
    protected function addTemplateBaseIncludes() {

        $this->addCss('bootstrap.min');
        $this->addCss('bootstrap-responsive.min');

        $this->addJs('jquery');
        $this->addJs('bootstrap.min');
    }

    final protected function loadWebData() {
        if (Core::getInstance()->debug()) {
            $this->webdata['debug'] =
                    Core::getInstance()->debug()->showReport();
        }
    }

    /**
     * Ajoute une donnée aux autres données qui seront transmises à la vue.
     * @param string $key le nom de la donnée. Ce nom sera ensuite utilisé 
     * dans la vue / template pour récuperer la valeur de la donnée.
     * @param mixed $data la donnée à stocker.
     * @param boolean $strict définit si oui ou non on ajoute la donnée si elle 
     * est vide, nulle, ou égale à 0;
     * @return Controller L'instance courante
     */
    final public function addData($key, $data, $strict = True) {
        if ($strict) {
            if (!isNull($data)) {
                $this->data[$key] = $data;
            }
        }
        else {
            $this->data[$key] = $data;
        }
        return $this;
    }

    /**
     * Ajoute les données Systeme et utilisateur ensemble et renvoie le tableau
     * contenant toutes ces données.
     * @return array le tableau contenant toutes les données système et 
     * utilisateur.
     */
    final protected function mergeData() {
        $this->loadWebData();

        $tab            = $this->data;
        $tab['webdata'] = $this->webdata;
        return $tab;
    }

    /**
     * Définit un template de base pour les pages du controleur.
     * La liste des templates sont disponibles dans la documentation.
     * @param string $template le nom du template a utiliser
     */
    final public function setBaseTemplate($template) {
        $this->webdata['template_extend'] = $template;
    }

    /**
     * Définit la template a afficher.
     * Par défaut, la template est "[nom du controlleur].twig"
     * @param string $template le nom de la template a afficher.
     */
    final protected function setTemplate($template) {
        $this->template = $template;
    }

    final protected function getTemplateFileName() {
        $index = 0;
        $trace = debug_backtrace();
        foreach ($trace as $step) {
            if (strcasecmp($step['function'], 'getTemplateFileName') != 0 && strcasecmp($step['function'], 'render') != 0) {
                return get_class($this) . '.' . $trace[$index]['function'];
            }
            $index++;
        }
        return false;
    }

    public abstract function index();

    final private function tryToRender() {
        try {
            echo $this->twig->render($this->template, $this->mergeData());
        } catch (Twig_Error_Loader $exc) {
            try {
                echo $this->twig->render(strtolower($this->template), $this->mergeData());
            } catch (Twig_Error_Loader $excTwo) {
                dbg($excTwo->getMessage()
                        . "<p>La template {$this->template} n'a pas pu etre chargée ! Le fichier existe t'il ?</p>"
                        . "<p><b>Chargement de la template de base du controlleur</b></p>", 2);
                $this->template = strtolower(get_class($this)) . '.twig';
                echo $this->twig->render(strtolower($this->template), $this->mergeData());
            }
        }
    }

    final public function render() {
        $this->template = $this->getTemplateFileName();
        if ($this->template == false) {
            dbg("le template de destination n'a pas pu etre calcule...", 2);
            $this->template = strtolower(get_class($this)) . '.twig';
        }
        else {
            $this->template .= '.twig';
        }
        $this->addTemplateBaseIncludes();
        $this->tryToRender();
    }

}

?>
