<?php

    /**
     * effectue les vérifications sur les champs pour l'ajout d'un collaborateur interne
     */
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_collaborateur_externe($recherche) {
        $query = "SELECT COL_MNEMONIC, COL_NOM, COL_PRENOM, COL_ETAT, COL_CIVILITE, COL_NOMJEUNEFILLE, COL_TEL, COL_PRT, COL_EMAIL, COL_EMAILAPSA, FOU_NO, COL_ARCHIVE FROM COLLABORATEUR C, EXTERNE E WHERE C.COL_NO = E.COL_NO AND E.COL_NO = " . $recherche;
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
        foreach($results as $key => $result) {
            switch($key) {
                case 'FOU_NO':
                    $_POST['FOURNISSEUR'] = $result;
                    break;
                default:
                    $_POST[substr($key, 4)] = $result;
                    break;
            }
        }
    }
    
    function update_collaborateur_externe ($recherche) {
        $champs = array(
            'FOURNISSEUR'   => 1,
            'NOM'           => 1,
            'PRENOM'        => 1,
            'ETAT'          => 0,
            'ARCHIVE'       => 0,
            'MNEMONIC'      => 1,
            'CIVILITE'      => 1,
            'NOMJEUNEFILLE' => 0,
            'TEL'           => 0,
            'PRT'           => 1,
            'EMAIL'         => 0,
         );

        $vars = verif_champs($champs, 'COL_');
        if (is_array($vars)) {
            //TODO : vérifier, il manque peut être un unset($vars['COL_NO']);
            $externe = array('FOU_NO' => $vars['COL_FOURNISSEUR']);
            unset($vars['COL_FOURNISSEUR']);
            $query = creer_update($vars, 'COLLABORATEUR', 'COL_NO=' . $recherche);
            $GLOBALS['connexion']->query($query);
            
            $query = creer_update($externe, 'EXTERNE', 'COL_NO=' . $recherche);
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