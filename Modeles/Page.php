<?php

class Page extends Entity{

    protected $id;
    protected $nom;
    protected $nom_complet;
    protected $description;
    protected $droit;
    protected $categorie;
    protected $bdd;

    /**
     * Constructeur
     * @param type $idMembre Identifiant de la page
     * @param type $_bdd La bdd de connexion
     */
    public function __construct($id, $_bdd) {
        $this->bdd = $_bdd;
        try {
            $Req = $_bdd->prepare("SELECT * FROM pages_lw WHERE id_page LIKE ?");
            $Req->execute(array($id));
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        if ($Req->rowCount() == 1) {
            while ($res = $Req->fetch(PDO::FETCH_NUM)) {
                $this->id = $res[0];
                $this->nom = $res[1];
                $this->nom_complet = $res[2];
                $this->description = $res[3];
                $this->droit = $res[4];
                $this->categorie = $res[5];
            }
        }
        else
            die('<div style="font-weight:bold; color:red">Erreur : page multiple ou inconnue ! ID=' . $id . '</div>');
    }

    /**
     * Supprime la page de la bdd
     */
    public function supprBdd() {
        try {
            $Req = $this->bdd->prepare("
            DELETE FROM pages_lw WHERE id_page=?");
            $Req->execute(array($this->id));
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
    }

    /**
     * Met la bdd a jour
     */
    public function majBdd() {
        try {
            $d = $this->bdd->prepare("
                UPDATE
                    pages_lw
                SET
                    nom_page=?,
                    nom_complet_page=?,
                    description_page=?,
                    droit_page=?,
                    categorie_page=?
                WHERE
                    id_page=?
                  ");
            $d->execute(array(
                $this->nom,
                $this->nom_complet,
                $this->description,
                $this->droit,
                $this->categorie,
                $this->id
            ));
        } catch (Exception $e) { //interception de l'erreur
            die('Erreur : ' . $e->getMessage() . '<br />');
        }
    }

    public static function getIdPage($nom, $bdd) {
        try {
            $Req = $bdd->prepare("SELECT id_page FROM pages_lw WHERE nom_page LIKE ?");
            $Req->execute(array($nom));
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        if ($Req->rowCount() == 1) {
            $res = $Req->fetch(PDO::FETCH_OBJ);
            return $res->id_page;
        }
    }

    public static function pageExiste($nom, $bdd) {
        try {
            $Req = $bdd->prepare("SELECT id_page FROM pages_lw WHERE nom_page LIKE ?");
            $Req->execute(array($nom));
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        if ($Req->rowCount() >= 1) {
            return true;
        }
        else
            return false;
    }

    public static function getAllPages($bdd, $categorie = "", $disable=array('accueil')) {
        $r = array();
        $s = "";
        if(count($disable) > 0)
            $s .= " WHERE ";
        $cou = count($disable);
        $p = 0;
        foreach ($disable as $value) {
            $p = $p + 1;
            $s .= 'nom_page NOT LIKE \'' . $value .'\' ';
            if($p != $cou){
                $s .= ' AND ';
            }
        }
        if ($categorie != "")
            if($cou > 0)
                $s .= ' AND nom_categorie_page LIKE \'' . $categorie .'\'';
            else
                $s .= ' WHERE nom_categorie_page LIKE \'' . $categorie .'\'';
        try {
            $Req = $bdd->prepare('
                    SELECT * FROM pages_lw 
                    INNER JOIN categorie_page_lw 
                    ON pages_lw.categorie_page = categorie_page_lw.id_categorie_page' . $s);
            $Req->execute();
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $r[] = $res;
        }

        return $r;
    }

    public static function getMenu($bdd, $disable = array('accueil')) {
        $e = "";
        $categs = Page::getAllPagesCategories($bdd);
        foreach ($categs as $categ) {

            $pages = Page::getAllPages($bdd,$categ->nom_categorie_page,$disable);
            if (count($pages) > 0) {
                if($categ->nom_categorie_page != "Aucune")
                    $e .= '<li class="nav-header">'.$categ->nom_categorie_page.'</li>';
                foreach ($pages as $page) {
                    $e .= '<li><a href="index.php?p='.$page->nom_page.'">'.$page->nom_complet_page.'</a></li>';
                }
            }
        }
        return $e;
    }

    public static function getAllPagesCategories($bdd) {
        $r = array();
        try {
            $Req = $bdd->prepare("SELECT * FROM categorie_page_lw");
            $Req->execute();
        } catch (Exception $e) { //interception de l'erreur
            die('<div style="font-weight:bold; color:red">Erreur : ' . $e->getMessage() . '</div>');
        }
        while ($res = $Req->fetch(PDO::FETCH_OBJ)) {
            $r[] = $res;
        }

        return $r;
    }

    /**
     * Met la bdd a jour
     */
    public static function ajouterPage($nom, $bdd, $droit = 1, $nom_complet = "", $description = "", $categorie = 1) {
        if (!Page::pageExiste($nom, $bdd)) {
            try {
                $d = $bdd->prepare("
                INSERT INTO
                    pages_lw (
                        `id_page` ,
                        `nom_page` ,
                        `nom_complet_page` ,
                        `description_page` ,
                        `droit_page`,
                        `categorie_page` 
                    )
                    VALUES (
                    NULL,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?)");
                $d->execute(array(
                    $nom,
                    $nom_complet,
                    $description,
                    $droit,
                    $categorie
                ));
            } catch (Exception $e) { //interception de l'erreur
                die('Erreur : ' . $e->getMessage() . '<br />');
            }
            return true;
        }
        else
            return false;
    }
    
    public function getDroit(){
        return $this->droit;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getNom_complet() {
        return $this->nom_complet;
    }

    public function setNom_complet($nom_complet) {
        $this->nom_complet = $nom_complet;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getCategorie() {
        return $this->categorie;
    }

    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }

    public function getBdd() {
        return $this->bdd;
    }

    public function setBdd($bdd) {
        $this->bdd = $bdd;
    }

    protected function chargerBdd() {
        
    }

    protected function majField($field,$value) {
        
    }



}

?>
