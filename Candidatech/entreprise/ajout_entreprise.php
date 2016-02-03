<?php

    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';

    function ajout_entreprise() {
        if(!empty($_FILES['LOGO']['name']))
        {
            $dossier = 'image/';
            $fichier = basename($_FILES['LOGO']['name']);
            move_uploaded_file($_FILES['LOGO']['tmp_name'], $dossier . $fichier);
            $_POST['LOGO'] = $dossier . $fichier;
        }
        if(!empty($_FILES['LOGOPIED']['name']))
        {
            $dossier = 'image/';
            $fichier = basename($_FILES['LOGOPIED']['name']);
            move_uploaded_file($_FILES['LOGOPIED']['tmp_name'], $dossier . $fichier);
            $_POST['LOGOPIED'] = $dossier . $fichier;
        }
        $champs = array(
            'NOM'           => 1,
            'STATUT'        => 1,
            'ADRESSE'       => 1,
            'ADRESSE_2'     => 0,
            'CP'            => 1,
            'TEL'           => 1,
            'VILLE'         => 1,
            'CAPITAL'       => 1,
            'TVA_INTRA'     => 1,
            'RCS'           => 1,
            'SIRET'         => 1,
            'APE'           => 1,
            'SITE_WEB'      => 0,
            'LOGO'          => 1,
            'LOGOPIED'      => 0,
        );

        $vars = verif_champs($champs, 'ENT_');
        if (is_array($vars)) {
            $query = creer_insert($vars, 'ENTREPRISE');
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
