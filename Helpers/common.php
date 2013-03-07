<?php

/**
 * Les fonctions usuelles qui peuvent toujours servir
 */
// Renvoie les $nbCarac premiers caractères d'une chaine
function abrevString($String, $nbCarac = 140, $fin = "...") {
    if (strlen($String) > (int) $nbCarac) {
        $pos = strpos($String, ' ', (int) $nbCarac);
        $String = substr($String, 0, $pos);
        $String = $String . '...';
    }
    return $String;
}

function isRecent($date, $nbJours = 2) {
    $e = date_create_from_format("Y-m-d", $date);
    $now = new DateTime();
    $ecoule = date_diff($now, $e, true);

    if ((int) $ecoule->days < (int) $nbJours)
        return true;
    else
        return false;
}

function getCoolDate($date) {
    $e = date_create_from_format("Y-m-d", $date);
    if ($e == false)
        return $e;
    $now = new DateTime();
    $ecoule = date_diff($now, $e, true);
    $jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
    $mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

    $datefr = $jour[$e->format("w")] . " " . $e->format("d") . " " . $mois[$e->format("n")] . " " . $e->format("Y");

    if ((int) $ecoule->days == 0)
        return "Aujourd'hui";
    else if ((int) $ecoule->days == 1)
        return "Hier";
    else if ((int) $ecoule->days == 7)
        return "Il y a une semaine";
    else if ((int) $ecoule->days < 7)
        return 'Il y a ' . $ecoule->days . ' jours';
    else
        return 'Le ' . strtolower($datefr);
}

function getDepartements() {
    $depts = array();
    $depts["01"] = "01 - Ain";
    $depts["02"] = "02 - Aisne";
    $depts["03"] = "03 - Allier";
    $depts["04"] = "04 - Alpes de Haute Provence";
    $depts["05"] = "05 - Hautes Alpes";
    $depts["06"] = "06 - Alpes Maritimes";
    $depts["07"] = "07 - Ardèche";
    $depts["08"] = "08 - Ardennes";
    $depts["09"] = "09 - Ariège";
    $depts["10"] = "10 - Aube";
    $depts["11"] = "11 - Aude";
    $depts["12"] = "12 - Aveyron";
    $depts["13"] = "13 - Bouches du Rhône";
    $depts["14"] = "14 - Calvados";
    $depts["15"] = "15 - Cantal";
    $depts["16"] = "16 - Charente";
    $depts["17"] = "17 - Charente Maritime";
    $depts["18"] = "18 - Cher";
    $depts["19"] = "19 - Corrèze";
    $depts["2A"] = "2A - Corse du Sud";
    $depts["2B"] = "2B - Haute Corse";
    $depts["21"] = "21 - Côte d'Or";
    $depts["22"] = "22 - Côtes d'Armor";
    $depts["23"] = "23 - Creuse";
    $depts["24"] = "24 - Dordogne";
    $depts["25"] = "25 - Doubs";
    $depts["26"] = "26 - Drôme";
    $depts["27"] = "27 - Eure";
    $depts["28"] = "28 - Eure et Loir";
    $depts["29"] = "29 - Finistère";
    $depts["30"] = "30 - Gard";
    $depts["31"] = "31 - Haute Garonne";
    $depts["32"] = "32 - Gers";
    $depts["33"] = "33 - Gironde";
    $depts["34"] = "34 - Hérault";
    $depts["35"] = "35 - Ille et Vilaine";
    $depts["36"] = "36 - Indre";
    $depts["37"] = "37 - Indre et Loire";
    $depts["38"] = "38 - Isère";
    $depts["39"] = "39 - Jura";
    $depts["40"] = "40 - Landes";
    $depts["41"] = "41 - Loir et Cher";
    $depts["42"] = "42 - Loire";
    $depts["43"] = "43 - Haute Loire";
    $depts["44"] = "44 - Loire Atlantique";
    $depts["45"] = "45 - Loiret";
    $depts["46"] = "46 - Lot";
    $depts["47"] = "47 - Lot et Garonne";
    $depts["48"] = "48 - Lozère";
    $depts["49"] = "49 - Maine et Loire";
    $depts["50"] = "50 - Manche";
    $depts["51"] = "51 - Marne";
    $depts["52"] = "52 - Haute Marne";
    $depts["53"] = "53 - Mayenne";
    $depts["54"] = "54 - Meurthe et Moselle";
    $depts["55"] = "55 - Meuse";
    $depts["56"] = "56 - Morbihan";
    $depts["57"] = "57 - Moselle";
    $depts["58"] = "58 - Nièvre";
    $depts["59"] = "59 - Nord";
    $depts["60"] = "60 - Oise";
    $depts["61"] = "61 - Orne";
    $depts["62"] = "62 - Pas de Calais";
    $depts["63"] = "63 - Puy de Dôme";
    $depts["64"] = "64 - Pyrénées Atlantiques";
    $depts["65"] = "65 - Hautes Pyrénées";
    $depts["66"] = "66 - Pyrénées Orientales";
    $depts["67"] = "67 - Bas Rhin";
    $depts["68"] = "68 - Haut Rhin";
    $depts["69"] = "69 - Rhône";
    $depts["70"] = "70 - Haute Saône";
    $depts["71"] = "71 - Saône et Loire";
    $depts["72"] = "72 - Sarthe";
    $depts["73"] = "73 - Savoie";
    $depts["74"] = "74 - Haute Savoie";
    $depts["75"] = "75 - Paris";
    $depts["76"] = "76 - Seine Maritime";
    $depts["77"] = "77 - Seine et Marne";
    $depts["78"] = "78 - Yvelines";
    $depts["79"] = "79 - Deux Sèvres";
    $depts["80"] = "80 - Somme";
    $depts["81"] = "81 - Tarn";
    $depts["82"] = "82 - Tarn et Garonne";
    $depts["83"] = "83 - Var";
    $depts["84"] = "84 - Vaucluse";
    $depts["85"] = "85 - Vendée";
    $depts["86"] = "86 - Vienne";
    $depts["87"] = "87 - Haute Vienne";
    $depts["88"] = "88 - Vosges";
    $depts["89"] = "89 - Yonne";
    $depts["90"] = "90 - Territoire de Belfort";
    $depts["91"] = "91 - Essonne";
    $depts["92"] = "92 - Hauts de Seine";
    $depts["93"] = "93 - Seine St Denis";
    $depts["94"] = "94 - Val de Marne";
    $depts["95"] = "95 - Val d'Oise";
    $depts["97"] = "97 - DOM";

    return $depts;
}

function getNomDepartement($numDept) {
    $depts = array();
    $depts["01"] = "01 - Ain";
    $depts["02"] = "02 - Aisne";
    $depts["03"] = "03 - Allier";
    $depts["04"] = "04 - Alpes de Haute Provence";
    $depts["05"] = "05 - Hautes Alpes";
    $depts["06"] = "06 - Alpes Maritimes";
    $depts["07"] = "07 - Ardèche";
    $depts["08"] = "08 - Ardennes";
    $depts["09"] = "09 - Ariège";
    $depts["10"] = "10 - Aube";
    $depts["11"] = "11 - Aude";
    $depts["12"] = "12 - Aveyron";
    $depts["13"] = "13 - Bouches du Rhône";
    $depts["14"] = "14 - Calvados";
    $depts["15"] = "15 - Cantal";
    $depts["16"] = "16 - Charente";
    $depts["17"] = "17 - Charente Maritime";
    $depts["18"] = "18 - Cher";
    $depts["19"] = "19 - Corrèze";
    $depts["2A"] = "2A - Corse du Sud";
    $depts["2B"] = "2B - Haute Corse";
    $depts["21"] = "21 - Côte d'Or";
    $depts["22"] = "22 - Côtes d'Armor";
    $depts["23"] = "23 - Creuse";
    $depts["24"] = "24 - Dordogne";
    $depts["25"] = "25 - Doubs";
    $depts["26"] = "26 - Drôme";
    $depts["27"] = "27 - Eure";
    $depts["28"] = "28 - Eure et Loir";
    $depts["29"] = "29 - Finistère";
    $depts["30"] = "30 - Gard";
    $depts["31"] = "31 - Haute Garonne";
    $depts["32"] = "32 - Gers";
    $depts["33"] = "33 - Gironde";
    $depts["34"] = "34 - Hérault";
    $depts["35"] = "35 - Ille et Vilaine";
    $depts["36"] = "36 - Indre";
    $depts["37"] = "37 - Indre et Loire";
    $depts["38"] = "38 - Isère";
    $depts["39"] = "39 - Jura";
    $depts["40"] = "40 - Landes";
    $depts["41"] = "41 - Loir et Cher";
    $depts["42"] = "42 - Loire";
    $depts["43"] = "43 - Haute Loire";
    $depts["44"] = "44 - Loire Atlantique";
    $depts["45"] = "45 - Loiret";
    $depts["46"] = "46 - Lot";
    $depts["47"] = "47 - Lot et Garonne";
    $depts["48"] = "48 - Lozère";
    $depts["49"] = "49 - Maine et Loire";
    $depts["50"] = "50 - Manche";
    $depts["51"] = "51 - Marne";
    $depts["52"] = "52 - Haute Marne";
    $depts["53"] = "53 - Mayenne";
    $depts["54"] = "54 - Meurthe et Moselle";
    $depts["55"] = "55 - Meuse";
    $depts["56"] = "56 - Morbihan";
    $depts["57"] = "57 - Moselle";
    $depts["58"] = "58 - Nièvre";
    $depts["59"] = "59 - Nord";
    $depts["60"] = "60 - Oise";
    $depts["61"] = "61 - Orne";
    $depts["62"] = "62 - Pas de Calais";
    $depts["63"] = "63 - Puy de Dôme";
    $depts["64"] = "64 - Pyrénées Atlantiques";
    $depts["65"] = "65 - Hautes Pyrénées";
    $depts["66"] = "66 - Pyrénées Orientales";
    $depts["67"] = "67 - Bas Rhin";
    $depts["68"] = "68 - Haut Rhin";
    $depts["69"] = "69 - Rhône";
    $depts["70"] = "70 - Haute Saône";
    $depts["71"] = "71 - Saône et Loire";
    $depts["72"] = "72 - Sarthe";
    $depts["73"] = "73 - Savoie";
    $depts["74"] = "74 - Haute Savoie";
    $depts["75"] = "75 - Paris";
    $depts["76"] = "76 - Seine Maritime";
    $depts["77"] = "77 - Seine et Marne";
    $depts["78"] = "78 - Yvelines";
    $depts["79"] = "79 - Deux Sèvres";
    $depts["80"] = "80 - Somme";
    $depts["81"] = "81 - Tarn";
    $depts["82"] = "82 - Tarn et Garonne";
    $depts["83"] = "83 - Var";
    $depts["84"] = "84 - Vaucluse";
    $depts["85"] = "85 - Vendée";
    $depts["86"] = "86 - Vienne";
    $depts["87"] = "87 - Haute Vienne";
    $depts["88"] = "88 - Vosges";
    $depts["89"] = "89 - Yonne";
    $depts["90"] = "90 - Territoire de Belfort";
    $depts["91"] = "91 - Essonne";
    $depts["92"] = "92 - Hauts de Seine";
    $depts["93"] = "93 - Seine St Denis";
    $depts["94"] = "94 - Val de Marne";
    $depts["95"] = "95 - Val d'Oise";
    $depts["97"] = "97 - DOM";

    if ($numDept <= count($depts)) {
        return $depts[$numDept];
    }
}

function isNumTelephone($num) {
    return preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", htmlspecialchars($num));
}

function isEmailAdress($email) {
    return preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", htmlspecialchars($email));
}

function formaterNumeroTel($numTel) {
    $i = 0;
    $j = 0;
    $formate = "";
    while ($i < strlen($numTel)) { //tant qu il y a des caracteres
        if ($j < 2) {
            if (preg_match('/^[0-9]$/', $numTel[$i])) { //si on a bien un chiffre on le garde
                $formate .= $numTel[$i];
                $j++;
            }
            $i++;
        } else { //si on a mis 2 chiffres a la suite on met un espace
            $formate .= ".";
            $j = 0;
        }
    }
    return $formate;
}

function definirLangue($langue) {
    header('Location: index.php');
    setcookie('lang', $langue, time() + 365 * 24 * 3600, null, null, false, true);
}

function chargerLangue() {
    $langFile = "fr.php";
    $lg = array("fr", "en");
    if (isset($_COOKIE['lang'])) {
        if (in_array($_COOKIE['lang'], $lg))
            $langFile = $_COOKIE['lang'] . ".php";
    }
    return $langFile;
}

function generateLink($link, $withoutHttp = true) {
    if (substr($link, 0, 3) == "www")
        $link = 'http://' . $link;
    if ($withoutHttp)
        return '<a href="' . $link . '">' . substr($link, 7) . '</a>';
    else
        return '<a href="' . $link . '">' . $link . '</a>';
}

function dg($string) {
    echo('<pre class="dg">' . $string . '</pre>');
}

/**
 * Renvoie true si l'objet fourni est null, vide, ou égal à ""
 */
function isNull($object) {
    $nul = false;
    if ($object == null)
        $nul = true;
    else if (is_string($object) && $object == "")
        $nul = true;
    else if (is_array($object) && count($object) == 0)
        $nul = true;
    return $nul;
}

# remove by key:

function array_remove_key($tab, $key) {
    foreach ($tab as $k => $v) {
        if(strcmp($key, $k) == 0){
            unset($tab[$k]);
        }
    }
    return $tab;
}

# remove by value:

function array_remove_value($tab, $value) {
    $args = func_get_args();
    return array_diff($args[0], array_slice($args, 1));
}

/**
 * Retourne la chaine ou le tableau passé en paramètre
 * entouré de doubles quotes ("). Si l'élément est un tableau,
 * chaque élément sera séparé par le séparateur défini.
 * (Par défaut, le séparateur est un espace)
 * @param string/Array $string la chaine ou le tableau de chaines a double quoter
 * @param string $separator le séparateur si l'élement est un tableau
 * @return string la chaine ou le tableau double quoté
 */
function dbQuote($string, $separator = " ") {
    $r = '"';
    if (is_array($string)) {
        foreach ($string as $elem)
            $r .= $elem . $separator;
    }
    else
        $r .= $string;
    $r .= '"';
    return $r;
}

?>