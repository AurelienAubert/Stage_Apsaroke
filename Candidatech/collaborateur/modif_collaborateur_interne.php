<?php //
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';

    function recuperer_collaborateur_interne($recherche) {
        $query = "SELECT * FROM COLLABORATEUR C, INTERNE I WHERE C.COL_NO=I.COL_NO AND I.COL_NO=" . $recherche;
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            switch ($key) {
                case 'FCT_NO':
                    $_POST['FONCTION'] = $result;
                    break;
                case 'TAU_NO':
                    $_POST['TAUNO'] = $result;
                    break;
                default:
                    $_POST[substr($key, 4)] = $result;
                    break;
            }
        }
        $_POST['DTDEPART'] = substr($_POST['DTDEPART'], 0, 10);
    }
    
    function update_collaborateur_interne($recherche) {
        $champs_collab = array(
            'MNEMONIC'      => 1,
            'NOM'           => 1,
            'PRENOM'        => 1,
            'NOMJEUNEFILLE' => 0,
            'CIVILITE'      => 1,
            'ETAT'          => 0,
            'ARCHIVE'       => 0,
            'TEL'           => 0,
            'PRT'           => 1,
            'EMAIL'         => 0,
            'EMAILAPSA'     => 1,
            'TAUNO'         => 0,
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
        $vars_specif = verif_champs($champs_interne, 'INT_');
        if (is_array($vars_collab) && is_array($vars_specif)) {
            $vars_collab['TAU_NO'] = $vars_collab['COL_TAUNO'];
            $vars_specif['FCT_NO'] = $vars_specif['INT_FONCTION'];
            unset($vars_collab['COL_TAUNO'], $vars_specif['INT_FONCTION']);
            $query = "SELECT COL_MNEMONIC FROM COLLABORATEUR WHERE COL_MNEMONIC = '" . $vars_collab['COL_MNEMONIC'] . "' AND COL_NO !=" . $recherche;
            $result = $GLOBALS['connexion']->query($query);
            if ($result->num_rows != 0) {
                return 'Mnémonique déjà utilisé pour un autre collaborateur';
            } else {
                $query = creer_update($vars_collab, 'COLLABORATEUR', "COL_NO = " . $recherche);
                $GLOBALS['connexion']->query($query);

                $query = creer_update($vars_specif, 'INTERNE', "COL_NO = " . $recherche);
                $GLOBALS['connexion']->query($query);
                
                $url = str_replace("modification", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $recherche . "&message=MAJOK";
                unset($_POST);
                $_POST['recherche'] = $recherche;
            }
        }
        else {
            $erreur = is_string($vars_collab)?$vars_collab."\n":'';
            $erreur .= is_string($vars_specif)?$vars_specif:'';
            return $erreur;
        }
        header('Location:' . $url);
    }
    
?>
