<?php

/*
 * This file is part of the Moon Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Classe représentant une extension de twig qui va nous permettre de generer
 * automatiquement des liens vers des controlleurs personnalisés.
 * @author lambda2
 */
class MoonTwig extends Twig_Extension
{
    /**
     * Returns a list of functions to add to the existing list.
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('link','MoonTwig::moonLink',array('is_safe' => array('html'))),
            new Twig_SimpleFunction('href','MoonTwig::moonHref',array('is_safe' => array('html'))),
            new Twig_SimpleFunction('insertForm','MoonTwig::insertForm'),
            new Twig_SimpleFunction('deleteForm','MoonTwig::deleteForm'),
            new Twig_SimpleFunction('updateForm','MoonTwig::updateForm'),
            new Twig_SimpleFunction('getFormOpenTag','MoonTwig::getFormOpenTag'),
            new Twig_SimpleFunction('getFormCloseTag','MoonTwig::getFormCloseTag'),
            new Twig_SimpleFunction('getFormFieldList','MoonTwig::getFormFieldList'),
            new Twig_SimpleFunction('truncate','MoonTwig::truncate'),
            new Twig_SimpleFunction('gravatar','MoonTwig::getGravatar'),
            new Twig_SimpleFunction('plu','MoonTwig::plural'),
            new Twig_SimpleFunction('len','MoonTwig::getCount')
            );
    }
    
    /**
     * Retourne un lien vers le controlleur spécifié
     * @param string $str la reference du controleur [Classe].[Methode]
     * @param type $text le texte du lien (optionnel)
     * @return type le lien HTML sous la forme <a href=moonHref($str)>$text</a>
     * @see MoonTwig::moonHref
     * 
     * @TODO : Ajouter une petite gestion des exceptions. Il est par exemple
     * invraisemblable de mettre un lien avec des nombres ou du genre...
     */
    public static function moonLink($str, $text='') {
        $pl = explode('.',self::getProperVal($str));
        if(strcmp($text,'') == 0){
            $text = $pl[0];
        }
        $str = Core::opts()->system->siteroot.implode(DIRECTORY_SEPARATOR, $pl);
        $opts = array();
        $numOpts = func_num_args();
        if($numOpts > 2)
        {
            $options = func_get_args();
            for($i=2; $i < $numOpts; $i++)
            {
                $opts[] = self::getProperVal($options[$i]);
            }
        }
        $o = '';
        if(count($opts) > 0)
            $o = '/'.implode('/',$opts);

        return '<a href="'.$str.$o.'">' . $text . '</a>';
    }
    
    /**
     * Retourne juste l'adresse du controleur spécifié.
     * @param string $str la reference du controleur [Classe].[Methode]
     * @return string le lien. Pratique pour créer des href un peu personnalisés
     */
    public static function moonHref($str) {
        $pl = explode('.',self::getProperVal($str));
        $str = Core::opts()->system->siteroot.implode(DIRECTORY_SEPARATOR, $pl);
        $opts = array();
        $numOpts = func_num_args();
        if($numOpts > 1)
        {
            $options = func_get_args();
            for($i=1; $i < $numOpts; $i++)
            {
                $opts[] = self::getProperVal($options[$i]);
            }
        }
        $o = '';
        if(count($opts) > 0)
            $o = '/'.implode('/',$opts);
        return $str.$o;
    }

    /**
     * Permet de générer un formulaire d'insertion
     * @param Entity $entity la classe pour laquelle créer le formulaire.
     * @param boolean $ajax activer ou non l'envoi des données en ajax.
     * @return string le code HTML du formulaire.
     */
    public static function insertForm($entity, $label='', $ajax=false)
    {
        $entity = self::convertStringToEntity($entity);
        if(!is_a($entity, 'Entity'))
            throw new AlertException(
                "Invalid entity supplied for form generation", 1);

        return $entity->generateInsertForm('',$label);
            
    }

    public static function getGravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        return getGravatar( $email, $s, $d, $r, $img, $atts);
    }

    /**
     * Permet de générer un formulaire de mise à jour
     * @param Entity $entity la classe pour laquelle créer le formulaire.
     * @param boolean $ajax activer ou non l'envoi des données en ajax.
     * @return string le code HTML du formulaire.
     */
    public static function updateForm($entity, $label='', $ajax=false)
    {
        if(!is_a($entity, 'Entity'))
            throw new AlertException(
                "Invalid entity supplied for form generation", 1);

        return $entity->generateUpdateForm('',$label);
            
    }

    /**
     * Permet de générer un formulaire de supression,
     * qui ne sera en fait constitué que d'un seul bouton 'supprimer'
     * @param Entity $entity la classe pour laquelle créer le formulaire.
     * @param boolean $ajax activer ou non l'envoi des données en ajax.
     * @return string le code HTML du formulaire.
     */
    public static function deleteForm($entity, $label='', $ajax=false)
    {
        if(!is_a($entity, 'Entity'))
            throw new AlertException(
                "Invalid entity supplied for form generation", 1);

        return $entity->generateDeleteForm('',$label);
    }

    public static function truncate($text,$num=100,$end="")
    {
        return truncate($text, $num, $end); 
    }

    /**
     * Generate the html code for the openning of the 
     * form tag for the specified action.
     * ex : [ <form action="#" method="post"> ]
     */
    public static function getFormOpenTag($entity,$action='insert')
    {
        $entity = self::convertStringToEntity($entity);
        return $entity->generateFormFor($action)->getFormOpenTag();
    }

    /**
     * Generate the html code for the closing of the 
     * form tag for the specified action.
     * ex : [ </form> ]
     */
    public static function getFormCloseTag($entity,$action='insert')
    {
        $entity = self::convertStringToEntity($entity);
        return $entity->generateFormFor($action)->getFormCloseTag();
    }

    /**
     * Generate the html code of all the elements
     * contained in the form for the specified action.
     */
    public static function getFormFieldList($entity,$action='insert')
    {
        $entity = self::convertStringToEntity($entity);
        return $entity->generateFormFor($action)->getFormFieldList();
    }

    protected static function convertStringToEntity($str)
    {
        if(is_string($str))
        {
            $str = Moon::create($str);
        }
        return $str;
    }

    protected static function getProperVal($obj)
    {
        if(is_a($obj,'EntityField'))
        {
            return $obj->getValue();
        }
        else
        {
            return $obj;
        }
    }

    public static function plural($str,$obj,$ext='s',$before=null,$none=null)
    {
        $nb = count($obj);
        if($nb > 1)
        {
            if($before)
                return $before.$nb.' '.$str.$ext;
            else
                return $nb.' '.$str.$ext;
        }
        else if($nb == 0)
        {
            if($none)
                return $none.' '.$str;
            else if($before)
                return $before.$nb.' '.$str;
            else
                return $nb.' '.$str;
        }
        else {
            if($before)
                return $before.$nb.' '.$str;
            else
                return $nb.' '.$str;
        }
    }

    public static function getCount($obj)
    {
        return count($obj);
    }
    
    function getName() {
        'Moon';
    }
}

?>
