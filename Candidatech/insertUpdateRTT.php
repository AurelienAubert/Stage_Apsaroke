<?php
include 'inc/connection.php';


$table = strtoupper($_POST['TABLE']);
$nb_jour = $_POST['VAL_JOUR'];
$mois = $_POST['MOIS'];
$annee = $_POST['ANNEE'];
$col_no = $_POST['COL_NO'];

$ligne = $GLOBALS['connexion']->query('SELECT * FROM RTT WHERE '
        . ' RTT_MOIS='.$mois.' AND RTT_ANNEE='.$annee.' AND COL_NO='.$col_no);

$query = '';
if($ligne->num_rows == 0){
    $query = 'INSERT INTO '.$table.' (`RTT_VAL_JOUR`, `RTT_MOIS`, `RTT_ANNEE`, `COL_NO`)'
        . ' VALUES ('.$nb_jour.','.$mois.','.$annee.','.$col_no.')';
}
else{
    $query = 'UPDATE '.$table.' SET RTT_VAL_JOUR='.$nb_jour.' WHERE COL_NO='.$col_no.' AND RTT_MOIS='.$mois.' AND RTT_ANNEE='.$annee;
}

$GLOBALS['connexion']->query($query);
