<?php

    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    function ajout_banque() {
        $champs = array(
            'NOM'           => 1,
            'CDE_BAN'       => 1,
            'CDE_GUI'       => 1,
            'NUM_CPT'       => 1,
            'RIB'           => 1,
        );

        $vars = verif_champs($champs, 'BAN_');
        if (is_array($vars)) {
            $query = creer_insert($vars, 'BANQUE');
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
