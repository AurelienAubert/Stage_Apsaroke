<?php
    include_once "connection.php";
    if ($_POST['validation'] == 'true') {
        $query_valid = "UPDATE RAM SET RAM_VALIDATION=1 WHERE RAM_MOIS = " . $_POST['mois'] . " AND RAM_ANNEE = " . $_POST['annee'] . " AND COL_NO = " . $_POST['id'];
        $GLOBALS['connexion']->query($query_valid);
    }
    else {
        $query_unvalid = "UPDATE RAM SET RAM_VALIDATION=0 WHERE RAM_MOIS = " . $_POST['mois'] . " AND RAM_ANNEE = " . $_POST['annee'] . " AND COL_NO = " . $_POST['id'];
        $GLOBALS['connexion']->query($query_unvalid);
    }
?>
