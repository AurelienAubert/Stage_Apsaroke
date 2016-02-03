<?php

    /**
     * met une date au format jj/mm/aaaa depuis le format aaaa-mm-jj[ hh:mm:ss]
     * @param string $date
     */
    function format_date($date) {
        return substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4);
    }
    
    function date2fr( $date ){
        $reps_EN = array( 'Monday' , 'Tuesday' , 'Wednesday' , 'Thursday' , 'Friday' , 'Saturday' , 'Sunday' , '|st|' , '|nd|' , '|rd|' , '|th|' , '||' , 'January' , 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August' , 'September' , 'October' , 'November' , 'December' );
        $reps_FR = array( 'lundi' , 'mardi' , 'mercredi' , 'jeudi' , 'vendredi' , 'samedi' , 'dimanche' , '<sup>er</sup>' , '' , '' , '' , '' , 'janvier' , 'février' , 'mars' , 'avril' , 'mai' , 'juin' , 'juillet' , 'août' , 'septembre' , 'octobre' , 'novembre' , 'décembre' );

        return str_replace( $reps_EN, $reps_FR, date( 'l j|S| F Y' , strtotime( $date ) ) );
        //return str_replace( $reps_EN, $reps_FR, date( 'j|S| F Y' , strtotime( $date ) ) );
    } 

    function creerFieldset($legende, $champs) {
        $retour = '<fieldset> <legend>' . $legende . '</legend><div class="row">';
        if (is_array($champs)) {
            foreach($champs as $champ) {
                $retour .= $champ . "\n";
            }
        }
        else {
            $retour .= $champs;
        }
        return $retour . "</div></fieldset>\n";
    }
    
    /**
     * entoure le(s) champ(s) de row
     * 
     * @param string|array $champ   champ(s) à entourer du row
     * @return string               le HTML du row complet
     */
    function creerRow($champs) {
        $retour = '<div class="row">';
        if (is_array($champs)) {
            foreach ($champs as $champ) {
                $retour .= $champ . "\n";
            }
        }
        else {
            $retour .= $champs;
        }
        return  $retour . '</div>';
    }
    
    function sautLigne() {
        return '<div class="row"><div class="span12"><br></div></div>';
    }
    
    /**
     * entoure le champ des divs pour déterminer sa taille
     * 
     * @param string        $titre          le titre à afficher
     * @param string|array  $champs         le(s) champ(s) à entourer
     * @param int           $tailleTitre    la taille du titre
     * @param int           $tailleChamp    la taille de chaque champ
     * @param string        $classe         classes supplémentaires optionnelles
     * @return string
     */
    function ajouterDiv($titre, $champs, $tailleTitre, $tailleChamp, $classe='') {
        $retour = '<div class="span' . $tailleTitre . ' ' . $classe . '">' . $titre . "</div>\n";
       
        if (is_array($champs)) {
            foreach($champs as $champ) {
                $retour .= '<div class="span' . $tailleChamp .'">' . $champ . "</div>\n";
            }
        }
        else {
            $retour .= '<div class="span' . $tailleChamp .'">' . $champs . "</div>\n";
        }
        return $retour;
    }
    
    /**
     * créé un champ input de type texte ou date
     * 
     * @param string    $titre          le titre des boutons
     * @param string    $nom            l'attribut name
     * @param int       $tailleTitre    la taille du titre
     * @param int       $tailleChamp    la taille du champ
     * @param bool      $requis         l'attribut required (champ facultatif par défaut)
     * @param string    $type           le type de champ : text (par défaut) ou date
     * @param string    $classe         classes supplémentaires optionnelles
     * 
     * @return string           le code HTML de champ
     */
    function input($titre, $nom, $tailleTitre, $tailleChamp, $requis=false, $type='text', $classe='', $clsinput='', $link='') {
        $retour = '<input type="' . $type . '" name="' . $nom . '" ';
        if ($clsinput){
            $retour .= 'class="' . $clsinput . '" ';
        }
        if ($requis) {
            $retour .= 'required="required" ';
        }
        if ($type=='date') {
            $retour .= 'placeholder="AAAA-MM-JJ" ';
            $retour .= (isset($_POST[$nom]) && $_POST[$nom] != '0000-00-00') ? 'value ="' . $_POST[$nom] . '"' : '';
        }else{
            $retour .= isset($_POST[$nom]) ? 'value ="' . $_POST[$nom] . '"' : '';
        }
        
        $retour = ajouterDiv($titre, $retour . '/>', $tailleTitre, $tailleChamp, $classe);

        if($type == 'file'){
            $retour .= '<br>'.afficher_image('', $link, '', '', true);
        }
        return $retour;
    }
    
    /**
     * créé un champ input de type texte en ReadOnly
     * 
     * @param string    $titre          le titre des boutons
     * @param string    $nom            l'attribut name
     * @param int       $tailleTitre    la taille du titre
     * @param int       $tailleChamp    la taille du champ
     * @param string    $type           le type de champ : text (par défaut) ou date
     * 
     * @return string           le code HTML de champ
     */
    function inputRO($titre, $nom, $tailleTitre, $tailleChamp, $type='text', $classe='', $clsinput='') {
        $retour = '<input type="' . $type . '" name="' . $nom . '"  readonly ';
        if ($clsinput){
            $retour .= 'class="' . $clsinput . '" ';
        }
        if ($type=='date') {
            $retour .= 'placeholder="AAAA-MM-JJ" ';
        }
        $retour .= isset($_POST[$nom]) ? 'value ="' . $_POST[$nom] . '"' : '';
        return ajouterDiv($titre, $retour . '/>', $tailleTitre, $tailleChamp, $classe);
    }
    
    /**
     * créé un champ textarea
     * 
     * @param string    $titre          le titre du champ
     * @param string    $nom            l'attribut name
     * @param int       $tailleTitre    la taille du titre
     * @param int       $tailleChamp    la taille du champ
     * @param bool      $requis         l'attribut required (champ facultatif par défaut)
     * @param int       $ligne          le nombre de lignes
     * @param int       $colonne        le nombre de colonnes
     * @param string    $classe         classes supplémentaires optionnelles
     * 
     * @return string           le code HTML de champ
     */
    function textarea($titre, $nom, $tailleTitre, $tailleChamp, $requis=false, $ligne=4, $colonne=40, $classe='') {
        $retour = '<textarea name="' . $nom . '" rows="' . $ligne . '" cols="' . $colonne . '" class="'. $classe .'" ';
        if ($requis) {
            $retour .= 'required="required" ';
        }
        $retour .= ' >';
        $retour .= isset($_POST[$nom]) ? $_POST[$nom] : '';
        $retour .= '</textarea>';
        return ajouterDiv($titre, $retour, $tailleTitre, $tailleChamp, '');
    }
    
    /**
     * créé deux boutons radio de type oui/non
     * @param string    $titre          le titre des boutons
     * @param string    $nom            l'attribut name
     * @param string    $texteOui       le texte allant avec le bouton oui
     * @param string    $texteNon       le texte allant avec le bouton non
     * @param int       $tailleTitre    la taille du titre
     * @param int       $tailleChamp    la taille de chaque bouton
     * @param int       $defaut         la case cochée par défaut (aucune si non précisée ou différente de 0 ou 1)
     * 
     * @return string           le code HTML complet des boutons
     */
    function radio($titre, $nom, $texteOui, $texteNon, $tailleTitre, $tailleChamp, $defaut=2) {
        $noms = array($nom.'_OUI', $nom.'_NON');
        $textes = array($texteNon, $texteOui);
        $boutons = array();
        $valeur = 1;
        
        foreach($noms as $nomBouton) {
            if ((isset($_POST[$nom]) && $_POST[$nom]==$valeur) || (!isset($_POST[$nom]) && $defaut==$valeur)) {
                $checked = 'checked';
            }
            else {
                $checked = '';
            }
            $boutons[] =
                '<label for="' . $nomBouton . '" class="radio">'
                .'<input type="radio" name="' . $nom . '" id="' . $nomBouton . '" value="' . $valeur . '" '
                . $checked . '/>' . $textes[$valeur] . "</label>\n"
            ;
            $valeur--;
        }
        return ajouterDiv($titre, $boutons, $tailleTitre, $tailleChamp);
    }
    
    /**
     * créé un champ select
     * 
     * @param string    $titre          le titre des boutons
     * @param string    $nom            l'attribut name
     * @param array     $option         la liste des choix possibles sous la forme valeur=>texte
     * @param int       $tailleTitre    la taille du titre
     * @param int       $tailleChamp    la taille du champ
     * @return string
     */
    function select($titre, $nom, $options, $tailleTitre, $tailleChamp, $lignevide = false, $defaut = '' ) {
        $retour = '<select name="' . $nom .'">\n';
        if($lignevide == true){
            $retour .= '<option value=""></option>';
        }
        if ($defaut == '' && isset($_POST[$nom])){ $defaut = $_POST[$nom]; }
        foreach ($options as $valeur=>$texte) {
            $retour .= '<option value="' . $valeur . '" ' . ($valeur == $defaut ? 'selected' : '');
            $retour .= '>' . $texte . '</option>';
        }
        return ajouterDiv($titre, $retour . '</select>', $tailleTitre, $tailleChamp);
    }
    
    /**
     * Permet de boucler l'appel d'une méthode
     * 
     * @param string    $nomFonction
     * @param string    $titre
     * @param array     $contenu        Tous le contenu qui doit être affiché
     * @param string    $accesContenu   Clé du tableau associatif
     * @param string    $classeTitre
     * @param string    $classeContenu
     * @param int       $nbExec         Nombre de fois que la boucle devra être exécuté
     * 
     */
    function boucleAff($nomFonction, $titre, $contenu, $classeTitre, $classeContenu, $nbExec, $accesContenu = null)
    {
        
        //Paramètre de la fonction à ajouter
        $i = 0;
        $retour = "";
        
        while($i<$nbExec)
        {
            if($i%2 != 0)
            {
                if($accesContenu == null){
                    $contenu[$i] = str_replace("&shy"," - ", $contenu[$i]);
                    $retour .= call_user_func_array($nomFonction, array($titre, $contenu[$i], 'offset1 '.$classeTitre, $classeContenu));
                }
                else
                    $retour .= call_user_func_array($nomFonction, array($titre, $contenu[$i][$accesContenu], 'offset1 '.$classeTitre, $classeContenu));
                $retour .= sautLigne();

            }
            else
            {
                if($accesContenu == null){
                    $contenu[$i] = str_replace("&shy"," - ", $contenu[$i]);
                    $retour .= call_user_func_array($nomFonction, array($titre, $contenu[$i], $classeTitre, $classeContenu));
                }
                else
                    $retour .= call_user_func_array($nomFonction, array($titre, $contenu[$i][$accesContenu], $classeTitre, $classeContenu));
            }
            $i++;
        }
        return $retour;
    }
    
    /**
     * fait l'affichage d'un champ
     * 
     * @param string $titre
     * @param string $contenu
     * @param string $classeTitre
     * @param string $classeContenu
     * @return string
     */
//    function afficher($titre, $contenu, $classeTitre, $classeContenu) {
//        $retour = '<div class="' . $classeTitre . '">' . $titre . '</div>';
//        $retour .= '<div class="' . $classeContenu . '">' . $contenu . '</div>';
//        return $retour;
//    }
    
    function afficher($titre, $contenu, $classeTitre, $classeContenu, $sup=null) {
        $retour = '<div class="' . $classeTitre . '">' . $titre . '</div>';
        if($sup === 'false'){
            $retour .= '<div class="' . $classeContenu . ' over">' . $contenu . '</div>';
        }
        else
        {
            $retour .= '<div class="' . $classeContenu . ' ">' . $contenu . '</div>';
        }
        return $retour;
    }

    function afficher_textarea($titre, $contenu, $tailleTitre, $tailleChamp, $ligne=4, $colonne=40, $classe='') {
        $retour = '<textarea rows="' . $ligne . '" cols="' . $colonne . '" class="'. $classe .'" ';
        $retour .= ' readonly >';
        $retour .= $contenu;
        $retour .= '</textarea>';
        $a = ajouterDiv($titre, $retour, $tailleTitre, $tailleChamp, '');
        return $a;
    }
    
   
    function afficher_image($titre, $contenu, $classeTitre, $classeContenu, $image) {
        $retour = '<div class="' . $classeTitre . '">' . $titre . '</div><div id="divlogo" class="' . $classeContenu . '">';
        if($image == true)
        {
            if(!empty($contenu))
            {
              $retour.='<img name="IMAGELOGO" src="'.$contenu.'" width="200px" height="100px"/>';
            }
            else
            {
              $retour.='&nbsp; Ce client n\'a pas de logo';  
            }
            $retour .= '</div>';
        }
        else
        {
          $retour .= $contenu . '</div>';
        }
        return $retour;
    }

    /**
     * créé un champ input pour MNEMONIQUE
     * 
     * @param string    $titre          le titre des boutons
     * @param string    $nom            l'attribut name
     * @param int       $tailleTitre    la taille du titre
     * @param int       $tailleChamp    la taille du champ
     * @param string    $classe         la classe du div
     * 
     * @return string           le code HTML de champ
     */
    function inputMNEMO($titre, $nom, $tailleTitre, $tailleChamp, $classe='') {
//var_dump($classe); 
        $retour = '<input type="text" name="' . $nom . '" class="mnemo" onkeyup="this.value = this.value.toUpperCase();" onclick="test_mnemo()" ';
        $retour .= isset($_POST[$nom]) ? 'value ="' . $_POST[$nom] . '"' : '';
        return ajouterDiv($titre, $retour . '/>', $tailleTitre, $tailleChamp, $classe);
    }
    
    /**
     * créé un champ Bouton avec lien
     * 
     * @param string    $titre          le texte du bouton
     * @param string    $classe         classes supplémentaires optionnelles
     * @param string    $lien           l'url associée
     * 
     * @return string           le code HTML de champ
     */
    function button($titre, $nom, $valeur, $tailleTitre, $tailleChamp, $classe='', $clsinput='', $link='') {
        $retour = '<input type="button" name="' . $nom . '" ';
        if ($clsinput){
            $retour .= 'class="' . $clsinput . '" ';
        }
        $retour .= 'value ="' . $valeur . '" onclick="' . $link . '"';
        $retour = ajouterDiv($titre, $retour . '/>', $tailleTitre, $tailleChamp, $classe);

        return $retour;
    }
    
    
?>
