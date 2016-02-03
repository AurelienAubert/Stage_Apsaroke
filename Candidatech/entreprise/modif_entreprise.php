<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_entreprise($recherche) {
        $query = "SELECT * FROM ENTREPRISE WHERE ENT_NO=" . $recherche;
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            $_POST[substr($key, 4)] = $result;
        }
    }
    
    function update_entreprise($recherche) {
        if(!empty($_FILES['LOGO']['name']))
        {
            $dossier = 'image/';
            $fichier = basename($_FILES['LOGO']['name']);
            move_uploaded_file($_FILES['LOGO']['tmp_name'], $dossier . $fichier);
            $_POST['LOGO'] = $dossier . $fichier;
        }else{
            if ($_POST['IMGENT'] != ''){
                $_POST['LOGO'] = $_POST['IMGENT'];
            }
        }
        if(!empty($_FILES['LOGOPIED']['name']))
        {
            $dossier = 'image/';
            $fichier = basename($_FILES['LOGOPIED']['name']);
            move_uploaded_file($_FILES['LOGOPIED']['tmp_name'], $dossier . $fichier);
            $_POST['LOGOPIED'] = $dossier . $fichier;
        }else{
            if ($_POST['IMGPIE'] != ''){
                $_POST['LOGOPIED'] = $_POST['IMGPIE'];
            }
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
            $query = creer_update($vars, 'ENTREPRISE', "ENT_NO=" . $recherche);
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