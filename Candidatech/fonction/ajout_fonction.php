<?php

    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function ajout_fonction() {
        $champs = array(
            'NOM'       => 1,
        );

        $vars = verif_champs($champs, 'FCT_');

        if (is_array($vars)) {
            $query = creer_insert($vars, 'FONCTION');
            $GLOBALS['connexion']->query($query);
            $id = $GLOBALS['connexion']->insert_id;
            $url = str_replace("ajout", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $id . "&message=MAJOK";
            unset($_POST);
            $_POST['recherche'] = $id;
        }
        else {
            return $vars;
        }
        header('Location:' . $url);
    }
?>
