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
            new Twig_SimpleFunction('updateForm','MoonTwig::updateForm')
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
        $pl = explode('.',$str);
        if(strcmp($text,'') == 0){
            $text = $pl[0];
        }
        $str = Core::opts()->system->siteroot.implode(DIRECTORY_SEPARATOR, $pl);
        return '<a href="'.$str.'">' . $text . '</a>';
    }
    
    /**
     * Retourne juste l'adresse du controleur spécifié.
     * @param string $str la reference du controleur [Classe].[Methode]
     * @return string le lien. Pratique pour créer des href un peu personnalisés
     */
    public static function moonHref($str) {
        $pl = explode('.',$str);
        $str = Core::opts()->system->siteroot.implode(DIRECTORY_SEPARATOR, $pl);
        return $str;
    }

    /**
     * Permet de générer un formulaire d'insertion
     * @param Entity $entity la classe pour laquelle créer le formulaire.
     * @param boolean $ajax activer ou non l'envoi des données en ajax.
     * @return string le code HTML du formulaire.
     */
    public static function insertForm($entity, $ajax=false)
    {
        if(is_string($entity))
        {
            $entity = Moon::create($entity);
        }
        if(!is_a($entity, 'Entity'))
            throw new AlertException(
                "Invalid entity supplied for form generation", 1);

        return $entity->generateInsertForm();
            
    }


    /**
     * Permet de générer un formulaire de mise à jour
     * @param Entity $entity la classe pour laquelle créer le formulaire.
     * @param boolean $ajax activer ou non l'envoi des données en ajax.
     * @return string le code HTML du formulaire.
     */
    public static function updateForm($entity, $ajax=false)
    {
        if(!is_a($entity, 'Entity'))
            throw new AlertException(
                "Invalid entity supplied for form generation", 1);

        return $entity->generateUpdateForm();
            
    }
    
    function getName() {
        'Moon';
    }
}

?>
