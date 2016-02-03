<?php

include "inc/connection.php";
include_once 'inc/verif_champs_formulaire.php';
require_once 'calendrier/fonction_nbjoursMois.php';
include 'calendrier/fonction_nomMois.php';

//var_dump($_POST);
$champs = array(
    'ENT_NO' => 1,
    'BAN_NO' => 1,
    'FAC_NUMCMDE' => 1,
    'FAC_DEV' => 1,
    'FAC_NOMCOM' => 1,
    'FAC_DATE' => 1,
    'FAC_PERIODE' => 1,
    'FAC_NOMCLI' => 1,
    'FAC_CODCLI' => 1,
    'FAC_ADR1' => 1,
    'FAC_ADR2' => 0,
    'FAC_CP' => 1,
    'FAC_VILLE' => 1,
    'FAC_NOMCTC' => 1,
    'FAC_CODFOU' => 0,
    'FAC_NOMPRO' => 1,
    'FAC_PRODETAIL' => 1,
    'FAC_MODE_REG' => 1,
);

$vars = verif_champs($champs, '');

if (is_array($vars)) {
    
}

// Création d'un proforma
$COL_NO = $_POST['COL_NO'];
$CLI_NO = $_POST['CLI_NO'];
$FAC_CODFOU = $_POST['FAC_CODFOU'];
$PRO_NO = $_POST['PRO_NO'];
$CTC_NO = $_POST['CTC_NO'];
$FAC_SUIVIPAR = $_POST['FAC_SUIVIPAR'];
$FAC_ANNEE = $_POST['FAC_ANNEE'];
$FAC_MOIS = $_POST['FAC_MOIS'];
$FAC_TAUXTVA = $_POST['TAUX_TVA'];

$ENT_NO = $_POST['ENT_NO'];
$BAN_NO = $_POST['BAN_NO'];
$FAC_NUMCMDE = $_POST['FAC_NUMCMDE'];
$FAC_DEV = $_POST['FAC_DEV'];
$FAC_NOMCOM = $_POST['FAC_NOMCOM'];
$FAC_DATE = $_POST['FAC_DATE'];
$FAC_PERIODE = $_POST['FAC_PERIODE'];
$FAC_NOMCLI = $_POST['FAC_NOMCLI'];
$FAC_CODCLI = $_POST['FAC_CODCLI'];
$FAC_ADR1 = $_POST['FAC_ADR1'];
$FAC_ADR2 = $_POST['FAC_ADR2'];
$FAC_CP = $_POST['FAC_CP'];
$FAC_VILLE = $_POST['FAC_VILLE'];
$FAC_NOMCTC = $_POST['FAC_NOMCTC'];
$FAC_NOMPRO = $_POST['FAC_NOMPRO'];
$FAC_PRODETAIL = $_POST['FAC_PRODETAIL'];
$FAC_TOTAL_HT = $_POST['FAC_TOTAL_HT'];
$FAC_MT_AVO = 0;
if(isset($_POST['OLD_FAC_TOTAL_HT']))
{
    $FAC_MT_AVO = $_POST['OLD_FAC_TOTAL_HT'];
    $FAC_MT_AVO -= $FAC_TOTAL_HT; //Montant de l'avoir si ce dernier existe
}
$FAC_ELEM = $_POST['FAC_ELEM'];
$FAC_MODE_REG = $_POST['FAC_MODE_REG'];

$i = 2;
$designation = array();
$elem_sup = "";
$elem_sup2 = "";
$elem_sup3 = "";

while (isset($_POST['FAC_CELL' . $i . '1']) && !empty($_POST['FAC_CELL' . $i . '1'])) {
    $elem_sup .= 'FAC_ELEM_SUP_' . ($i - 1) . '="' . $_POST['FAC_CELL' . $i . '0'] . '&shy' . $_POST['FAC_CELL' . $i . '1'] . '&shy' . floatval($_POST['FAC_CELL' . $i . '2']) . '",';
    $elem_sup2 .= 'FAC_ELEM_SUP_' . ($i - 1) . ',';
    $elem_sup3 .= '"' . $_POST['FAC_CELL' . $i . '0'] . '&shy' . $_POST['FAC_CELL' . $i . '1'] . '&shy' . floatval($_POST['FAC_CELL' . $i . '2']) . '",';
    $elem = array($_POST['FAC_CELL' . $i . '0'], $_POST['FAC_CELL' . $i . '1'], $_POST['FAC_CELL' . $i . '2']);
    //$FAC_TOTAL_HT += floatval($elem[2]);
    $designation[] = array($elem[0], $elem[1], floatval($elem[2]));
    $i++;
}
if($elem_sup3 != "")
    $elem_sup3 = substr($elem_sup3, 0, -1);
if($elem_sup2 != "")
    $elem_sup2 = ','.substr($elem_sup2, 0, -1);
$elem_sup = substr($elem_sup, 0, -1);

$FAC_MONTANT_TVA = floatval($FAC_TOTAL_HT) * floatval($FAC_TAUXTVA) / 100;
$FAC_TOTAL_TTC = floatval($FAC_TOTAL_HT) + floatval($FAC_MONTANT_TVA);

if ($_POST['type'] != "creation") {
    $FAC_NO = $_POST['FAC_NO'];

    $rq_insert_client_ajout = '';
    if($_GET['action'] == 'avoir'){
        $rq_insert_client_ajout .= 'FAC_MT_AVOIR="'.$FAC_MT_AVO.'", FAC_COMPTEUR="0",';
    }
    $rq_insert_client = 'UPDATE FACTURE SET FAC_DEV="' . $FAC_DEV . '",FAC_TOTAL_HT="'
            . $FAC_TOTAL_HT . '",FAC_TOTAL_TTC="' . $FAC_TOTAL_TTC . '",FAC_MONTANT_TVA="' . $FAC_MONTANT_TVA . '",'
            . 'FAC_DATE="' . $FAC_DATE . '",FAC_PERIODE="' . $FAC_PERIODE . '",'
            . 'FAC_ANNEE="' . $FAC_ANNEE . '",FAC_MOIS="' . $FAC_MOIS . '",FAC_MODE_REG="' . $FAC_MODE_REG . '",'
            . 'FAC_ELEM="'.$FAC_ELEM.'", FAC_NOMCOM="' . $FAC_NOMCOM . '",FAC_NOMCLI="' . $FAC_NOMCLI . '",FAC_CODCLI="' . $FAC_CODCLI . '",FAC_ADR1="' . $FAC_ADR1 . '",'
            . 'FAC_ADR2="' . $FAC_ADR2 . '",FAC_CP="' . $FAC_CP . '",FAC_VILLE="' . $FAC_VILLE . '",'
            . 'FAC_NOMCTC="' . $FAC_NOMCTC . '",FAC_NOMPRO="' . $FAC_NOMPRO . '",FAC_PRODETAIL="' . $FAC_PRODETAIL . '",'
            . 'FAC_NUMCMDE="' . $FAC_NUMCMDE . '",FAC_TAUXTVA="' . $FAC_TAUXTVA . '",';
    if($rq_insert_client_ajout != '')
        $rq_insert_client .= $rq_insert_client_ajout;

    if (!empty($elem_sup)) {
        $rq_insert_client .= $elem_sup . ',';
    }

    $rq_insert_client .= 'COL_NO="' . $COL_NO . '",'
            . 'CLI_NO="' . $CLI_NO . '",ENT_NO="' . $ENT_NO . '",BAN_NO="' . $BAN_NO . '",'
            . 'FAC_CODFOU="' . $FAC_CODFOU . '",PRO_NO="' . $PRO_NO . '",FAC_SUIVIPAR="' . $FAC_SUIVIPAR . '",'
            . 'CTC_NO="' . $CTC_NO . '" WHERE FAC_NO ="' . $FAC_NO . '"';

} else {
    $rq_insert_client = 'INSERT INTO FACTURE (FAC_DEV, FAC_TOTAL_HT, '
            . 'FAC_TOTAL_TTC, FAC_MONTANT_TVA, FAC_DATE, FAC_PERIODE, '
            . 'FAC_ANNEE, FAC_MOIS, FAC_MODE_REG, FAC_ELEM, '
            . 'CLI_NO, FAC_CODFOU, PRO_NO, CTC_NO, '
            . 'COL_NO, ENT_NO, BAN_NO, FAC_SUIVIPAR, '
            . 'FAC_NOMCOM, FAC_NOMCLI, FAC_CODCLI, FAC_ADR1, '
            . 'FAC_ADR2, FAC_CP, FAC_VILLE, FAC_NOMCTC, '
            . 'FAC_NOMPRO, FAC_PRODETAIL, FAC_NUMCMDE, FAC_TAUXTVA';


    $rq_insert_client .= $elem_sup2 . ') VALUES ("' . $FAC_DEV . '","' . $FAC_TOTAL_HT . '","' . $FAC_TOTAL_TTC . '",'
            . '"' . $FAC_MONTANT_TVA . '","' . $FAC_DATE . '","' . $FAC_PERIODE . '",'
            . '"' . $FAC_ANNEE . '","' . $FAC_MOIS . '","' . $FAC_MODE_REG . '", "'.$FAC_ELEM.'",'
            . '"' . $CLI_NO . '","' . $FAC_CODFOU . '","' . $PRO_NO . '","' . $CTC_NO . '",'
            . '"' . $COL_NO . '","' . $ENT_NO . '","' . $BAN_NO . '","' . $FAC_SUIVIPAR . '",'
            . '"' . $FAC_NOMCOM . '","' . $FAC_NOMCLI . '","' . $FAC_CODCLI . '","' . $FAC_ADR1 . '",'
            . '"' . $FAC_ADR2 . '","' . $FAC_CP . '","' . $FAC_VILLE . '","' . $FAC_NOMCTC . '",'
            . '"' . $FAC_NOMPRO . '","' . $FAC_PRODETAIL . '","' . $FAC_NUMCMDE . '","' . $FAC_TAUXTVA . '"';

    if (isset($elem_sup) && $elem_sup != "") {
        $rq_insert_client .= ','.$elem_sup3;
    }
    $rq_insert_client .= ')';
}

//echo $rq_insert_client;
$GLOBALS['connexion']->query($rq_insert_client);
$id = $GLOBALS['connexion']->insert_id;
$GLOBALS['connexion']->query('UPDATE FACTURE SET FAC_DEV="'.$FAC_DEV.$id.'" WHERE FAC_NO="'.$id.'"');

if(isset($_POST['OLD_FAC_TOTAL_HT']))
    header('Location:visu_proforma.php?mois='.$FAC_MOIS.'&annee='.$FAC_ANNEE.'&valideFacture=avoirFacture&recherche='.$FAC_NO);
else
    header('Location:visu_proforma.php?mois='.$FAC_MOIS.'&annee='.$FAC_ANNEE);
?>
