<?php
include_once 'connection.php';

$select = "SELECT * FROM RAM WHERE COL_NO ='" . $_POST['id'] . "' AND RAM_MOIS ='" . $_POST['mois'] . "' AND RAM_ANNEE = '" . $_POST['annee'] . "';";
$result=$GLOBALS['connexion']->query($select);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $com_no_cli = $row['COM_NO_CLI'];
    $com_no_apsa = $row['COM_NO_APSA'];
}

$query_suppr = "DELETE FROM RAM WHERE COL_NO ='" . $_POST['id'] . "' AND RAM_MOIS ='" . $_POST['mois'] . "' AND RAM_ANNEE = '" . $_POST['annee'] . "';";
$GLOBALS['connexion']->query($query_suppr);

$com_suppr = "DELETE FROM COMMENTAIRE WHERE COM_NO IN ('" . $com_no_apsa . ' ' . $com_no_cli . "');";
$GLOBALS['connexion']->query($com_suppr);
?>
