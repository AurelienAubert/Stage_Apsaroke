<?php
// (c) Paul-André MULCEY
// Impression projet : Contrat de travail à durée indéterminée

include_once "inc/connection.php";
include_once "inc/creer_input.php";

function recuperer_OMI($recherche, $type) {
    //Récupération des données
    $q1 = "SELECT * FROM MISSION M WHERE M.MIS_NO=" . $_GET['recherche'];
    $r1 = $GLOBALS['connexion']->query($q1)->fetch_assoc();
    $query = "SELECT * FROM PROJET P LEFT JOIN COLLABORATEUR COL ON COL.COL_NO=P.COL_NO, CONTACT_CLIENT CTC, CLIENT C WHERE C.CLI_NO=P.CLI_NO AND CTC.CTC_NO=P.CTC_NO AND P.PRO_NO=" . $r1['PRO_NO'];
    $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

    $query_getNseq = "SELECT MAX(SUBSTRING(MIS_NSEQUENTIEL,11,3)) AS NSEQ FROM MISSION WHERE SUBSTRING(MIS_NSEQUENTIEL,7,4)='". date("Y")."'";
    $row_Nseq = $GLOBALS['connexion']->query($query_getNseq)->fetch_assoc();
    if ($row_Nseq['NSEQ'] == null)
    {
        $num = "001";
    }
    else
    {
        $num = (int)$row_Nseq['NSEQ'];
        $num++;
    }
    $nsequentiel = "OM" . $row['COL_MNEMONIC'] . '-' . date("Y") . sprintf("%03d", $num);
    $numdoc = $nsequentiel;
    $iddoc = null;

    $nomcol = $row['COL_NOM'] . ' ' . $row['COL_PRENOM'];
    $adrcli = $row['CLI_ADRESSE_1'] . ' ' . $row['CLI_ADRESSE_2'] . ' ' . $row['CLI_CP'] . ' ' . $row['CLI_VILLE'];
    $nomctc = $row['CTC_NOM'] . ' ' . $row['CTC_PRENOM'] . ' - ' . $row['CTC_PRT'] . ' / ' . $row['CTC_EMAIL'];
    $nomctf = $row['CTF_NOM'] . ' ' . $row['CTF_PRENOM'] . ' - ' . $row['CTF_PRT'] . ' / ' . $row['CTF_EMAIL'];
    if ($row['CTC_NO'] == 0) $nomctc = '';
    if ($row['CTF_NO'] == 0) $nomctf = '';
    $periode = 'du ' . format_date($r1['MIS_DTDEBUT']) . ' au ' . format_date($r1['MIS_DTFIN']);
    if ($r1['MIS_NBJOURS'] > 0){
        $nbjours = 'Nombre de jours prévus : ' . $r1['MIS_NBJOURS'];
    }

    $djour = date2fr(date("d F Y"));

    $query = "SELECT * FROM LIBDOCUMENT L LEFT JOIN COLLABORATEUR COL ON COL.COL_NO=L.COL_NO LEFT JOIN DOCUMENT D ON D.DOC_NO = L.DOC_NO WHERE D.DOC_CODE='" . $type . "' ORDER BY LDO_ORDRE";
    $lib = $GLOBALS['connexion']->query($query)->fetch_assoc();

    // Mise en POST
    $_POST['numdoc'] = $numdoc;
    $_POST['periode'] = $periode;
    $_POST['article'] = $lib['LDO_CONTENU'];
    $_POST['nomcol'] = $nomcol;
    $_POST['nomcli'] = $row['CLI_NOM'];
    $_POST['adrcli'] = $adrcli;
    $_POST['nompro'] = $r1['MIS_NOM'];
    $_POST['nomctc'] = $nomctc;
    $_POST['nomctf'] = $nomctf;
    $_POST['nbjours'] = $nbjours;
    $_POST['prodet'] = $row['PRO_DETAIL'];
    $_POST['promod'] = $row['PRO_MODALITE'];
    $_POST['djour'] = $djour;
}

/**
 * affiche le formulaire de préparation à l'impression d'un ordre de mission
 */
function afficherFormulaire() {

    $retour = creerFieldset('Ordre de mission :', array(
        input('Periode :', 'periode', 2, 4, false, 'text', '', 'input300'),
        input('No du document :', 'numdoc', 2, 2, true),
        sautLigne(),
        textarea('Rappel :', 'article', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Collaborateur :', 'nomcol', 2, 4, true, 'text', '', 'input500'),
        sautLigne(),
        input('Client :', 'nomcli', 2, 4, true, 'text', '', 'input500'),
        sautLigne(),
        input('Adresse client :', 'adrcli', 2, 4, false, 'text', '', 'input500'),
        sautLigne(),
        input('Nom du projet :', 'nompro', 2, 6, false, 'text', '', 'input500'),
        sautLigne(),
        input('Contact client :', 'nomctc', 2, 6, false, 'text', '', 'input500'),
        sautLigne(),
        input('Contact fournisseur :', 'nomctf', 2, 6, false, 'text', '', 'input500'),
        sautLigne(),
        input('Jours prévus :', 'nbjours', 2, 6, false, 'text', '', 'input500'),
        sautLigne(),
        textarea('Détail mission :', 'prodet', 2, 8, false, 6, 80, 'textarea800'),
        sautLigne(),
        textarea('Modalités :', 'promod', 2, 8, false, 6, 80, 'textarea800'),
        sautLigne(),
        input('Date du document :', 'djour', 2, 4, true),
    ));

    return $retour;
}
?>
