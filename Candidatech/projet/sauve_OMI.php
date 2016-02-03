<?php
include_once "inc/connection.php";

function sauver_OMI($idmis){
    $valjson = '';
    $valjson .= 'numdoc##' . $_POST['numdoc'] . '§§';
    $valjson .= 'periode##' . $_POST['periode'] . '§§';
    $valjson .= 'article##' . $_POST['article'] . '§§';
    $valjson .= 'nomcol##' . $_POST['nomcol'] . '§§';
    $valjson .= 'nomcli##' . $_POST['nomcli'] . '§§';
    $valjson .= 'adrcli##' . $_POST['adrcli'] . '§§';
    $valjson .= 'nompro##' . $_POST['nompro'] . '§§';
    $valjson .= 'nomctc##' . $_POST['nomctc'] . '§§';
    $valjson .= 'nomctf##' . $_POST['nomctf'] . '§§';
    $valjson .= 'nbjours##' . $_POST['nbjours'] . '§§';
    $valjson .= 'prodet##' . $_POST['prodet'] . '§§';
    $valjson .= 'promod##' . $_POST['promod'] . '§§';
    $valjson .= 'djour##' . $_POST['djour'] . '§§';
   
    $valjson = substr($valjson, 0, strlen($valjson) - 2);
    //echo $valjson . '<br>';

    // Mise à jour du dernier No de document
    $q1 = "UPDATE MISSION SET MIS_NSEQUENTIEL = '" . $_POST['numdoc'] . "' WHERE MIS_NO=" . $idmis;
    $r1 = $GLOBALS['connexion']->query($q1);
    
    // Sauvegarde des données corrigées avant impression
    $query = "INSERT INTO HISTDOC (HID_TYPE, HID_IDDOC, HID_NOMDOC, HID_CONTENU) VALUES ('OMI', '" . $idmis ."', '" . $_POST['numdoc'] . "', '" . str_replace("'", "`", $valjson)  . "')";
    $lib = $GLOBALS['connexion']->query($query);
    $id = $GLOBALS['connexion']->insert_id;

    return $id;
  
}

