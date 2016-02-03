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
        <title>Pre paie</title>
        <?php include "head.php"; ?>
        <script type="text/javascript" src="js.js"></script>
    </head>

    <body>
        <!-- Barre de menu-->
        <?php
            $mois = $_POST['mois'];
            $annee = $_POST['annee'];
            $nomMois = nomMois($mois);

            $GLOBALS['retour_page'] = 'chx_date.php?type=visu_prepaie';
            $GLOBALS['titre_page'] = 'Pré Paie de '.$nomMois.' '.$annee;
            $GLOBALS['export'] = '';
            
            include ("menu/menu_global.php"); ?> 
        <!-- Affiche Bonjour Prénom Nom de la personne en loguée + date du jour-->                                                     
        <?php 
     
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
                       array_push($tab_we,$i);
                       break;
                case ($jour == 7):
                       $dimanche ++;
                       array_push($tab_we,$i);
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
        <div id = div1 style = 'margin-left:2em;' class=''>
        <?php
           echo '<br>';
           echo '<b>'; 
           echo 'Pré Paie - ';
           echo $nomMois;
           echo ' ';
           echo $annee;
           echo '</b>';
           echo '<br>';
           echo 'Jours ouvr&eacute;s : '.$jourouvre.'.';
           echo '<br>';
           echo '<br>';
        ?>
           
           <table border='1' class='table-bordered'>
           <tr style='font-size: 11px;'>
           <td style="visibility: hidden"></td>
           <td style="visibility: hidden"></td>
           <td style="visibility: hidden"></td>
           <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
           <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
           <th BGCOLOR='#bbbbff' >DATES</th>
           <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
           <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
           <td style="visibility: hidden"></td>
           <td style="visibility: hidden"></td>           
           <?php
           if($mois == 11)
           {
               ?>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <th BGCOLOR='#bbbbff'>EN EURO</th>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
               <?php
           }
           else
           {
               ?>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <th BGCOLOR='#bbbbff'>EN EURO</th>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
                <td BGCOLOR='#bbbbff' style="color:#bbbbff;"></td>
               <?php
           }
           ?>
           <td style="visibility: hidden"></td>
           </tr>
           <tr style='font-size: 10px;'>
               <th>COLLABORATEURS</th>
               <th>JW</th>               
               <th>NB ABS</th>
               <th>CP</th>
               <th>RTT</th>
               <th>MALADIES</th>
               <th>SANS<br/>SOLDE</th>
               <th>WE<br/>TRAVAILLES</th>
               <th>NB TR</th>
               <th>13EME<br/>MOIS</th>
               <th>GSM</th>
               <th>PEE</th>
               <?php
               if($mois == '11')
               {
                    ?>
                    <th>PRIMES<br/>ANNUELLES<br/>ANCIENNETE</th>
                    <?php
               }
               ?>
               <th>FRAIS<br/>JOURNALIERS</th>
               <th>COMMISSION</th>
               <th>PRIMES</th>
               <th>AVANCES<br/>SUR<br/>SALAIRE</th>
               <th>COMMENTAIRES</th>
           </tr>

        <?php
        $recapconge = array();
        $recup_abs = array();
        $recup_we = array();
        $recup_info_int = array();
        $recup_spe = array();
        $idcol = NULL;
        $commentairegeneral = null;
                
               $query_coll = "SELECT C.COL_NO, C.COL_NOM, C.COL_MNEMONIC 
                              FROM COLLABORATEUR C, INTERNE I 
                              WHERE I.COL_NO = C.COL_NO
                              AND C.COL_ETAT = 1
                              ORDER BY C.COL_NOM, C.COL_PRENOM;";
                
               $query_conge = "SELECT C.COL_NOM, A.COL_NO, A.TYA_NO, A.ABS_NBH, A.ABS_JOUR
                            FROM ABSENCE A, COLLABORATEUR C
                            WHERE C.COL_NO = A.COL_NO
                            AND A.ABS_MOIS = '".$mois."'
                            AND A.ABS_ANNEE = '".$annee."'
                            ORDER BY C.COL_NOM, C.COL_PRENOM, A.TYA_NO, A.ABS_JOUR;";
               
               $query_tr_gsm_pee = "SELECT C.COL_NO, C.COL_NOM, I.INT_TR, I.INT_GSM, I.INT_PEE, I.INT_TREIZIEME , I.INT_PRIME_ANCI, I.INT_FRAIS
                                    FROM COLLABORATEUR C, INTERNE I
                                    WHERE C.COL_NO = I.COL_NO
                                    ORDER BY C.COL_NOM, C.COL_PRENOM;";
               
               $query_com = "SELECT DISTINCT(C.COM_TEXTE)
                            FROM COMMENTAIRE C, SPECIF_MENSUELLE S
                            WHERE C.COM_NO = S.COM_NO
                            AND S.SPM_MOIS = '".$mois."'
                            AND S.SPM_ANNEE = '".$annee."';";
               
               $rq_abs = "SELECT DISTINCT C.COL_NO, SUM(A.ABS_NBH) AS ABS
                             FROM ABSENCE A, COLLABORATEUR C
                             WHERE C.COL_NO = A.COL_NO 
                             AND ABS_MOIS = '".$mois."'
                             AND ABS_ANNEE = '".$annee."'";
               
               $rq_we = "SELECT C.COL_NO, R.RAM_JOUR, R.RAM_NBH
                         FROM RAM R, COLLABORATEUR C
                         WHERE C.COL_NO = R.COL_NO 
                         AND R.RAM_MOIS = '".$mois."'
                         AND R.RAM_ANNEE = '".$annee."'";
               
               $rq_spe = "SELECT S.SPM_NO, C.COL_NO, S.SPM_COMMISSION, S.SPM_A_DEDUIR, S.SPM_PRIME, S.SPM_COMMENTAIRE 
                          FROM SPECIF_MENSUELLE S, COLLABORATEUR C
                          WHERE C.COL_NO = S.COL_NO
                          AND S.SPM_MOIS = '".$mois."'
                          AND S.SPM_ANNEE = '".$annee."'";
               
               $result_comm = $connexion->query($query_com);
               $ligne_com = mysqli_fetch_assoc($result_comm);

               if(mysqli_num_rows($result_comm) == 1)
               {
                   $commentairegeneral = $ligne_com['COM_TEXTE'];
               }
               
               $result_coll = $connexion->query($query_coll);
               
               $result_tr_gsm_pee = $connexion->query ($query_tr_gsm_pee);
               $ligne_tr_gsm_pee = mysqli_fetch_assoc($result_tr_gsm_pee);
               
               $result_conge = $connexion->query($query_conge);
               $ligne_conge = mysqli_fetch_assoc($result_conge);
               
                while($ligne_coll = mysqli_fetch_assoc($result_coll))
                {
                    $recup_abs[$ligne_coll['COL_NO']] = array( 'nom' => $ligne_coll['COL_NOM'], 'mnemonic' => $ligne_coll['COL_MNEMONIC']);
                    $idcol = $ligne_coll['COL_NO'];
                    $recapconge[$ligne_coll['COL_NO']][$ligne_coll['COL_NOM']] = array();
                    $recup_spe[$ligne_coll['COL_NO']] = array();
                    
                    $query_abs = $rq_abs. ' AND C.COL_NO=' . $idcol . ' GROUP BY C.COL_NO ORDER BY C.COL_NOM, C.COL_PRENOM;';
                    $query_we = $rq_we. ' AND C.COL_NO=' . $idcol . ' ORDER BY C.COL_NOM, C.COL_PRENOM, R.RAM_JOUR;';
                    $query_spe = $rq_spe. ' AND C.COL_NO=' . $idcol . ' ORDER BY C.COL_NOM, C.COL_PRENOM;';
                    
                    $result_abs = $connexion->query($query_abs);
                    $ligne_abs = mysqli_fetch_assoc($result_abs);
                    
                    $result_we = $connexion->query($query_we);
                    $ligne_we = mysqli_fetch_assoc($result_we);
                    
                    $result_spe = $connexion->query($query_spe);
                    $ligne_spe = mysqli_fetch_assoc($result_spe);
                    
                    //remplit le tableau avec les congés en fonction du numéro du col.
                    do
                    {
                        if($idcol == $ligne_conge['COL_NO'])
                        {
                            $recapconge[$ligne_conge['COL_NO']][$ligne_conge['COL_NOM']][$ligne_conge['TYA_NO']][] = array($ligne_conge['ABS_JOUR'], $ligne_conge['ABS_NBH']);
                        }
                        else
                        {
                            break;
                        }
                    }while($ligne_conge = mysqli_fetch_assoc($result_conge));
                    
                    //remplit le tableau avec le booléen qui indique si le col à le droit aux TR, le GSM, le 13ème mois et le PEE en fonction du numéro col.
                    do
                    {
                        if($idcol == $ligne_tr_gsm_pee['COL_NO'])
                        {
                            $recup_info_int[$ligne_coll['COL_NO']] = array($ligne_tr_gsm_pee['INT_TR'], $ligne_tr_gsm_pee['INT_GSM'], $ligne_tr_gsm_pee['INT_PEE'], $ligne_tr_gsm_pee['INT_TREIZIEME'], $ligne_tr_gsm_pee['INT_PRIME_ANCI'], $ligne_tr_gsm_pee['INT_FRAIS']);
                        }
                        else
                        {
                            break;
                        }
                    }while($ligne_tr_gsm_pee = mysqli_fetch_assoc ($result_tr_gsm_pee));
                    
                    //remplit le tableau avec les samedi et dimanche travaillés en fonction du numéro col.
                    do
                    {
                        if($idcol == $ligne_we['COL_NO'])
                        {
                            foreach($tab_we as $WE)
                            {
                                if($ligne_we['RAM_JOUR'] == $WE)
                                {
                                    $recup_we[$ligne_coll['COL_NO']][] = array($ligne_we['RAM_JOUR'], $ligne_we['RAM_NBH']);
                                }
                            }
                        }
                        else
                        {
                            break;
                        }
                    }while($ligne_we = mysqli_fetch_assoc($result_we));
                    
                    //remplit le tableau avec le total des abs en fonction du numéro col.
                    if(isset($ligne_abs['ABS']))
                    {
                        $recup_abs[$ligne_coll['COL_NO']]['ABS'] = $ligne_abs['ABS'];
                    }
                    else
                    {
                        $recup_abs[$ligne_coll['COL_NO']]['ABS'] = '0';
                    }
                    
                    if(isset($ligne_frais['FRAIS']))
                    {
                        $recup_abs[$ligne_coll['COL_NO']]['FRAIS'] = $ligne_frais['FRAIS'];
                    }
                    else
                    {
                        $recup_abs[$ligne_coll['COL_NO']]['FRAIS'] = '0';
                    }
                    
                    if(isset($ligne_spe['SPM_NO']))
                    {
                        $recup_spe[$ligne_coll['COL_NO']]= array($ligne_spe['SPM_COMMISSION'], $ligne_spe['SPM_A_DEDUIR'], $ligne_spe['SPM_PRIME'], $ligne_spe['SPM_COMMENTAIRE']);
                    }
                }
                    /*
                     * donc ici, il nous reste un tableau avec au moins un tableau vide par collab...
                     * si ce collab a des absences, on les conserves dans des sous tableaux...
                     * et sinon on conserve uniquement le nom
                     */
                
                    //affiche le nom du col, les jours travaillés et le total des abs.
                    foreach($recup_abs as $id=>$contenu)
                    { 
                      ?>
                      <tr align="CENTER">
                      <td><?php echo '<b>'.$contenu['nom'].'</b><br/>'.'<i>('.$contenu['mnemonic'].')</i>'; ?></td>
                      <td><?php echo $jourouvre - $contenu['ABS']; ?></td>
                      <td><?php echo $contenu['ABS']; ?></td>
                      <?php
                        $compCP = 0;
                        $compRTT = 0;
                        $compMal = 0;
                        $compSS = 0;
                        $compWE = 0;
                        $debutCP = true;
                        $debutRTT = true;
                        $debutMal = true;
                        $debutSS = true;
                        $debutWE = true;
                      
                      //affiche les dates de congés en fonction de leur type.
                      foreach($recapconge as $id1=>$element2)
                      {   
                          foreach($element2 as $element)
                          {
                                if($id==$id1)
                                {
                                  if(isset($element[2]))
                                      {
                                          ?>
                                          <td>
                                          <?php
                                          foreach($element[2] as $element1)
                                          {
                                              if($element1[0] != 0)
                                              {
                                                  if($element1[1] == 0.5)
                                                  {
                                                      if($compCP == 3)
                                                      {
                                                          echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i><br/>';
                                                          $compCP = 0;
                                                           $debutCP = true;
                                                      }
                                                      else
                                                      {
                                                          if($debutCP == true)
                                                          {
                                                              echo '<b><i>'.$element1[0].'AM'.'</b></i>';
                                                              $compCP ++;
                                                              $debutCP = false;
                                                          }
                                                          else
                                                          {
                                                              echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i>';
                                                              $compCP ++;
                                                          }
                                                      }
                                                  }
                                                  else
                                                  {
                                                      if($compCP == 3)
                                                      {
                                                           echo '/'.$element1[0].'<br/>';
                                                           $compCP = 0;
                                                           $debutCP = true;
                                                      }
                                                      else
                                                      {
                                                          if($debutCP == true)
                                                          {
                                                                echo $element1[0];
                                                                $compCP ++;
                                                                $debutCP = false;
                                                          }
                                                          else
                                                          {
                                                                echo '/'.$element1[0];
                                                                $compCP ++;
                                                          }
                                                      }
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
                                          <td></td>
                                          <?php
                                      }
                                      if(isset($element[3]))
                                      {
                                          ?>
                                          <td>
                                          <?php
                                          foreach($element[3] as $element1)
                                          {
                                              if($element1[0] != 0)
                                              {
                                                  if($element1[1] == 0.5)
                                                  {
                                                      if($compRTT == 3)
                                                      {
                                                            echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i><br/>';
                                                            $compRTT = 0;
                                                            $debutRTT = true;
                                                      }
                                                      else
                                                      {
                                                            if($debutRTT == true)
                                                            {
                                                                echo '<b><i>'.$element1[0].'AM'.'</b></i>';
                                                                $compRTT ++;
                                                                $debutRTT = false;
                                                            }
                                                            else
                                                            {
                                                                echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i>';
                                                                $compRTT ++;
                                                            }
                                                      }
                                                  }
                                                  else
                                                  {
                                                      if($compRTT == 3)
                                                      {
                                                            echo '/'.$element1[0].'<br/>';
                                                            $compRTT = 0;
                                                            $debutRTT = true;
                                                      }
                                                      else
                                                      {
                                                          if($debutRTT == true)
                                                          {
                                                                echo $element1[0];
                                                                $compRTT ++;
                                                                $debutRTT = false;
                                                          }
                                                          else
                                                          {
                                                                echo '/'.$element1[0];
                                                                $compRTT ++;
                                                          }
                                                      }
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
                                          <td></td>
                                          <?php
                                      }
                                      if(isset($element[5]))
                                      {
                                          ?>
                                          <td>
                                          <?php
                                          foreach($element[5] as $element1)
                                          {
                                              if($element1[0] != 0)
                                              {
                                                  if($element1[1] == 0.5)
                                                  {
                                                      if($compMal == 3)
                                                      {
                                                            echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i><br/>';
                                                            $compMal = 0;
                                                            $debutMal = true;
                                                      }
                                                      else
                                                      {
                                                            if($debutMal == true)
                                                            {
                                                                echo '<b><i>'.$element1[0].'AM'.'</b></i>';
                                                                $compMal ++;
                                                                $debutMal = false;
                                                            }
                                                            else
                                                            {
                                                                echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i>';
                                                                $compMal ++;
                                                            }
                                                      }
                                                  }
                                                  else
                                                  {
                                                      if($compMal == 3)
                                                      {
                                                            echo '/'.$element1[0].'<br/>';
                                                            $compMal = 0;
                                                            $debutMal = true;
                                                      }
                                                      else
                                                      {
                                                          if($debutMal == true)
                                                          {
                                                                echo $element1[0];
                                                                $compMal ++;
                                                                $debutMal = false;
                                                          }
                                                          else
                                                          {
                                                                echo '/'.$element1[0];
                                                                $compMal ++;
                                                          }
                                                      }
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
                                          <td></td>
                                          <?php
                                      }
                                      if(isset($element[6]))
                                      {
                                          ?>
                                          <td>
                                          <?php
                                          foreach($element[6] as $element1)
                                          {
                                              if($element1[0] != 0)
                                              {
                                                  if($element1[1] == 0.5)
                                                  {
                                                      if($compSS == 3)
                                                      {
                                                          echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i><br/>';
                                                          $compSS = 0;
                                                          $debutSS = true;
                                                      }
                                                      else
                                                      {
                                                          if($debutSS == true)
                                                          {
                                                              echo '<b><i>'.$element1[0].'AM'.'</b></i>';
                                                              $compSS ++;
                                                              $debutSS = false;
                                                          }
                                                          else
                                                          {
                                                              echo '/'.'<b><i>'.$element1[0].'AM'.'</b></i>';
                                                              $compSS ++;
                                                          }
                                                      }
                                                  }
                                                  else
                                                  {
                                                      if($compSS == 3)
                                                      {
                                                          echo '/'.$element1[0].'<br/>';
                                                          $compSS = 0;
                                                          $debutSS = true;
                                                      }
                                                      else
                                                      {
                                                          if($debutSS == true)
                                                          {
                                                              echo $element1[0];
                                                              $compSS ++;
                                                              $debutSS = false;
                                                          }
                                                          else
                                                          {
                                                              echo '/'.$element1[0];
                                                              $compSS ++;
                                                          }
                                                          
                                                      }
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
                                          <td></td>
                                          <?php
                                      }
                                }
                          }
                      }
                      ?><td><?php
                      
                      //affiche le détail des WE travaillés.
                      foreach($recup_we as $id2=>$value)
                      {
                           if($id == $id2 && isset($value))
                           {
                               foreach($value as $jourwe)
                               {
                                   if($jourwe[1] == 0.5)
                                   {
                                       if($compWE == 3)
                                       {
                                           echo '/'.'<b><i>'.$jourwe[0].'AM'.'</b></i><br/>';
                                           $compWE = 0;
                                           $debutWE = true;
                                       }
                                       else
                                       {
                                           if($debutWE == true)
                                           {
                                               echo '<b><i>'.$jourwe[0].'AM'.'</b></i>';
                                               $compWE ++;
                                               $debutWE = false;
                                           }
                                           else
                                           {
                                               echo '/'.'<b><i>'.$jourwe[0].'AM'.'</b></i>';
                                               $compWE ++;
                                           }
                                       }                                          
                                   }
                                   else
                                   {
                                       if($compWE == 3)
                                       {
                                           echo '/'.$jourwe[0].'<br/>';
                                           $compWE = 0;
                                           $debutWE = true;
                                       }
                                       else
                                       {
                                           if($debutWE == true)
                                           {
                                               echo $jourwe[0];
                                               $compWE ++;
                                               $debutWE = false;
                                           }
                                           else
                                           {
                                               echo '/'.$jourwe[0];
                                               $compWE ++;
                                           }
                                       }
                                   }
                               }
                           }
                      }
                      
                      ?></td><?php
                      
                      //affiche le nombre de TR, le GSM, le PEE...
                      foreach ($recup_info_int as $id3=>$int)
                      {
                          if($id == $id3)
                          {
                              if($int[0] == true)
                              {
                                  ?>
                                  <td><?php echo floor($jourouvre - $contenu['ABS']); ?></td>
                                  <?php
                              }
                              else
                              {
                                  ?>
                                  <td><?php echo '0'; ?></td>
                                  <?php
                              }
                              if($int[3] == true)
                              {
                                ?>
                                <td><?php echo 'Oui'; ?></td>
                                <?php
                              }
                              else
                              {
                                ?>
                                <td><?php echo 'Non'; ?></td>
                                <?php
                              }
                              if($int[1] != '0.00')
                              {
                              ?>
                              <td><?php echo $int[1]; ?></td>
                              <?php
                              }
                              else
                              {
                                  ?>
                                  <td><?php echo '0'; ?></td>
                                  <?php
                              }
                              if($int[2] != '0.00')
                              {
                              ?>
                              <td><?php echo $int[2]; ?></td>
                              <?php
                              }
                              else
                              {
                                  ?>
                                  <td><?php echo '0'; ?></td>
                                  <?php
                              }
                              if($mois == '11')
                              {
                                if($int[4] != '0.00')
                                {
                                ?>
                                <td><?php echo $int[4]; ?></td>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <td><?php echo '0'; ?></td>
                                    <?php
                                }
                              }
                              if(isset($int[5]))
                              {
                                    ?>
                                      <td><?php echo $int[5]; ?></td> 
                                    <?php
                              }
                              else
                              {
                                    ?>
                                    <td><?php echo '0'; ?></td>
                                    <?php
                              }
                          }
                      }
                      
                    foreach($recup_spe as $id4=>$value3)
                    {
                        if($id==$id4)
                        {
                            if(isset($value3[0]) && $value3[0] != 0)
                            {
                                ?>
                                <td>
                                <?php echo $value3[0]; ?>
                                </td>
                                <?php
                            }
                            else 
                            {
                                ?>
                                <td></td>
                                <?php
                            }
                            if(isset($value3[1]) && $value3[1] != 0)
                            {
                                ?>
                                <td>
                                <?php echo $value3[1]; ?>
                                </td>
                                <?php
                            }
                            else 
                            {
                                ?>
                                <td></td>
                                <?php
                            }
                            if(isset($value3[2]) && $value3[2] != 0)
                            {
                                ?>
                                <td>
                                <?php echo $value3[2]; ?>
                                </td>
                                <?php
                            }
                            else 
                            {
                                ?>
                                <td></td>
                                <?php
                            }
                            if(isset($value3[3]) && $value3[3] != null)
                            {
                                ?>
                                <td>
                                <?php echo $value3[3];?>
                                </td>
                                <?php
                            }
                            else 
                            {
                                ?>
                                <td></td>
                                <?php
                            }
                        }
                    }
                    ?>
                      </tr>
                      <?php
                  } 
                ?> 
        </table>
        <br/>
        <br/>
        <b><u>Commentaire g&eacute;n&eacute;ral :</b></u>
        <br/>
        <br/>
        <?php echo $commentairegeneral;?>
        <br/>
        <br/>
        <br/>
        </div>
        <form id="pdf" method="POST" action="pdf_PrePaie.php">
            <input name="mois" type="hidden" value="<?php echo $nomMois; ?>"></input>
            <input name="annee" type="hidden" value="<?php echo $annee; ?>"></input>
            <input name="joursouvres" type="hidden" value="<?php echo $jourouvre; ?>"></input>
            <input name="mois" type="hidden" value="<?php echo $nomMois; ?>"></input>
            <input name="tab_prepaie" type="hidden" value=""></input>
            <input name="titrepdf" type="hidden" value="Pr&eacute;paie"></input>
            <input name="titrejo" type="hidden" value="Jours ouvr&eacute;s"></input>
            <input name="titre" type="hidden" value="Commentaire g&eacute;n&eacute;ral :"></input>
            <input name="commentaire" type="hidden" value="<?php echo $commentairegeneral; ?>"></input>
        </form>
        <script type="text/javascript">
                $("tr:odd").each(function() {
                    $(this).children().css("background-color", "#bbbbff");
                });
                
                $(document).ready(function (){
                $('#pdf').submit(function() {
                        var tableau = new Array();

                        $('table').find('tr').each(function() {
                            var ligne = new Array();
                            $(this).children().each(function() {
                                var texte = $(this).html().replace(/(<[/]?[bi]>)/g, '');
                                if (texte.indexOf('name')!= -1) {
                                    texte = $(this).children().val();
                                }
                                else {
                                    texte = texte.replace(/<br>/g, "\n");
                                }
                                ligne.push({
                                    height:$(this).height(),
                                    width:$(this).width(),
                                    texte:texte.replace(/^\s+/g,'').replace(/\s+$/g,''),
                                    couleur:$(this).css('background-color')
                                });
                            });
                            tableau.push(ligne);
                            
                        });
                        $('input[name="tab_prepaie"]').val(JSON.stringify(tableau));
                    });
                });
        </script>
    </body>
</html>