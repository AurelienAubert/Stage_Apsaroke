<?php
session_start();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('collaborateur/modif_collaborateur_interne.php');
include('collaborateur/ajout_formulaire_collaborateur_interne.php');

$page['titre']='Modification des coordonn&eacute;es des collaborateurs';
$page['message'] = '';

$rq_col_interne = "SELECT * FROM INTERNE WHERE COL_NO = '".$_SESSION['col_id']."';";
$res_col_interne = $connexion->query($rq_col_interne);


if(mysqli_num_rows($res_col_interne)>=1)
{
    if (count($_POST)>1) {
        $page['message'] = update_collaborateur_interne_commun($_SESSION['col_id']);
    }
    else {
        recuperer_collaborateur_interne($_SESSION['col_id']);
    }

    //$page['message']='probleme lors de la modification';

    $page['contenu']= afficherFormulaire(true, true);
    $page['contenu'] .= '<input type="hidden" name="ETAT" value="' . $_POST['ETAT'] . '"';


    include('inc/page_modification.php');
}
 else 
{
    if (count($_POST)>1) {
        $page['message'] = update_collaborateur_externe($_SESSION['col_id']);
    }
    else {
        recuperer_collaborateur_externe($_SESSION['col_id']);
    }

    //$page['message']='probleme lors de la modification';

    $page['contenu']= afficherFormulaire(true, true);
    $page['contenu'] .= '<input type="hidden" name="ETAT" value="' . $_POST['ETAT'] . '"';


    include('inc/page_modification.php');
}

?>
