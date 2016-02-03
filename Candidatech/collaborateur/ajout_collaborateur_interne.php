<?php

    /**
     * effectue les vérifications sur les champs pour l'ajout d'un collaborateur interne
     */
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function ajout_collaborateur_interne() {
        $champs_collab = array(
            'MNEMONIC'      => 1,
            'NOM'           => 1,
            'PRENOM'        => 1,
            'NOMJEUNEFILLE' => 0,
            'CIVILITE'      => 1,
            'ETAT'          => 0,
            'TEL'           => 0,
            'PRT'           => 1,
            'EMAIL'         => 0,
        );

        $champs_interne = array(
            'DTNAISSANCE'   => 1,
            'NSS'           => 0,
            'LIEUNAISSANCE' => 1,
            'NATIONALITE'   => 0,
            'ADRESSE'       => 1,
            'ADRESSE2'      => 0,
            'CP'            => 1,
            'VILLE'         => 1,
            'FONCTION'      => 0,
            'STATUT'        => 0,
            'COEFF'         => 0,
            'POSITION'      => 0,
            'TYPECONTRAT'   => 0,
            'TYPEHORAIRE'   => 0,
            'DTENTREE'      => 1,
            'DTDEPART'      => 0,
            'REMUNFIXE'     => 0,
            'REMUNVAR'      => 0,
            'NOMBANQUE'     => 0,
            'PERIODEESSAI'  => 0,
            'PPE'           => 0,
            'TR'            => 0,
            'FACTURABLE'    => 0,
            'GSM'           => 0,
            'PEE'           => 0,
            'TREIZIEME'     => 0,
            'PRIME_ANCI'    => 0,
            'PART_VARI'     => 0,
            'FRAIS'         => 0,
            'IBAN'          => 0,
            'BIC'           => 0,
         );

        $vars_collab = verif_champs($champs_collab, 'COL_');
        $vars_interne = verif_champs($champs_interne, 'INT_');

        if (is_array($vars_collab) && is_array($vars_interne)) {
            $vars_interne['FCT_NO'] = $vars_interne['INT_FONCTION'];
            unset($vars_interne['INT_FONCTION']);
            $query = "SELECT COL_MNEMONIC FROM COLLABORATEUR WHERE COL_MNEMONIC = '" . $vars_collab['COL_MNEMONIC'] . "'";
            $result = $GLOBALS['connexion']->query($query);
            if ($result->num_rows != 0) {
                return 'Mnémonique déjà utilisé';
            }
            else {
                $vars_collab['COL_EMAILAPSA'] = $vars_collab['COL_MNEMONIC'] . '@apsaroke.fr';
                $query = creer_insert($vars_collab, 'COLLABORATEUR');
                $GLOBALS['connexion']->query($query);

                // Récupération de l'ID collaborateur
                $id = $GLOBALS['connexion']->insert_id;
                $vars_interne['COL_NO'] = $GLOBALS['connexion']->insert_id;
                
                $query = creer_insert($vars_interne, 'INTERNE');
                $GLOBALS['connexion']->query($query);
                
                $url = str_replace("ajout", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $id . "&message=MAJOK";
                unset($_POST);
                $_POST['recherche'] = $id;
            }
        }
        else {
            $erreur = is_string($vars_collab)?$vars_collab."\n":'';
            $erreur .= is_string($vars_interne)?$vars_interne:'';
            return $erreur;
        }
        header('Location:' . $url);
    }
?>


