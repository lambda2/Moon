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
    protected function getTemplateLoaderOptionsArray() {
        /**
         * On charge les options de configuration du moteur de templates
         * en fonction des options définies dans le fichier de configuration
         */
        $tplConfig = array();

        if (Core::opts()->templates->cache_enabled) {
            $tplConfig['cache'] = Core::opts()->templates->cache_path;
        }

        return $tplConfig;
    }

    /**
     * Va renvoyer un tableau contenant les chemins des dossiers contenant 
     * les templates
     * @return array les chemins des dossiers contenant les templates
     */
    protected function getTemplatePathsArray() {
        /**
         * On récupère depuis le fichier de configuration les
         * chemins des dossiers contenant les templates,
         * C'est à dire le chemin des templates utilisateurs (les vues)
         * et le chemin des templates Système.
         */
        $tplPaths = array(
            Core::opts()->templates->user_path,
            Core::opts()->templates->system_path);

        return $tplPaths;
    }

    /**
     * Va initialiser le controleur, c'est à dire initialiser toutes les 
     * propriétés du controleur à leur valeur par défaut.
     */
    protected function initialize() {

        $this->template = strtolower(get_class($this)) . '.twig';


        $this->loader = new Twig_Loader_Filesystem(
                $this->getTemplatePathsArray()
        );

        $this->twig = new Twig_Environment(
                $this->loader, $this->getTemplateLoaderOptionsArray()
        );
        $escaper    = new Twig_Extension_Escaper(false);
        $this->twig->addExtension($escaper);

        $this->initializeWebData();

        // Le template par défaut à étendre est défini dans le fichier de conf
    }

    /**
     * Crée la webdata de base et supprime les anciennes webdatas enregistrées.
     */
    protected function initializeWebData() {
        // On nettoie tout ça...
        $this->webdata = array();

        $this->webdata['template_extend'] =
                Core::opts()->templates->default_template;
        $this->webdata['info']            = Core::opts()->info->childs();
        $this->webdata['base']            = Core::opts()->system->siteroot;
        $this->webdata['stylesheets']     = array();
        $this->webdata['scripts']         = array();
    }

    /**
     * Ajoute la feuille de style spécifiée aux feuilles de style de la page.
     * @param string $name le nom de la css
     */
    public function addCss($name) {
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
                        . $name.'.css')) {
            $this->webdata['stylesheets'][] =
                    Core::opts()->templates->stylesheets_path
                    . $name.'.css';
        }
        else {
            /** @TODO : Retirer cet echo malfaisant, et gerer une exception. */
            /*echo "il semblerait que " . Core::opts()->system->siteroot
            . Core::opts()->templates->stylesheets_path
            . $name . " ne soit pas un fichier...";*/
            dbg("la feuille de style [$name] est introuvable.",1);
        }
    }

    /**
     * Ajoute le script (javascript) spécifié aux scripts de la page.
     * @param string $name le nom du script
     */
    public function addJs($name) {
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
                        . $name .'.js')) {
            $this->webdata['scripts'][] =
                    Core::opts()->templates->js_path
                    . $name .'.js';
        }
        else {
            /** @TODO : Retirer cet echo malfaisant, et gerer une exception. */
            /*echo "il semblerait que " . Core::opts()->system->siteroot
            . Core::opts()->templates->js_path
            . $name . " ne soit pas un fichier...";*/
            
            dbg("le script [$name] est introuvable.",1);
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
        $this->addCss('penis');
        
        $this->addJs('jquery');
        $this->addJs('bootstrap.min');
        $this->addJs('bootstrap.miyn');
    }

    protected function loadWebData() {
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
    public function addData($key, $data, $strict = True) {
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
    protected function mergeData() {
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
    public function setBaseTemplate($template) {
        $this->webdata['template_extend'] = $template;
    }

    /**
     * Définit la template a afficher.
     * Par défaut, la template est "[nom du controlleur].twig"
     * @param string $template le nom de la template a afficher.
     */
    public function setTemplate($template) {
        $this->template = $template;
    }

    public function index() {
        
    }

    public function render() {
        $this->addTemplateBaseIncludes();
        echo $this->twig->render($this->template, $this->mergeData());
    }

}

?>
