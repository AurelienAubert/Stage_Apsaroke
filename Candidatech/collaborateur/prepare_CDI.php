<?php
// (c) Paul-André MULCEY
// Impression projet : Contrat de travail à durée indéterminée

include_once "inc/connection.php";
include_once "inc/creer_input.php";

function recuperer_CDI($recherche, $type) {
    //Récupération des données
    $query = "SELECT * FROM COLLABORATEUR P LEFT JOIN INTERNE I ON I.COL_NO=P.COL_NO LEFT JOIN FONCTION F ON F.FCT_NO=I.FCT_NO WHERE P.COL_NO=" . $_GET['recherche'];
    $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

    // Mise en place No de document : test si déja un CDI pour ce collaborateur dans l'historique des documents
    $q1 = "SELECT SUBSTRING(HID_NOMDOC, 6, 3)) as COL, HID_NOMDOC, HID_NO FROM HISTDOC WHERE HID_TYPE ='CDI'";
    $r1 = $GLOBALS['connexion']->query($q1);
    if ($r1 == null){
    //if ($r1['COL'] == null){
        $numdoc = date('Y') . '-' . $row['COL_MNEMONIC'] . "CDI";
        $req = "SELECT MAX(SUBSTRING(HID_NOMDOC, 12, 1)) as NUM FROM HISTDOC WHERE HID_TYPE ='CDI'";
        $res = $GLOBALS['connexion']->query($req)->fetch_assoc();
        if ($res['NUM'] == null)
        {
            $num = "1";
        }
        else
        {
            $num = (int)$res['NUM'];
            $num++;
        }
        $numdoc .= $num;
        $iddoc = null;
    }else{
        $res1 = $r1->fetch_assoc();
        $numdoc = $res1['HID_NOMDOC'];
        $iddoc = $res1['HID_NO'];
    }

    $nomcol = $row['COL_CIVILITE'] . ' ' . $row['COL_PRENOM'] . ' ' . $row['COL_NOM'];
    $natcol = ', de nationalité ' . $row['INT_NATIONALITE'];
    $adrcol = 'Demeurant à ' . $row['INT_ADRESSE'];
    if ($row['INT_ADRESSE2'] != ''){
        $adrcol .= ' ' . $row['INT_ADRESSE2'];
    }
    $adrcol .= ' ' . $row['INT_CP'] . ' '  . $row['INT_VILLE'];
    $nsscol = $row['INT_NSS'];

    $datejour = date('d-m-Y');

    //$query = "SELECT * FROM LIBDOCUMENT L LEFT JOIN COLLABORATEUR COL ON COL.COL_NO=L.COL_NO WHERE L.DOC_NO='2' ORDER BY LDO_ORDRE";
    $query = "SELECT * FROM LIBDOCUMENT L LEFT JOIN DOCUMENT D ON D.DOC_NO = L.DOC_NO WHERE D.DOC_CODE='CDI' ORDER BY LDO_ORDRE";
    $lib = $GLOBALS['connexion']->query($query);
    while ($par = $lib->fetch_assoc()) {
        $i = $par['LDO_ORDRE'] - 1;
        $t[$i] = $par['LDO_NOM'];
        $p[$i] = $par['LDO_CONTENU'];
    }

    // Remplacements de champs dans les libellés
    $dentree = date2fr(date("l d F Y", mktime(0, 0, 0, substr($row['INT_DTENTREE'], 8, 2), substr($row['INT_DTENTREE'], 5, 2), substr($row['INT_DTENTREE'], 0, 4))));

    // Periode d'essai : valeurs par défaut :
    //  Si cadre : 4 mois + renouv 3 mois
    //  employés : 2 mois + renouv 1 mois
    if (strtolower($row['INT_STATUT']) == "cadre"){
        $periode = "4 mois";
        $renouv = "3 mois";
    }else{
        $periode = "2 mois";
        $renouv = "1 mois";
    }

    // Remplacements dans les articles concernés
    $p[0] = str_replace('INT_DTENTREE', $dentree, $p[0]);
    $p[0] = str_replace('FCT_NOM', $row['FCT_NOM'], $p[0]);
    $p[0] = str_replace('INT_STATUT', $row['INT_STATUT'], $p[0]);
    $p[0] = str_replace('INT_COEFF', $row['INT_COEFF'], $p[0]);
    $p[0] = str_replace('INT_POSITION', $row['INT_POSITION'], $p[0]);
    $p[1] = str_replace('INT_ESSAIPER', $periode, $p[1]);
    $p[1] = str_replace('INT_ESSAIREN', $renouv, $p[1]);
    $p[3] = str_replace('INT_REMUNFIXE', $row['INT_REMUNFIXE'] . " " . EURO, $p[3]);
    $p[15] = str_replace('COL_NOMCOMPLET', $nomcol, $p[15]);
    $p[20] = str_replace('COL_NOMCOMPLET', $nomcol, $p[20]);

    // Mise en POST
    $_POST['numdoc'] = $numdoc;
    $_POST['nomcol'] = $nomcol;
    $_POST['natcol'] = $natcol;
    $_POST['adrcol'] = $adrcol;
    $_POST['nsscol'] = $nsscol;
    for ($i = 0; $i < count($t); $i++){
        $_POST['t[' . $i . ']'] = $t[$i];
        $_POST['p[' . $i . ']'] = $p[$i];
    }
}

/**
 * affiche le formulaire de préparation à l'impression d'un CDI
 */
function afficherFormulaire() {

    $retour = creerFieldset('1ère page :', array(
        input('Nom complet :', 'nomcol', 2, 4, true, 'text', '', 'input500'),
        input('', 'numdoc', 2, 2, true, 'hidden'),
        sautLigne(),
        input('Nationalité :', 'natcol', 2, 4, true, 'text', '', 'input500'),
        sautLigne(),
        input('Adresse :', 'adrcol', 2, 4, true, 'text', '', 'input500'),
        sautLigne(),
        input('No sécurité sociale :', 'nsscol', 2, 6, true, 'text', '', 'input500'),
    ));

    $retour .= creerFieldset('2ème page :', array(
        input('Titre 1 :', 't[0]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 1 :', 'p[0]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 2 :', 't[1]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 2 :', 'p[1]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 3 :', 't[2]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 3 :', 'p[2]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 4 :', 't[3]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 4 :', 'p[3]', 2, 8, true, 6, 80, 'textarea800'),
    ));

    $retour .= creerFieldset('3ème page :', array(
        input('Titre 5 :', 't[4]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 5 :', 'p[4]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 6 :', 't[5]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 6 :', 'p[5]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 7 :', 't[6]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 7 :', 'p[6]', 2, 8, true, 6, 80, 'textarea800'),
    ));

    $retour .= creerFieldset('4ème page :', array(
        input('Titre 8 :', 't[7]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 8 :', 'p[7]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 9 :', 't[8]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 9 :', 'p[8]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 10 :', 't[9]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 10 :', 'p[9]', 2, 8, true, 6, 80, 'textarea800'),
    ));

    $retour .= creerFieldset('5ème page :', array(
        input('Titre 11 :', 't[10]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 11 :', 'p[10]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 12 :', 't[11]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 12 :', 'p[11]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 13 :', 't[12]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 13 :', 'p[12]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 14 :', 't[13]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 14 :', 'p[13]', 2, 8, true, 6, 80, 'textarea800'),
    ));

    $retour .= creerFieldset('6ème page :', array(
        input('Titre 15 :', 't[14]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 15 :', 'p[14]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 16 :', 't[15]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 16 :', 'p[15]', 2, 8, true, 6, 80, 'textarea800'),
    ));

    $retour .= creerFieldset('7ème page :', array(
        input('Titre 17 :', 't[16]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 17 :', 'p[16]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 18 :', 't[17]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 18 :', 'p[17]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 19 :', 't[18]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 19 :', 'p[18]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        input('Titre 20 :', 't[19]', 2, 3, true, 'text', '', 'input500'),
        sautLigne(),
        textarea('Article 20 :', 'p[19]', 2, 8, true, 6, 80, 'textarea800'),
        sautLigne(),
        textarea('Lib signature :', 'p[20]', 2, 8, true, 6, 80, 'textarea800'),
    ));

    return $retour;
}
?>
