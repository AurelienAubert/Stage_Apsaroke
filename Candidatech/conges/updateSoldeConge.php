<?php

$col_no = $_POST['COL_NO'];
$solde ='';
$table ='';
$champ ='';
if(isset($_POST['INT_SOLDE_CP']) || isset($_POST['EXT_SOLDE_CP']))
{
    $solde = isset($_POST['INT_SOLDE_CP']) ? $_POST['INT_SOLDE_CP'] : $_POST['EXT_SOLDE_CP'];
    $table = isset($_POST['INT_SOLDE_CP']) ? 'INTERNE' : 'EXTERNE';
    $champ = isset($_POST['INT_SOLDE_CP']) ? 'INT_SOLDE_CP' : 'EXT_SOLDE_CP';
}
if(isset($_POST['INT_SOLDE_RTT']) || isset($_POST['EXT_SOLDE_RTT']))
{
    $solde = isset($_POST['INT_SOLDE_RTT']) ? $_POST['INT_SOLDE_RTT'] : $_POST['EXT_SOLDE_RTT'];
    $table = isset($_POST['INT_SOLDE_RTT']) ? 'INTERNE' : 'EXTERNE';
    $champ = isset($_POST['INT_SOLDE_RTT']) ? 'INT_SOLDE_RTT' : 'EXT_SOLDE_RTT';
}


$queryUpdate = 'UPDATE '.$table.' SET '.$champ.'='.$solde.' WHERE COL_NO='.$col_no;
$GLOBALS['connexion']->query($queryUpdate);