<?php

    /**
     * effectue les vérifications sur les champs pour l'ajout d'un collaborateur interne
     */
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function ajout_collaborateur_externe() {
        $champs = array(
            'FOURNISSEUR'   => 1,
            'NOM'           => 1,
            'PRENOM'        => 1,
            'ETAT'          => 0,
            'MNEMONIC'      => 1,
            'CIVILITE'      => 1,
            'NOMJEUNEFILLE' => 0,
            'TEL'           => 0,
            'PRT'           => 1,
            'EMAIL'         => 1,
         );

        $vars = verif_champs($champs, 'COL_');
        if (is_array($vars)) {
            $externe = array('FOU_NO' => $vars['COL_FOURNISSEUR']);
            unset($vars['COL_FOURNISSEUR']);
            $query = creer_insert($vars, 'COLLABORATEUR');
            $GLOBALS['connexion']->query($query);
            
            $externe['COL_NO'] = $GLOBALS['connexion']->insert_id;
            $query = creer_insert($externe, 'EXTERNE');
            $GLOBALS['connexion']->query($query);
                
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