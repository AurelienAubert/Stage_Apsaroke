<?php
 include ('inc/connection.php');
 
$mois = $_POST['mois'];
$annee = $_POST['annee'];
$com_general = addslashes(utf8_decode($_POST['com_general']));

$rq_com = "SELECT COM_NO FROM SPECIF_MENSUELLE WHERE SPM_MOIS = '".$mois."' AND SPM_ANNEE = '".$annee."';";
$res_com = $connexion->query($rq_com);
$ligne_com1 = mysqli_fetch_assoc($res_com);

    if(mysqli_num_rows($res_com)<1)
    {
        $query1 = "INSERT INTO COMMENTAIRE (COM_TEXTE) VALUES ('".$com_general."');";
        $connexion->query($query1);
        $idcom = $connexion->insert_id;
    }
    else
    {
        $idcom = $ligne_com1['COM_NO'];
        $query2 = "UPDATE COMMENTAIRE SET COM_TEXTE = '".$com_general."' WHERE COM_NO = '".$idcom."';";
        $connexion->query($query2);
    }

 
        foreach($_POST as $index=>$value)
        { 
            if ($index=='annee' || $index=='mois' || $index=='com_general') {
                continue;
            }
            $id_col = $value['id'];
            $contenu_commentaire = addslashes(utf8_decode($value['COMMENTAIRE']));
            $contenu_commission = $value['COMMISSION'];
            $contenu_accompte = $value['ACOMPTE'];
            $contenu_prime = $value['PRIME'];
            
            
            
            $rq = "SELECT * FROM SPECIF_MENSUELLE WHERE COL_NO = '".$id_col."' AND SPM_MOIS = '".$mois."' AND SPM_ANNEE = '".$annee."';";
            $res = $connexion->query($rq);
            $ligne = mysqli_fetch_assoc($res);
                        
            if(mysqli_num_rows($res)>=1)
            {
                $query0 = "UPDATE SPECIF_MENSUELLE SET SPM_COMMISSION = '".$contenu_commission."', SPM_A_DEDUIR = '".$contenu_accompte."', SPM_PRIME = '".$contenu_prime."', SPM_COMMENTAIRE = '".$contenu_commentaire."' WHERE SPM_NO = '".$ligne['SPM_NO']."';";
                $connexion->query($query0);
            }
            else
            {
                $query  = "INSERT INTO  SPECIF_MENSUELLE (SPM_COMMISSION, SPM_A_DEDUIR, SPM_PRIME, SPM_COMMENTAIRE, COL_NO, SPM_MOIS, SPM_ANNEE, COM_NO)";
                $query .= " VALUES ('".$contenu_commission."', '".$contenu_accompte."', '".$contenu_prime."', '".$contenu_commentaire."', '".$id_col."','".$mois."','".$annee."','".$idcom."')";
                $connexion->query($query);
            }
        }
      
     echo 'Sauvegarde effectuee';
     ?>
