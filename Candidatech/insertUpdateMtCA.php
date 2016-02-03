<?php
include 'inc/connection.php';

$montant = $_POST['MONTANT_ANNEE_OBJECTIF'];

$ligne = $GLOBALS['connexion']->query('SELECT * FROM PARAMETRE WHERE PAR_LIBELLE="MONTANT_ANNEE_OBJECTIF"')->fetch_assoc();

$query = '';
if(empty($ligne)){
    $query = 'INSERT INTO PARAMETRE (`PAR_LIBELLE`, `PAR_VALEUR`) VALUES ("MONTANT_ANNEE_OBJECTIF",'.$montant.')';
}
else{
    $query = 'UPDATE PARAMETRE SET PAR_VALEUR='.$montant.' WHERE PAR_LIBELLE="MONTANT_ANNEE_OBJECTIF"';
}

$GLOBALS['connexion']->query($query);
