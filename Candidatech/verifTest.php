<?php
$cnx = mysql_connect('127.0.0.1','root', '');
$db = mysql_select_db('chiricahuasv');

$id_col = $_POST['id'];
$contenu_commentaire = $_POST['tab_commentaire'];
$contenu_commission = $_POST['tab_commission'];
$contenu_accompte = $_POST['tab_accompte'];
$contenu_prime = $_POST['tab_prime'];

$rq = "SELECT * FROM specif_mensuelle WHERE COL_NO = '".$id_col."' AND SPM_MOIS = 1 AND SPM_ANNEE =2014;";
$res = mysql_query($rq);
$ligne = mysql_fetch_assoc($res);

if(mysql_num_rows($res)>=1)
{
    $query_update_commission = "UPDATE specif_mensuelle SET SPM_COMMISSION='" . $contenu_commission . "', SPM_COMMENTAIRE ='" . $contenu_commentaire . "', SPM_A_DEDUIR = '".$contenu_accompte."', SPM_PRIME = '".$contenu_prime."' WHERE SPM_NO= '".$ligne['SPM_NO']."';";
    mysql_query($query_update_commission);
}
 else
 {    

    $query  = "INSERT INTO  specif_mensuelle (SPM_COMMISSION, SPM_A_DEDUIR, SPM_PRIME, SPM_COMMENTAIRE, COL_NO, SPM_MOIS, SPM_ANNEE)";
    $query .= " VALUES ('".$contenu_commission."', '".$contenu_accompte."', '".$contenu_prime."', '".$contenu_commentaire."', '".$id_col."','1','2014')";

    mysql_query($query);
 }
?>

