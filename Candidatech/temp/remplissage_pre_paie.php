<?php 
 session_start (); 
 include 'calendrier/fonction_mois.php'; 
 include ('calendrier/fonction_nbjoursMois.php');
 include ('calendrier/jours_feries.php');
 include ('calendrier/fonction_dimanche_samedi.php');
 include ('calendrier/fonction_nomMois.php');
 include ('inc/connection.php');
 echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Choisir une date</title>
        <?php include "head.php"; ?>
        <script type="text/javascript" src="js.js"></script>
        <LINK rel="stylesheet" type="text/css" href="bootstrap.css"></LINK>
    </head>

    <body>
        <!-- Barre de menu-->
        <?php include ("menu/menu_global.php"); ?> 
        <!-- Affiche Bonjour Prénom Nom de la personne en loguée + date du jour-->                                                     
        <?php include ('inc/session.php');
     
        $mois = $_POST['mois'];
        $annee = $_POST['annee'];
        $nomMois = nomMois($mois);
        $date_depart = strtotime($annee."-".$mois."-01");
        $date_arrive = strtotime($annee."-".$mois."-31");

        $samedi = 0;
        $dimanche = 0;
        $ferie1 = 0;

        $nbjoursMois = nbjoursMois ($mois, $annee); //connaitre le nb de jours dans le mois
        $jour = jour_semaine($mois, 1, $annee);
        $feries = getFeries($date_depart, $date_arrive);

        for ($i=1 ; $i<=$nbjoursMois ; $i++) 
        {
            switch (true)
            {
                case in_array(mktime(0, 0, 0, $mois, $i, $annee), $feries):
                       $ferie1 ++;
                       break;
                case ($jour == 6):
                       $samedi ++;
                       break;
                case ($jour == 7):
                       $dimanche ++;
                       break;
            }
            if($jour == 7)
            {
                $jour = 0;
            }
            $jour ++;
        }

        $jourouvre = $nbjoursMois - $ferie1 - $dimanche - $samedi;
        ?>
        <!--startprint-->
           <img src="image/apsaroke.jpeg" width="200px;"></img>      
           </br>
        <?php
           echo '<br>';
           echo '<b>'; 
           echo 'Pr&eacute;s Paie - ';
           echo $nomMois;
           echo ' ';
           echo $annee;
           echo '</b>';
           echo '<br>';
        ?>
           <br>
           <table border="1">
               <tr>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Collaborateurs</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Jours <br> Travail</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Total <br> Absence</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>CP</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>RTT</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Mal</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>SS</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>WE</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Frais trajet <br> journalier <br> l&eacute;gal</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Montant &agrave;<br> rembourser</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Frais Mission <br> Note d'achat <br> &agrave; rembourser</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Frais Mission <br> Note d'achat <br> &agrave; refacturer</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Commentaires</th>
               </tr>
                   <?php
                        $tab_col = array();
                        
                    
                       $rq_frais = " SELECT C.ID_COL, C.NOM_COL, l.LIF_MONTANT AS FRAIS
                        FROM collaborateur C, ligne_frais l, note_frais n
                        WHERE n.nof_no = l.nof_no
                        and c.id_col = n.col_no
                        AND n.nof_mois ='".$mois."'
                        AND n.nof_annee = '".$annee."'";
                        
                        
                        $rq ="SELECT DISTINCT A.ID_COL, C.NOM_COL, SUM(NBH_ABS) AS ABS
                        FROM absence A
                        LEFT OUTER JOIN collaborateur C
                        ON C.ID_COL = A.ID_COL 
                        WHERE MOIS_ABS = '".$mois."'
                        AND ANNEE_ABS = '".$annee."'";
                        
                        $rq_col = "SELECT DISTINCT ID_COL, NOM_COL
                        FROM collaborateur
                        GROUP BY NOM_COL
                        order by ID_COL;";
                        
                        $res_col = $connexion->query($rq_col);
                        $id_col = null;
                       

                        while ($row_col = mysqli_fetch_assoc($res_col))
                        {
                            $tab_col [$row_col['ID_COL']]= array( 'nom' => $row_col['NOM_COL']);
                            $id_col = $row_col ['ID_COL'];
                            $query_absence = $rq . ' AND A.ID_COL=' . $id_col . ' GROUP BY C.NOM_COL ORDER BY ID_COL;';
                            $query_frais =  $rq_frais . ' AND C.ID_COL=' . $id_col . ' GROUP BY C.NOM_COL ORDER BY ID_COL;';
                            
                            $res = $connexion->query($query_absence);
                            $res_frais= $connexion->query($query_frais);
                            $ligne = $res->fetch_assoc();
                            $ligne_frais = $res_frais->fetch_assoc();
                            
                            if(isset($ligne['ABS']))
                            {
                               $tab_col[$row_col['ID_COL']]['ABS'] = $ligne['ABS'];
                            }
                            else
                            {
                               $tab_col[$row_col['ID_COL']]['ABS'] = '0.0';
                            }
                                    
                            if(isset($ligne_frais['FRAIS']))
                            {
                               $tab_col[$row_col['ID_COL']]['FRAIS'] = $ligne_frais['FRAIS'];
                            }
                            else
                            {
                               $tab_col[$row_col['ID_COL']]['FRAIS'] = '0.0';
                            }
                            
                         }
                        
                  foreach($tab_col as $id=>$contenu)
                  {
                      echo '<tr align=center>'; 
                      echo'<td>';
                      echo $contenu['nom'];
                      echo '</td>';
                      echo'<td>';
                      echo $jourouvre - $contenu['ABS'];
                      echo '</td>';
                      echo'<td>';
                      echo $contenu['ABS'];
                      echo '</td>';
                      echo'<td>';
                      echo '</td>';
                      echo'<td>';
                      echo '</td>';
                      echo'<td>';
                      echo '</td>';
                      echo'<td>';
                      echo '</td>';
                      echo'<td>';
                      echo '0.0';
                      echo '</td>';
                      echo'<td>';
                      echo number_format($contenu['FRAIS'],1);
                      echo '</td>';
                      echo'<td>';
                      echo number_format(($jourouvre - $contenu['ABS'])*$contenu['FRAIS'],2);
                      echo' &euro;';
                      echo '</td>';
                      echo'<td>';
                      echo '</td>';
                      echo'<td>';
                      echo '</td>';
                      echo'<td>';
                      echo '</td>';
                      echo '</tr>';
                  }
                
                ?>           
           </table>
   <!--endprint-->
        <br></br> 
        <button class="btn btn-primary" type="button" onclick="doPrint()">Imprimer <i class="icon-ok"></i> </button>
        <button class='btn btn-primary' type='button' onClick="javascript:window.location.replace('pre-paie.php');"> Retour<i class='icon-remove'></i> </button>
        <br></br>
        <script language="JavaScript" type="text/JavaScript">
   function doPrint() {
                    bdhtml=window.document.body.innerHTML;
                    sprnstr="<!--startprint-->";
                    eprnstr="<!--endprint-->";
                    prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);
                    prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));
                    window.document.body.innerHTML=prnhtml;
                    window.print();
                     }
       </script>
                        
    </body>
</html>

