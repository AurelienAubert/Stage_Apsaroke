<?php

include_once "inc/connection.php";
include_once "inc/creer_input.php";
include_once "inc/liste.php";

function afficher_projet($recherche, $idmission = 0) {
    $query = "SELECT P.*, CLI_NOM, CTC_NOM, COL.COL_PRENOM AS COL_PRENOM, COL.COL_NOM AS COL_NOM, COM.COL_PRENOM AS COM_PRENOM, COM.COL_NOM AS COM_NOM FROM PROJET P LEFT JOIN COLLABORATEUR COL ON COL.COL_NO=P.COL_NO LEFT JOIN COLLABORATEUR COM ON COM.COL_NO=P.PRO_SUIVIPAR, CONTACT_CLIENT CTC, CLIENT C WHERE C.CLI_NO=P.CLI_NO AND CTC.CTC_NO=P.CTC_NO AND P.PRO_NO=" . $recherche;
    $row = $GLOBALS['connexion']->query($query)->fetch_assoc();
    // Mission : par défaut, c'est la dernière mission
    if (!isset($idmission) || $idmission == 0 || $idmission == null) {
        $query = "SELECT MIS_NO FROM MISSION WHERE PRO_NO=" . $row['PRO_NO'] . " AND MIS_ORDRE IN (SELECT MAX(MIS_ORDRE) FROM MISSION WHERE PRO_NO=" . $row['PRO_NO'] . ")";
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
        $idmission = $results['MIS_NO'];
    }
    $q2 = "SELECT * FROM MISSION WHERE MIS_NO=" . $idmission;
    $row2 = $GLOBALS['connexion']->query($q2)->fetch_assoc();

    $cloture = "";
    if ($row2['MIS_ARCHIVE'] > 0)
        $cloture = afficher('<b>Mission ARCHIVEE</b>', '', 'span2', 'span3');

    if (is_null($row['CTF_NO'])) {
        $row['CTF_NOM'] = "Aucun";
    } else {
        $qctc = "SELECT CTF_NOM FROM CONTACT_FOURNISSEUR WHERE CTF_NO =" . $row['CTF_NO'];
        $row['CTF_NOM'] = $GLOBALS['connexion']->query($qctc)->fetch_assoc();
    }
    if ($row2['MIS_NSEQUENTIEL'] != '') {
        // recherche de l'id de l'historique pour la ré-impression
        $q1 = "SELECT * FROM HISTDOC WHERE HID_TYPE='OMI' AND HID_IDDOC=" . $idmission;
        $r1 = $GLOBALS['connexion']->query($q1)->fetch_assoc();
        $iddoc = $r1['HID_NO'];
        $NSEQ = button('', 'MIS_NSEQUENTIEL', $row2['MIS_NSEQUENTIEL'], 1, 3, $classe = '', $clsinput = 'btn btn-primary', $link = 'javascript:reimpOMI(' . $row['PRO_NO'] . ', ' . $iddoc . ');');
    } else {
        $NSEQ = button('', 'MIS_NSEQUENTIEL', 'Ordre de Mission', 1, 3, $classe = '', $clsinput = 'btn btn-primary', $link = 'javascript:impOMI(' . $row2['MIS_NO'] . ');');
        $PRO_NO = '<input type="hidden" name="PRO_NO" value="'.$row2['PRO_NO'].'">';
    }

    $det = str_replace('\n', '', $row['PRO_DETAIL']);
    $det = str_replace('\r', '<br />', $det);

    $result = creerFieldset('Projet', array(
        afficher('Client :', $row['CLI_NOM'], 'span3', 'span3'),
        afficher('Nom du projet :', $row['PRO_NOM'], 'span3', 'span3'),
        sautLigne(),
        afficher('Contact client :', $row['CTC_NOM'], 'span3', 'span3'),
        afficher('Partenaire éventuel :', $row['CTF_NOM'], 'span3', 'span3'),
        sautLigne(),
        afficher('Début du projet :', format_date($row['PRO_DTDEBUT']), 'span3', 'span3'),
        afficher('Numéro de cde :', $row['PRO_NUMCMDE'], 'span3', 'span3'),
        sautLigne(),
        afficher('Collaborateur :', $row['COL_PRENOM'] . ' ' . $row['COL_NOM'], 'span3', 'span3'),
        afficher('Projet suivi par :', $row['COM_PRENOM'] . ' ' . $row['COM_NOM'], 'span3', 'span3'),
        sautLigne(),
        afficher('Durée prévisionnelle (jours) :', $row['PRO_NBJOURS'], 'span3', 'span3'),
        afficher('Date de fin prévue :', format_date($row['PRO_DTFINPREVUE']), 'span3', 'span3'),
        sautLigne(),
        afficher_textarea('Détails :', $row['PRO_DETAIL'], 3, 8, 1, 80, 'textarea800'),
        sautLigne(),
        afficher_textarea('Modalités :', $row['PRO_MODALITE'], 3, 8, 4, 80, 'textarea800'),
        sautLigne(),
        afficher('Etat du projet :', $row['PRO_ARCHIVE'] == 1 ? 'Archivé' : 'En cours', 'span3', 'span3'),
        afficher('Date de cloture :', format_date($row['PRO_DTCLOTURE']), 'span3', 'span3'),
        sautLigne(),
        '<div style="border-top: 1px solid #e5e5e5; width: 98%; margin-left: 30px;"><div>',
    ));
    $result .= creerFieldset('Mission', array(
        select('<b>Mission :</b>', 'MISSION', array() + donner_liste('MISSION', 'MIS', 0, $idmission), 3, 3, false, $idmission),
        $cloture,
        sautLigne(),
        afficher('Nom de la mission :', $row2['MIS_NOM'], 'span3', 'span3'),
        $NSEQ,
        $PRO_NO,
        sautLigne(),
        afficher('Numéro de cde :', $row2['MIS_NUMCMDE'], 'span3', 'span3'),
        afficher('Date de cde :', $row2['MIS_DATECMDE'], 'span3', 'span3'),
        sautLigne(),
        afficher('Début de mission :', $row2['MIS_DTDEBUT'], 'span3', 'span3'),
        afficher('Fin de mission :', $row2['MIS_DTFIN'], 'span3', 'span3'),
        sautLigne(),
        afficher('Durée prévisionnelle (jours):', $row2['MIS_NBJOURS'], 'span3', 'span3'),
        //afficher('Suivi par :', $row2['MIS_SUIVIPAR'], 'span3', 'span3'),
        sautLigne(),
        afficher('Forfait :', $row2['MIS_FORFAIT'] == 1 ? 'Oui' : 'Non', 'span3', 'span3'),
        afficher('Montant du forfait :', $row2['MIS_MONTFORFAIT'], 'span3', 'span3'),
        sautLigne(),
        afficher('Taux journalier :', $row2['MIS_TJM'], 'span3', 'span3'),
        afficher('Prix d\'achat :', $row2['MIS_PA'], 'span3', 'span3'),
        sautLigne(),
        afficher_textarea('Commentaire :', $row2['MIS_COMMENTAIRE'], 3, 8, 4, 80, 'textarea800'),
        input('', 'idmission', 2, 3, true, 'hidden')
    ));
    return $result;
}

?>
