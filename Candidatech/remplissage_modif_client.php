<?php
    include "inc/connection.php"; 
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
        'ARCHIVE'       => 0
    );

    $vars = verif_champs($champs, 'CLI_');

    if (is_array($vars)) {

        $client_choisi = $_POST['recherche'];
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

        $logo_client = $_POST['logo_client'];

        $logo = "";
        if(!empty($_FILES['LOGO']['name']))
        {
            $dossier = 'image/';
            $fichier = basename($_FILES['LOGO']['name']);
            move_uploaded_file($_FILES['LOGO']['tmp_name'], $dossier . $fichier);
            $logo = $dossier . $fichier;
        }
        else 
        {
            if ($logo_client != 'Ce client n\'a pas de logo'){
                $logo = $logo_client;
            }else{
                $logo = '';
            }
        }

        // Test infos facturation : si vide, on récupère les infos commerciales
        if($nom_facturation == ''){ $nom_facturation = $nom; }
        if($adrfac1 == ''){ $adrfac1 = $adrcom1; }
        if($adrfac2 == ''){ $adrfac2 = $adrcom2; }
        if($cpfac == ''){ $cpfac = $cpcom; }
        if($villefac == ''){ $villefac = $villecom; }
        
        $rq_client = "UPDATE CLIENT SET CLI_CODE='" . $code . "', CLI_NOM='" . $nom . "', CLI_ADRCOM_1='".$adrcom1."', CLI_ADRCOM_2='".$adrcom2."', CLI_CPCOM='".$cpcom."', CLI_VILLECOM='".$villecom."', CLI_DTCREATION='" . $date_creation . "', CLI_LOGO='".$logo."', CLI_NOMFAC='".$nom_facturation."', CLI_ADRFAC_1='".$adrfac1."', CLI_ADRFAC_2='".$adrfac2."', CLI_CPFAC='".$cpfac."', CLI_VILLEFAC='".$villefac."', CLI_PAYS='".$pays."', CLI_CODE_FOUR='".$codefourn."', CLI_ARCHIVE='".$archive."' WHERE CLI_NO = '" . $client_choisi . "'";
        $connexion->query($rq_client);

        $url = "affichage.php?type=client&recherche=" . $client_choisi . "&message=MAJOK";
        unset($_POST);
        $_POST['recherche'] = $client_choisi;
    } else {
        return $vars;
    }
    header('Location:' . $url);
?>
