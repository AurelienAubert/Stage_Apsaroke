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
        <?php include ('inc/session.php'); ?>
        <div class="container">
            <div class="row">
            </div>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="offset2 span7">
        <?php
        $mois = $_POST['mois'];
        $annee = $_POST['annee'];
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
        $nomMois = nomMois($mois);
        ?>
         <!--startprint-->
           <img src="image/apsaroke.jpeg" width="200px;"></img>      
           </br>
           <?php
           echo '<br>';
           echo '<b>'; 
           echo 'Cong&eacute;s - ';
           echo $nomMois;
           echo ' ';
           echo $annee;
           echo '</b>';
           echo '<br>';
           ?>
           </br>
           <table border ="1">
           <tr>
               <th>Collaborateurs</th>
               <th style ='padding-right: 1em; padding-left: 1em;'>CP</th>
               <th style ='padding-right: 1em; padding-left: 1em;'>RTT</th>
               <th style ='padding-right: 1em; padding-left: 1em;'>MAL</th>
               <th style ='padding-right: 1em; padding-left: 1em;'>SS</th>
           </tr>
        <?php
        $bool = true;
                $TR = NULL;
                $IdCol = NULL;

                $query = "SELECT c.nom_col, a.id_tyabs, a.nbh_abs, a.jour_abs
                            from absence a, collaborateur c
                            where c.id_col = a.id_col
                            and `MOIS_ABS` = '".$mois."'
                            and `ANNEE_ABS` = '".$annee."';";
                        
                $result = $connexion->query ($query);
                
              
                if(mysqli_num_rows ($result) < 1)
                {
                    ?>
                    <script language="javascript">
                        alert("Il n'y a pas de donn\351es pour le mois et l'ann\351e s\351lectionn\351s");                    
                        window.location.replace("affichage_TR.php");
                    </script>
                    <?php
                }
                while($ligne = mysqli_fetch_assoc($result))
                {
                    
                ?>
                    <tr>
                        <td bgcolor ='#91CDE9' ALIGN="center" style='padding: 1em;'><?php echo stripslashes($ligne['nom_col']);  ?></td>
                        <?php
                        if($ligne['id_tyabs']==2)
                        {
                        ?>
                        <td bgcolor ='#C3F3EB' ALIGN="center"><?php echo stripslashes($ligne['jour_abs']).'/'.$mois.'/'.$annee; ?></td>
                        <?php
                        }
                        else 
                        {
                            ?>
                        <td bgcolor ='#C3F3EB' ALIGN="center"><?php echo '0.0'; ?></td>
                        <?php
                        }
                         if($ligne['id_tyabs']==3)
                        {
                        ?>
                        <td bgcolor ='#94F6CA' ALIGN="center"><?php echo stripslashes($ligne['jour_abs']).'/'.$mois.'/'.$annee; ?></td>
                        <?php
                        }
                        else 
                        {
                            ?>
                        <td bgcolor ='#94F6CA' ALIGN="center"><?php echo '0.0'; ?></td>
                        <?php
                        }
                        if($ligne['id_tyabs']==5)
                        {
                        ?>
                        <td bgcolor ='#B6A781' ALIGN="center"><?php echo stripslashes($ligne['jour_abs']).'/'.$mois.'/'.$annee; ?></td>
                        <?php
                        }
                        else 
                        {
                            ?>
                        <td bgcolor ='#B6A781' ALIGN="center"><?php echo '0.0'; ?></td>
                        <?php
                        }
                        if($ligne['id_tyabs']==6)
                        {
                        ?>
                        <td bgcolor ='#FCB2D8' ALIGN="center"><?php echo stripslashes($ligne['jour_abs']).'/'.$mois.'/'.$annee; ?></td>
                        <?php
                        }
                        else 
                        {
                            ?>
                        <td bgcolor ='#FCB2D8' ALIGN="center"><?php echo '0.0'; ?></td>
                        <?php
                        }
                        
                    ?>
                    </tr>
                    <?php
                }
                ?>
        </table>
        <!--endprint-->
        <br></br> 
        <button class="btn btn-primary" type="button" onclick="doPrint()">Imprimer <i class="icon-ok"></i> </button>
        <button class='btn btn-primary' type='button' onClick="javascript:window.location.replace('recapitulatif_conge_date.php');"> Retour<i class='icon-remove'></i> </button>
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
        </div>
       </div>
      </div>
    </body>
</html>
