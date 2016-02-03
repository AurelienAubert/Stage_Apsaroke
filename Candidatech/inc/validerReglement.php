<?php

include_once "connection.php";

$query_valid = "UPDATE NOTE_FRAIS SET NOF_REGLER ='1' WHERE NOF_NSEQUENTIEL = '" . $_POST['id'] . "'";
$GLOBALS['connexion']->query ($query_valid);
?>
