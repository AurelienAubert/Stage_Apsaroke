<?php 
  require "inc/verif_session.php";
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
        $tab_we = array();

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
                       $tab_we[] = $jour;
                       break;
                case ($jour == 7):
                       $dimanche ++;
                       $tab_we[] = $jour;
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
        <div id = div1 style = 'margin-left:2em'>
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
           echo 'Jour ouvr&eacute;s : '.$jourouvre.'.';
           echo '<br>';
           echo '<br>';
        ?>
           <table border="1">
               <tr>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Collaborateurs</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Jours <br> Travail</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Total <br> Absence</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>CP</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>RTT</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Mal.</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>SS</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>WE</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Frais trajet <br> journalier</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Montant &agrave;<br> rembourser</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Frais Mission <br> Note d'achat <br> &agrave; rembourser</th>
                   <th style ='padding-right: 1em; padding-left: 1em;'>Frais Mission <br> Note d'achat <br> &agrave; refacturer</th>
                   <th style ='padding-right: 5em; padding-left: 5em;'>Commentaires</th>
               </tr>
                   <?php
                        $tab_col = array();
                        $recapconge = array();
                        
                    
                       $rq_frais = " SELECT C.COL_NO, C.COL_NOM, l.LIF_MONTANT AS FRAIS
                        FROM collaborateur C, ligne_frais l, note_frais n
                        WHERE n.nof_no = l.nof_no
                        and c.COL_NO = n.col_no
                        AND n.nof_mois ='".$mois."'
                        AND n.nof_annee = '".$annee."'";
                        
                        
                        $rq ="SELECT DISTINCT A.COL_NO, C.COL_NOM, SUM(ABS_NBH) AS ABS
                        FROM absence A
                        LEFT OUTER JOIN collaborateur C
                        ON C.COL_NO = A.COL_NO 
                        WHERE ABS_MOIS = '".$mois."'
                        AND ABS_ANNEE = '".$annee."'";
                        
                        $rq_col = "SELECT DISTINCT COL_NO, COL_NOM
                        FROM collaborateur
                        order by COL_NO;";
                        
                        $query1 = "SELECT C.COL_NOM, A.COL_NO, TYA_NO, ABS_NBH, ABS_JOUR
                            from absence A, collaborateur C
                            where C.COL_NO = A.COL_NO
                            and ABS_MOIS = '".$mois."'
                            and ABS_ANNEE = '".$annee."'
                            ORDER BY COL_NO, TYA_NO, ABS_JOUR;";
                        
                        $result1 = $connexion->query($query1);
                        $ligne1 = mysqli_fetch_assoc($result1);
                        
                        $res_col = $connexion->query($rq_col);
                        $COL_NO = null;

                        while ($row_col = mysqli_fetch_assoc($res_col))
                        {
                            $tab_col [$row_col['COL_NO']]= array( 'nom' => $row_col['COL_NOM']);
                            $COL_NO = $row_col ['COL_NO'];
                            $query_absence = $rq . ' AND A.COL_NO=' . $COL_NO . ' GROUP BY C.COL_NOM ORDER BY COL_NO;';
                            $query_frais =  $rq_frais . ' AND C.COL_NO=' . $COL_NO . ' GROUP BY C.COL_NOM ORDER BY COL_NO;';
                            
                            $res = $connexion->query($query_absence);
                            $res_frais = $connexion->query($query_frais);
                            $ligne = $res->fetch_assoc();
                            $ligne_frais = $res_frais->fetch_assoc();
                            
                            $recapconge[$row_col['COL_NOM']] = array();
                    
                            do
                            {
                                if($COL_NO == $ligne1['COL_NO'])
                                {
                                    $recapconge[$ligne1['COL_NOM']][$ligne1['TYA_NO']][] = array($ligne1['ABS_JOUR'], $ligne1['ABS_NBH']);
                                }
                                else
                                {
                                    break;
                                }
                            }while($ligne1 = mysqli_fetch_assoc($result1));
                    
                            if(isset($ligne['ABS']))
                            {
                               $tab_col[$row_col['COL_NO']]['ABS'] = $ligne['ABS'];
                            }
                            else
                            {
                               $tab_col[$row_col['COL_NO']]['ABS'] = '0';
                            }
                                    
                            if(isset($ligne_frais['FRAIS']))
                            {
                               $tab_col[$row_col['COL_NO']]['FRAIS'] = $ligne_frais['FRAIS'];
                            }
                            else
                            {
                               $tab_col[$row_col['COL_NO']]['FRAIS'] = '0.0';
                            }
                            
                         }
                         
                  foreach($tab_col as $id=>$contenu)
                  {
                      ?>
                      <tr align="CENTER">
                      <td BGCOLOR='#91CDE9'><?php echo $contenu['nom']; ?></td>
                      <td BGCOLOR="#FFFF00"><?php echo $jourouvre - $contenu['ABS']; ?></td>
                      <td BGCOLOR="#ED7F10"><?php echo $contenu['ABS']; ?></td>
                      <?php
                      
                      foreach($recapconge as $nom=>$element)
                      {     
                          if($contenu['nom']==$nom)
                          {
                            if(isset($element[2]))
                                {
                                    ?>
                                    <td BGCOLOR='#357AB7' ALIGN=CENTER>
                                    <?php
                                    foreach($element[2] as $element1)
                                    {
                                        if($element1[0] != 0)
                                        {
                                            if($element1[1] == 0.5)
                                            {
                                                echo '<b><i>'.$element1[0].'/'.$mois.'/'.$annee.'</b></i></br>';
                                            }
                                            else
                                            {
                                                echo $element1[0].'/'.$mois.'/'.$annee.'</br>';
                                            }
                                        }                                    
                                    }
                                    ?>
                                    </td>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <td BGCOLOR='#357AB7' ALIGN=CENTER>&nbsp;</td>
                                    <?php
                                }
                                if(isset($element[3]))
                                {
                                    ?>
                                    <td BGCOLOR='#096A09' ALIGN=CENTER>
                                    <?php
                                    foreach($element[3] as $element1)
                                    {
                                        if($element1[0] != 0)
                                        {
                                            if($element1[1] == 0.5)
                                            {
                                                echo '<b><i>'.$element1[0].'/'.$mois.'/'.$annee.'</b></i></br>';
                                            }
                                            else
                                            {
                                                echo $element1[0].'/'.$mois.'/'.$annee.'</br>';
                                            }
                                        }                                    
                                    }
                                    ?>
                                    </td>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <td BGCOLOR='#096A09' ALIGN=CENTER>&nbsp;</td>
                                    <?php
                                }
                                if(isset($element[5]))
                                {
                                    ?>
                                    <td BGCOLOR='#88421D' ALIGN=CENTER>
                                    <?php
                                    foreach($element[5] as $element1)
                                    {
                                        if($element1[0] != 0)
                                        {
                                            if($element1[1] == 0.5)
                                            {
                                                echo '<b><i>'.$element1[0].'/'.$mois.'/'.$annee.'</b></i></br>';
                                            }
                                            else
                                            {
                                                echo $element1[0].'/'.$mois.'/'.$annee.'</br>';
                                            }
                                        }                                    
                                    }
                                    ?>
                                    </td>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <td BGCOLOR='#88421D' ALIGN=CENTER>&nbsp;</td>
                                    <?php
                                }
                                if(isset($element[6]))
                                {
                                    ?>
                                    <td BGCOLOR='#CF0A1D' ALIGN=CENTER>
                                    <?php
                                    foreach($element[6] as $element1)
                                    {
                                        if($element1[0] != 0)
                                        {
                                            if($element1[1] == 0.5)
                                            {
                                                echo '<b><i>'.$element1[0].'/'.$mois.'/'.$annee.'</b></i></br>';
                                            }
                                            else
                                            {
                                                echo $element1[0].'/'.$mois.'/'.$annee.'</br>';
                                            }
                                        }                                    
                                    }
                                    ?>
                                    </td>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <td BGCOLOR='#CF0A1D' ALIGN=CENTER>&nbsp;</td>
                                    <?php
                                }
                          }
                      }
                      echo'<td></td>';
                      echo'<td>';
                      echo number_format($contenu['FRAIS'],1);
                      echo '</td>';
                      echo'<td>';
                      echo number_format(($jourouvre - $contenu['ABS'])*$contenu['FRAIS'],2);
                      echo' &euro;';
                      echo '</td>';
                      echo'<td></td>';
                      echo'<td></td>';
                      echo'<td></td>';
                      ?>
                      </tr>        
                      <?php
                  }
                
                ?>           
           </table>
           </br>
           </br>
           <i><b>Date</b></i> : &frac12; journ&eacute;e d'absence.
           </br>
              Date : 1 journ&eacute;e d'absence.
        </br><br clear="all"> 
   <!--endprint-->
        <br></br> 
        <button class="btn btn-primary" type="button" onclick="doPrint()">Imprimer <i class="icon-print"></i> </button>
        <button class='btn btn-primary' type='button' onClick="javascript:window.location.replace('pre-paie.php');"> Retour<i class='icon-remove'></i> </button>
        <br></br>
            </div>
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

