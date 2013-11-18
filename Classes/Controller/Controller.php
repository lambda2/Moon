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
 * The main controller class.
 * All controllers must inherit this class.s
 * 
 * @package Moon
 * @subpackage Controller
 */
abstract class Controller
{

    /**
     * @var Twig_Loader_Filesystem The Twig loader
     */
    protected $loader;

    /**
     * @var Twig_Environment the Twig environment
     */
    protected $twig;

    /**
     *
     * @var bool the Ajax flag.
     * Set to true when the called method have an Ajax annotation. 
     */
    protected $ajax = false;

    /**
     *
     * @var bool the fromAjax flag.
     * Set to true when the method is called by an ajax request.
     * 
     * @deprecated Not used yet
     */
    protected $fromAjax = false;

    /**
     * @var string template the template wich will be showed
     */
    protected $template = false;

    /**
     * @var bool true when the template is not default, but user defined
     * with [setTemplate()]
     * @see Controller#setTemplate
     */
    private $definedTemplate = false;

    /**
     * @var string the folder for all the templates 
     */
    protected $templatesFolder = null;

    /**
     * @var ArrayBrowser the config for all the templates 
     */
    protected $templatesConfig = null;

    /**
     * @var array webdata the array wich contains all the template shared data,
     * like css stylesheets, scripts, profiler informations...
     */
    protected $webdata = array ();

    /**
     * @var array urlParams the array that contains all the parameters 
     * passed to the template. (in the url, all that was after the "?"
     */
    protected $urlParams = array ();

    /**
     * @var array data the array containing the data that the controller 
     * must pass to the view.
     */
    protected $data = array ();

    /**
     * The Controller constructor.
     */
    public function __construct ()
    {
        $this->setTemplateFolder (Core::opts ()->templates->default_template);
        $this->initialize ();
    }

    /**
     * Will add all the fields in the given array to the 
     * template data.
     * @param array $params the data to add.
     */
    public function registerParams ($params = array ())
    {
        if ( is_array ($params) )
        {
            foreach ($params as $key => $value)
            {
                $this->addData ($key, $value);
            }
        }
    }

    /**
     * set the view to be displayed as an ajax page.
     * @param boolean $ajax true or false
     * @return $this
     */
    public function setAjax ($ajax = true)
    {
        $this->ajax = $ajax;
        return $this;
    }

    /**
     * @return true if the current controller is for ajax request, false otherwise
     */
    public function isAjax ()
    {
        return $this->ajax;
    }

    /**
     * set the view called by an ajax request.
     * @param boolean $ajax true or false
     * @return $this
     */
    public function setFromAjax ($ajax = true)
    {
        $this->fromAjax = $ajax;
        return $this;
    }

    /**
     * @return true if the current controller is called by an ajax request, 
     * false otherwise
     */
    public function isFromAjax ()
    {
        return $this->fromAjax;
    }

    /**
     * Will change de default template folder to a subdirectory of it,
     * and all the next templates will be searched in this directory.
     * @param string $templateFolder the subdirectory to search next templates
     */
    public function useCustomTemplateFolder ($templateFolder)
    {
        $this->setTemplateFolder ($templateFolder);
        $this->loadTemplateConfiguration ();

        // We have to reload the engine to update the search paths.
        // @TODO : find an other solution than reload all the twig engine
        $this->reloadMainTwigEngine ();
    }

    /**
     * Set the template folder path without relative path.
     * This method will search the requested template folder
     * in the Templates directory [templates->path] set in 
     * the application configuration file.
     * @param string $template the name of the template.
     */
    protected function setTemplateFolder ($template)
    {
        // the path to the templates folder
        $templatePath = Core::opts ()->templates->path;

        // the selected template config file path
        $templateFolderPath = $templatePath . '/' . $template . '/';

        $this->templatesFolder = $templateFolderPath;

        $this->loadTemplateConfiguration ();
    }

    /**
     * Load the template configuration by generating a
     * ArrayBrowser instance with the template.yml
     * config file int the template directory.
     */
    protected function loadTemplateConfiguration ()
    {
        if ( isNull ($this->templatesFolder) )
        {
            throw new AlertException (
            "No template folder have been specified with setTemplateFolder(folder)", 1);
        }

        // The path to the template folder configuration file
        $templateConfigPath = $this->templatesFolder . 'template.yml';

        if ( file_exists ($templateConfigPath) )
        {
            $tplOpts = Spyc::YAMLLoad ($templateConfigPath);
            $this->templatesConfig = new ArrayBrowser ($tplOpts);
        }
        else
        {
            throw new AlertException (
            "Unable to load the templates configuration file."
            . "Verify the syntax of your YAML file (searching into :"
            . " '{$templateConfigPath}')", 1
            );
        }
    }

    /**
     * Will generate an array containing the configuration options of the 
     * template engine, based on the options specified in the configuration file.
     * This array will then be passed Twig_Environment during its instantiation.
     * 
     * @return array the configuration options of the template engine
     * 
     * @see Twig_Environment
     */
    final protected function getTemplateLoaderOptionsArray ()
    {
        /**
         * On charge les options de configuration du moteur de templates
         * en fonction des options définies dans le fichier de configuration
         */
        $tplConfig = array ();

        if ( Core::opts ()->templates->cache_enabled )
        {
            $tplConfig['cache'] = Core::opts ()->templates->cache_path;
        }
        if ( Core::opts ()->system->mode == 'DEBUG' )
        {
            $tplConfig['debug'] = true;
        }

        return $tplConfig;
    }

    /**
     * Will return an array containing the template(s) folder(s) path
     * @return array the template(s) folder(s) path
     */
    final protected function getTemplatePathsArray ()
    {
        /**
         * On récupère depuis le fichier de configuration les
         * chemins des dossiers contenant les templates,
         * C'est à dire le chemin des templates utilisateurs (les vues)
         * et le chemin des templates Système.
         */
        $tplPaths = array (
            Core::opts ()->templates->user_path,
            $this->templatesFolder . DIRECTORY_SEPARATOR . $this->templatesConfig->twig_path
        );

        return $tplPaths;
    }

    /**
     * Will initialize the controller
     */
    final protected function initialize ()
    {


        $this->template = strtolower (get_class ($this)) . '.twig';
        /* if ( isset ($_GET['ajax']) )
          {
          $this->ajax = true;
          } */

        $this->getMainTwigEngine ();

        $this->initializeWebData ();

        // Le template par défaut à étendre est défini dans le fichier de conf
    }

    /**
     * Will initialize the main twig engine.
     */
    final protected function getMainTwigEngine ()
    {
        $this->loader = new Twig_Loader_Filesystem (
                $this->getTemplatePathsArray ()
        );

        $this->twig = new Twig_Environment (
                $this->loader, $this->getTemplateLoaderOptionsArray ()
        );
        $escaper = new Twig_Extension_Escaper (false);
        $this->twig->addExtension ($escaper);

        $this->twig->addExtension (new MoonTwig ());

        if ( Core::opts ()->system->mode == 'DEBUG' )
        {
            $this->twig->addExtension (new Twig_Extension_Debug ());
        }
    }

    /**
     * Reload all the twig engine. 
     * This operation can be heavy, to use with caution.
     */
    final protected function reloadMainTwigEngine ()
    {

        $this->loader = new Twig_Loader_Filesystem (
                $this->getTemplatePathsArray ()
        );

        $this->twig = new Twig_Environment (
                $this->loader, $this->getTemplateLoaderOptionsArray ()
        );
        $escaper = new Twig_Extension_Escaper (false);
        $this->twig->addExtension ($escaper);

        $this->twig->addExtension (new MoonTwig ());

        if ( Core::opts ()->system->mode == 'DEBUG' )
        {
            $this->twig->addExtension (new Twig_Extension_Debug ());
        }
    }

    /**
     * Create basic webdata, containing all the scripts and stylesheets paths,
     * the site root path, and some configuration informations (options.info).
     * <b>Note that this method will override all previously defined webdata.</b>
     */
    final protected function initializeWebData ()
    {
        $this->webdata = array ();
        $this->webdata['info'] = Core::opts ()->info->childs ();
        $this->webdata['base'] = Core::opts ()->system->siteroot;
        $this->webdata['stylesheets'] = array ();
        $this->webdata['scripts'] = array ();
    }

    /**
     * Add the given stylesheet to the template
     * @param string $name the stylesheet name, without the path.
     */
    final public function addCss ($name)
    {
        if ( file_exists ($name) )
        {
            $this->webdata['stylesheets'][] = $name;
        }
        else if ( file_exists (Core::opts ()->system->siteroot
                        . Core::opts ()->templates->stylesheets_path
                        . $name) )
        {
            $this->webdata['stylesheets'][] = Core::opts ()->system->siteroot
                    . Core::opts ()->templates->stylesheets_path
                    . $name;
        }
        else if ( file_exists (Core::opts ()->templates->stylesheets_path
                        . $name) )
        {
            $this->webdata['stylesheets'][] = Core::opts ()->templates->stylesheets_path
                    . $name;
        }
        else if ( file_exists (Core::opts ()->templates->stylesheets_path
                        . $name . '.css') )
        {
            $this->webdata['stylesheets'][] = Core::opts ()->templates->stylesheets_path
                    . $name . '.css';
        }
        else if ( file_exists (
                        $this->templatesFolder
                        . $this->templatesConfig->stylesheets_path
                        . $name . '.css') )
        {
            $this->webdata['stylesheets'][] = $this->templatesFolder
                    . $this->templatesConfig->stylesheets_path
                    . $name . '.css';
        }
        else if ( file_exists (
                        $this->templatesFolder . DIRECTORY_SEPARATOR
                        . $this->templatesConfig->stylesheets_path
                        . $name) )
        {
            $this->webdata['stylesheets'][] = $this->templatesFolder
                    . DIRECTORY_SEPARATOR
                    . $this->templatesConfig->stylesheets_path
                    . $name;
        }
        else
        {
            /** @TODO : Retirer cet echo malfaisant, et gerer une exception. */
            /* echo "il semblerait que " . Core::opts()->system->siteroot
              . Core::opts()->templates->stylesheets_path
              . $name . " ne soit pas un fichier..."; */
            dbg ("la feuille de style [$name] est introuvable.", 1);
        }
    }

    /**
     * Add the given script to the template
     * @param string $name the script name, without the path.
     */
    final public function addJs ($name)
    {
        if ( file_exists ($name) )
        {
            $this->webdata['scripts'][] = $name;
        }
        else if ( file_exists (Core::opts ()->system->siteroot
                        . Core::opts ()->templates->js_path
                        . $name) )
        {
            $this->webdata['scripts'][] = Core::opts ()->system->siteroot
                    . Core::opts ()->templates->js_path
                    . $name;
        }
        else if ( file_exists (Core::opts ()->templates->js_path
                        . $name) )
        {
            $this->webdata['scripts'][] = Core::opts ()->templates->js_path
                    . $name;
        }
        else if ( file_exists (Core::opts ()->templates->js_path
                        . $name . '.js') )
        {
            $this->webdata['scripts'][] = Core::opts ()->templates->js_path
                    . $name . '.js';
        }
        else if ( file_exists (
                        $this->templatesFolder . DIRECTORY_SEPARATOR
                        . $this->templatesConfig->js_path
                        . $name) )
        {
            $this->webdata['scripts'][] = $this->templatesFolder
                    . DIRECTORY_SEPARATOR
                    . $this->templatesConfig->js_path
                    . $name;
        }
        else if ( file_exists (
                        $this->templatesFolder
                        . $this->templatesConfig->js_path
                        . $name . '.js') )
        {
            $this->webdata['scripts'][] = $this->templatesFolder
                    . $this->templatesConfig->js_path
                    . $name . '.js';
        }
        else
        {
            /** @TODO : Retirer cet echo malfaisant, et gerer une exception. */
            /* echo "il semblerait que " . Core::opts()->system->siteroot
              . Core::opts()->templates->js_path
              . $name . " ne soit pas un fichier..."; */

            dbg ("le script [$name] est introuvable.", 1);
        }
    }

    /**
     * Will define wich styleshhets and scripts will be added, in addition of
     * all the template define resources.
     * --> <b>Override it</b> in your controllers to include the sames files
     * in your templates.
     */
    protected function addTemplateUserIncludes ()
    {
        
    }

    /**
     * Will load all the css and js dependancies of the selected template.
     */
    protected function addTemplateBaseIncludes ()
    {

        foreach ($this->templatesConfig->css_includes as $key => $value)
        {
            $this->addCss ($value);
        }

        foreach ($this->templatesConfig->js_includes as $key => $value)
        {
            $this->addJs ($value);
        }
    }

    /**
     * The css / js includes needed if we are in development mode.
     */
    protected function addDebugIncludes ()
    {
        if ( Core::getInstance ()->debug () )
        {
            $this->addCss ('../System/Classes/Debug/debug.css');
            $this->addJs ('../System/Classes/Debug/debug.js');
        }
    }

    /**
     * Will add additional webdata, such as POST, GET, SESSION
     * or USER.
     */
    final protected function loadWebData ()
    {
        if ( Core::getInstance ()->debug () )
        {
            $this->webdata['debug'] = Core::getInstance ()->debug ()->showReport ();
        }
        $this->webdata['user'] = Core::getUser ();
        $this->webdata['get'] = $_GET;/** @todo Remove direct access to get superglobal */
        $this->webdata['post'] = $_POST;/** @todo Remove direct access to post superglobal */
        $this->webdata['session'] = $_SESSION;
        $this->webdata['logged'] = Core::getUser () != null;
        $this->webdata['duration'] = Profiler::getElapsedTime ();
        $this->webdata['ajax'] = $this->ajax;
        $this->webdata['fromAjax'] = $this->fromAjax;
    }

    /*
      Ajoute une donnée aux autres données qui seront transmises à la vue.
      @param string $key le nom de la donnée. Ce nom sera ensuite utilisé
      dans la vue / template pour récuperer la valeur de la donnée.
      @param mixed $data la donnée à stocker.
      @param boolean $strict définit si oui ou non on ajoute la donnée si elle
      est vide, nulle, ou égale à 0;
      @return Controller L'instance courante
     */

    /**
     * Adds data to all the other data that will be passed to the view.
     * @param string $key the name of the data. This name will then be used in the view / template to retrieve the value of the data.
     * @param mixed $data the data to be stored.
     * @param boolean $strict defines whether or not the data is added if it is empty, void, or equal to 0;
     * @return Controller The current Controller instance
     */
    final public function addData ($key, $data, $strict = True)
    {
        if ( $strict )
        {
            if ( !isNull ($data) )
            {
                $this->data[$key] = $data;
            }
        }
        else
        {
            $this->data[$key] = $data;
        }
        return $this;
    }

    /**
     * Merge the Data and the System ifnormations and return them into a single array.
     * @return array the final array, containing data and webdata
     */
    final protected function mergeData ()
    {
        $this->loadWebData ();

        $tab = $this->data;
        $tab['webdata'] = $this->webdata;
        return $tab;
    }

    /**
     * Defines a base template for the Controller methods.
     * @param string $template the base template name
     */
    final public function setBaseTemplate ($template)
    {
        $this->webdata['template_extend'] = $template;
    }

    /**
     * Defines the template to use.
     * @param string $template the name of the template to use, without
     * folder path.
     */
    final protected function setTemplate ($template)
    {
        $this->definedTemplate = true;
        $this->template = $template;
    }

    /**
     * sets the Url params.
     * @param mixed $params the params to set
     * @return \Controller the current instance
     */
    public function setUrlParams ($params)
    {
        $this->urlParams = $params;
        return $this;
    }

    /**
     * @return array the url parameters.
     * Url parameters are all url elements
     * given after the ? symbol.
     */
    public function getUrlParams ()
    {
        return $this->urlParams;
    }

    public function __toString ()
    {
        return "[Controller] > " . get_class ($this);
    }

    /**
     * @return string the specified url parameter.
     */
    public function getUrlParam ($param = null)
    {
        if ( $param == null )
            return $this->urlParams;
        else if ( !array_key_exists ($param, $this->urlParams) )
            return null;
        return $this->urlParams[$param];
    }

    /**
     * Redirect the user to the specified path.
     * As usual, path must have a form like :
     * <Controller>.<Method> (like "Home.index")
     */
    public function redirect ($path = null, $options = array (), $urlParams = array ())
    {
        if ( isNull ($path) )
        {
            return Core::getRouter ()->callDefaultController ($options, $urlParams);
        }
        else
        {
            return Core::getRouter ()->callController ($path, $options, $urlParams);
        }
    }

    final protected function getTemplateFileName ()
    {
        $index = 0;
        $trace = debug_backtrace ();
        foreach ($trace as $step)
        {
            if ( strcasecmp ($step['function'], 'getTemplateFileName') != 0 && strcasecmp ($step['function'], 'render') != 0 )
            {
                return get_class ($this) . '.' . $trace[$index]['function'];
            }
            $index++;
        }
        return false;
    }

    public abstract function index ($params);

    final private function tryToRender ()
    {
        try
        {
            echo $this->twig->render ($this->template, $this->mergeData ());
        }
        catch (Twig_Error_Loader $exc)
        {
            try
            {
                echo $this->twig->render (strtolower ($this->template), $this->mergeData ());
            }
            catch (Twig_Error_Loader $excTwo)
            {
                dbg ($excTwo->getMessage (), 2);
                dbg ("La template {$this->template} n'a pas pu etre chargée ! Le fichier existe t'il ?", 1);
                dbg ("Chargement de la template de base du controlleur", 0);
                $this->template = strtolower (get_class ($this)) . '.twig';
                echo $this->twig->render (strtolower ($this->template), $this->mergeData ());
            }
        }
    }

    protected function rejectAccess ($why = "Access Denied")
    {
        throw new MemberAccessException ($why);
    }

    public function isAccessGranted ()
    {
        return $this->grantAccess ();
    }

    protected function getRenderedHtml ()
    {
        // Check if the user have enought permissions
        if ( !$this->grantAccess () )
            throw new MemberAccessException ("Access Denied");

        if ( $this->template == false )
        {
            $this->template = $this->getTemplateFileName ();
        }

        $this->template .= '.twig';

        // Add the includes
        $this->addTemplateBaseIncludes ();
        $this->addTemplateUserIncludes ();
        $this->addDebugIncludes ();

        Profiler::endTimer ();
        // And return the rendered html
        return $this->twig->render ($this->template, $this->mergeData ());
    }

    final public function render ()
    {
        // Check if the user have enought permissions
        if ( !$this->grantAccess () )
            throw new MemberAccessException ("Access Denied");

        if ( $this->definedTemplate == false )
        {
            $this->template = $this->getTemplateFileName ();

            if ( $this->template == false )
            {
                dbg ("le template de destination n'a pas pu etre calcule...", 2);
                $this->template = strtolower (get_class ($this)) . '.twig';
            }
            else
            {
                $this->template .= '.twig';
            }
        }
        else
        {
            $this->template .= '.twig';
        }

        // Add the includes
        $this->addTemplateBaseIncludes ();
        $this->addTemplateUserIncludes ();
        $this->addDebugIncludes ();

        Profiler::endTimer ();
        // And try to render
        $this->tryToRender ();
    }

    final public function jsonRender ()
    {
        echo json_encode ($this->data);
    }

    protected function grantAccess ()
    {
        return True;
    }

}

?>
