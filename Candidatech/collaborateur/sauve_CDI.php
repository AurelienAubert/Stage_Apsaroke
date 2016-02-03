<?php
include_once "inc/connection.php";

function sauver_CDI($idcol){
    $valjson = '';
    $valjson .= 'numdoc##' . $_POST['numdoc'] . '§§';
    $valjson .= 'nomcol##' . $_POST['nomcol'] . '§§';
    $valjson .= 'natcol##' . $_POST['natcol'] . '§§';
    $valjson .= 'adrcol##' . $_POST['adrcol'] . '§§';
    $valjson .= 'nsscol##' . $_POST['nsscol'] . '§§';

    $t = $_POST['t'];
    $p = $_POST['p'];

    for ($i = 0; $i < 20; $i++){
        $txt = 't[' . $i . ']##' . $t[$i] . '§§p[' . $i . ']##' . $p[$i] . '§§';
        $valjson .= $txt;
    }
    $valjson .= 'p[20]##' . $p[20] . '§§';

    $valjson = substr($valjson, 0, strlen($valjson) - 2);
    //echo $valjson . '<br>';

    // Sauvegarde des données corrigées avant impression
    $query = "INSERT INTO HISTDOC (HID_TYPE, HID_IDDOC, HID_NOMDOC, HID_CONTENU) VALUES ('CDI', '" . $idcol ."', '" . $_POST['numdoc'] . "', '" . str_replace("'", "`", $valjson)  . "')";
    $lib = $GLOBALS['connexion']->query($query);
    $id = $GLOBALS['connexion']->insert_id;

    return $id;
  
}

