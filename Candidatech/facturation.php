<?php require "inc/verif_session.php"; ?>
<?php
/*Facturation
 * 1    |   MULCEY   | 02/12/2014    | Modification d'un Proforma ou d'une facture
 * 
 * 
 * 
 * 
 * 
 * 
 */
$GLOBALS['retour_page'] = 'visu_proforma.php?mois=' . $_POST['mois'] . '&annee=' . $_POST['annee'];

include 'facture/modification_facture.php';
