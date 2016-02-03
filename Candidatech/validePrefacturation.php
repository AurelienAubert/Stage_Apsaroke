<?php
 include ('inc/connection.php');
 
foreach($_POST as $index=>$value)
{   
    $id_projet = $value['id'];
    $commentaire = addslashes(utf8_decode($value['COMMENTAIRE']));
    
    $query_update = "UPDATE PROJET SET PRO_COMMENTAIRE = '".$commentaire."' WHERE PRO_NO = '".$id_projet."';";
    $GLOBALS['connexion']->query($query_update);
}
     echo 'Sauvegarde effectuee';
?>
