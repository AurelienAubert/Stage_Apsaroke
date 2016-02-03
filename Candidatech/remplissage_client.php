<?php
    include 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
	
    $champs = array(
        'CODE'          => 1,
        'DTCREATION'    => 1,
        'NOM'           => 1,
        'NOMFAC'        => 0,
        'ADRCOM_1'      => 1,
        'ADRCOM_2'      => 0,
        'CPCOM'         => 1,
        'VILLECOM'      => 1,
        'ADRFAC_1'      => 0,
        'ADRFAC_2'      => 0,
        'CPFAC'         => 0,
        'VILLEFAC'      => 0,
        'PAYS'          => 0,
        'CODE_FOUR'     => 0,
        //'ARCHIVE'       => 0
    );

    $vars = verif_champs($champs, 'CLI_');
	
    if (is_array($vars)) {

        $code = $vars['CLI_CODE'];
        $nom = $vars['CLI_NOM'];
        $adrcom1 = $vars['CLI_ADRCOM_1'];
        $adrcom2 = $vars['CLI_ADRCOM_2'];
        $cpcom = $vars['CLI_CPCOM'];
        $villecom = $vars['CLI_VILLECOM'];
        $date_creation = $vars['CLI_DTCREATION'];
        $nom_facturation = $vars['CLI_NOMFAC'];
        $adrfac1 = $vars['CLI_ADRFAC_1'];
        $adrfac2 = $vars['CLI_ADRFAC_2'];
        $cpfac = $vars['CLI_CPFAC'];
        $villefac = $vars['CLI_VILLEFAC'];
        $pays = $vars['CLI_PAYS'];
        $codefourn = $vars['CLI_CODE_FOUR'];
        $archive = $vars['CLI_ARCHIVE'];

        $logo = "";
        if(!empty($_FILES['LOGO']['name']))
        {
            $dossier = 'client/images_clients/';
            $fichier = basename($_FILES['LOGO']['name']);
            move_uploaded_file($_FILES['LOGO']['tmp_name'], $dossier . $fichier);
            $logo = $dossier . $fichier;
        }
        else 
        {
            $logo = '';
        }

        // Test infos facturation : si vide, on récupère les infos commerciales
        if($nom_facturation == ''){ $nom_facturation = $nom; }
        if($adrfac1 == ''){ $adrfac1 = $adrcom1; }
        if($adrfac2 == ''){ $adrfac2 = $adrcom2; }
        if($cpfac == ''){ $cpfac = $cpcom; }
        if($villefac == ''){ $villefac = $villecom; }
        
        $rq_client = "INSERT INTO CLIENT(CLI_CODE, CLI_NOM, CLI_ADRCOM_1, CLI_ADRCOM_2, CLI_CPCOM, CLI_VILLECOM, CLI_DTCREATION, CLI_LOGO, CLI_NOMFAC, CLI_ADRFAC_1, CLI_ADRFAC_2, CLI_CPFAC, CLI_VILLEFAC, CLI_PAYS, CLI_CODE_FOUR, CLI_ARCHIVE) "
                . " VALUES ('".$code."', '".$nom."', '".$adrcom1."', '".$adrcom2."', '".$cpcom."', '".$villecom."', '".$date_creation."', '".$logo."', '".$nom_facturation."', '".$adrfac1."', '".$adrfac2."', '".$cpfac."', '".$villefac."', '".$pays."', '".$codefourn."', '".$archive."');";
        $GLOBALS['connexion']->query($rq_client);

        $id = $GLOBALS['connexion']->insert_id;
        $url = "affichage.php?type=client&recherche=" . $id . "&message=MAJOK";
        unset($_POST);
        $_POST['recherche'] = $id;

    } else {
        return $vars;
    }
    header('Location:' . $url);
?>

