<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function ajout_contact_client() {
        $champs = array(
            'CLIENT'        => 1,
            'NOM'           => 1,
            'PRENOM'        => 1,
            'EMAIL'         => 1,
            'PRT'           => 1,
            'FONCTION'      => 1,
            'COMMENTAIRE'   => 0,
            'ARCHIVE'       => 0,
        );

        $vars = verif_champs($champs, 'CTC_');
        if (is_array($vars)) {
            $vars['CLI_NO'] = $vars['CTC_CLIENT'];
            $vars['FCT_NO'] = $vars['CTC_FONCTION'];
            unset ($vars['CTC_CLIENT'], $vars['CTC_FONCTION']);
            $query = creer_insert($vars, 'CONTACT_CLIENT');
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
