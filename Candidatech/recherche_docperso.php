<?php
include_once 'inc/connection.php';

$arrficperso = array();
$mnemonic = $_SESSION['mnemonic'];
//function rec_docperso($mnemonic){
    $i = 0;

    // Recherche des contrats de travail et avenants
    $q1 = "SELECT * FROM HISTDOC WHERE HID_TYPE='CDI' AND SUBSTR(HID_NOMDOC, 6, 3)='" . $mnemonic . "'";
    $r1 = $GLOBALS['connexion']->query($q1);
    if (mysqli_num_rows ($r1) > 0) {
        while($row = $r1->fetch_assoc ()){
            $arrficperso[$i++] = './collaborateur/impressions/' . $row['HID_NOMDOC'] . '.pdf';
        }
    }

    // Recherche des ordres de mission
    $q2 = "SELECT * FROM HISTDOC WHERE HID_TYPE='OMI' AND SUBSTR(HID_NOMDOC, 3, 3)='" . $mnemonic . "'";
    $r2 = $GLOBALS['connexion']->query($q2);
    if (mysqli_num_rows ($r2) > 0) {
        while($row = $r2->fetch_assoc ()){
            $arrficperso[$i++] = './projet/impressions/' . $row['HID_NOMDOC'] . '.pdf';
        }
    }
//    if (count($arrficperso) > 0){
//        return true;
//    }else{
//        return false;
//    }
//}
