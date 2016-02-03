<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_fournisseur($recherche) {
        $query = "SELECT * FROM FOURNISSEUR WHERE FOU_NO=".$recherche;
        $results = $GLOBALS['connexion']->query ($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            $_POST[substr($key, 4)] = $result;
        }
    }
    function update_fournisseur($recherche) {
        $champs = array(
            'CODE'      => 1,
            'DTCREATION'=> 1,
            'NOM'       => 1,
            'ARCHIVE'   => 0,
        );

        $vars = verif_champs($champs, 'FOU_');

        if (is_array($vars)) {
            $query = creer_update($vars, 'FOURNISSEUR', "FOU_NO=" . $recherche);
            $GLOBALS['connexion']->query($query);
            $url = str_replace("modification", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $recherche . "&message=MAJOK";
            unset($_POST);
            $_POST['recherche'] = $recherche;
        }
        else {
            return $vars;
        }
        header('Location:' . $url);
    }
    
?>
