<?php

/*
 * This file is part of the Lambda Web Framework.
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

        $this->webdata = array();

        $this->loader = new Twig_Loader_Filesystem(
                $this->getTemplatePathsArray()
        );

        $this->twig = new Twig_Environment(
                $this->loader, $this->getTemplateLoaderOptionsArray()
        );
        $escaper    = new Twig_Extension_Escaper(false);
        $this->twig->addExtension($escaper);

        $this->webdata['template_extend'] =
                Core::opts()->templates->default_template;

        // Le template par défaut à étendre est défini dans le fichier de conf
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
        echo $this->twig->render($this->template, $this->mergeData());
    }

}

?>
