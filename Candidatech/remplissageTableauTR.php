<?php  require "inc/verif_session.php"; ?>

<?php include ('calendrier/fonction_mois.php'); 
include ('calendrier/fonction_nbjoursMois.php');
include ('calendrier/jours_feries.php');
include ('calendrier/fonction_dimanche_samedi.php');
include ('calendrier/fonction_nomMois.php');
include ('inc/connection.php');?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Tickets Restaurant</title>
        
        <?php include "head.php"; ?>
        
        <script type="text/javascript" src="js.js"></script>
          <style>
            td span
            {
                display: none;
                font-size: 10px;
            }
            td:hover span
            {
                font-size: 10px;
                display: inline;
                position: absolute;
                text-decoration: none;
                background-color: #ffffff;
                border-radius: 6px;
            }
        </style>
    </head>

    
    <body>
        <!-- Barre de menu-->
        <?php 
        $mois = $_POST['mois'];
        $annee = $_POST['annee'];
        $nomMois = nomMois($mois);
        
        $GLOBALS['retour_page'] = 'chx_date.php?type=tr';
        $GLOBALS['export'] = ''; 
        $GLOBALS['titre_page'] = 'Fiche tickets restaurant de '.$nomMois.' '.$annee;
        include ("menu/menu_global.php"); ?> 
        <!-- Affiche Bonjour Prénom Nom de la personne en loguée + date du jour--> 
                    
                        <?php

                        
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
                        
                        
                        <div id = div1 class=''>
                            <div id=div2 style ='margin-left: 2em;'> 
                            <?php
                            echo '<br>';
                            echo '<br>';
                            echo '<b>';
                            echo 'Tickets Restaurant -- '.$nomMois.' '.$annee;
                            echo '</b>';
                            echo '<br>';
                            echo 'Jour ouvrés : '.$jourouvre.'.';
                            echo '<br>';
                            echo '<br>';
                            ?>
                                </div>
                        

                        <table border='1' class='table-bordered' align='center'>
                            <tr>
                               <th>Collaborateurs</th>
                               <th>Jours<br>travaillés</th>
                               <th>Nombre<br>absence</th>
                               <th>Droit<br>aux<br>T.R.</th>
                               <th>Nombre<br>de<br>T.R.</th>
                               <th style="padding-right: 8em; padding-left: 8em;">Signatures</th>
                            </tr>
                            
                            <?php
                            
                            $infocoll = array();
                            $idcol = NULL;
                            
                            $query = "SELECT C.COL_NO, C.COL_NOM, C.COL_MNEMONIC, I.INT_TR FROM COLLABORATEUR C, INTERNE I
                                      WHERE C.COL_NO = I.COL_NO
                                      AND C.COL_ETAT = 1
                                      ORDER BY C.COL_NOM, C.COL_PRENOM;";
                            
                            $query1 = "SELECT DISTINCT A.COL_NO, C.COL_NOM, SUM(ABS_NBH) AS ABS
                                     FROM ABSENCE A, COLLABORATEUR C
                                     WHERE C.COL_NO = A.COL_NO
                                     AND ABS_MOIS = '".$mois."'
                                     AND ABS_ANNEE = '".$annee."'
                                     GROUP BY A.COL_NO
                                     ORDER BY C.COL_NOM, C.COL_PRENOM;";
                            

                            $result = $connexion->query ($query);
                            $result1 = $connexion->query ($query1);
                            $ligne1 = mysqli_fetch_assoc($result1);
                            
                            
                            while($ligne = mysqli_fetch_assoc($result))
                            {
                                $idcol = $ligne['COL_NO'];
                                
                                $infocoll[$ligne['COL_NOM']][$ligne['COL_MNEMONIC']][$ligne['INT_TR']] = array();
                                
                                do
                                {
                                    if($idcol == $ligne1['COL_NO'])
                                    {
                                        $infocoll[$ligne1['COL_NOM']][$ligne['COL_MNEMONIC']][$ligne['INT_TR']] = $ligne1['ABS'];
                                    }
                                    else
                                    {
                                        break;
                                    }
                                }while($ligne1 = mysqli_fetch_assoc($result1));
                            }
                            foreach($infocoll as $nom=>$element)
                            {
                                foreach($element as $mne=>$element1)
                                {
                                    ?>
                                    <tr>
                                    <td style ='padding-top: 1em; padding-bottom: 1em;' ALIGN=CENTER><?php echo '<b>'.$nom.'</b><br>'.'<i>('.$mne.')</i>'; ?><span>Collaborateur</span></td>
                                    <?php
                                    foreach($element1 as $tr=>$element2)
                                    {
                                        if(isset ($element2[0]))
                                        {
                                            ?>
                                            <td ALIGN="CENTER"><?php echo $jourouvre-$element2; ?><span>Jours travaillés</span></td>
                                            <td ALIGN="CENTER"><?php echo $element2; ?><span>Jours absents</span></td>
                                            <?php
                                            if($tr == true)
                                            {
                                                ?>
                                                <td ALIGN="CENTER"><?php echo 'Oui'; ?><span>Tickets <br/>Restaurants</span></td>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <td ALIGN="CENTER"><?php echo 'Non'; ?><span>Tickets <br/>Restaurants</span></td>
                                                <?php
                                            }
                                            ?>
                                            <td ALIGN="CENTER"><?php echo floor(($jourouvre-$element2)*$tr); ?><span>Nombre <br/>Tickets Restaurants</span></td>
                                            <td><span>Signature</span></td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td ALIGN="CENTER"><?php echo $jourouvre; ?><span>Jours travaillés</span></td>
                                            <td ALIGN="CENTER"><?php echo '0.0'; ?><span>Jours absents</span></td>
                                            <?php
                                            if($tr == true)
                                            {
                                                ?>
                                                <td ALIGN="CENTER"><?php echo 'Oui'; ?><span>Tickets <br/>Restaurants</span></td>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <td ALIGN="CENTER"><?php echo 'Non'; ?><span>Tickets <br/>Restaurants</span></td>
                                                <?php
                                            }
                                            ?>
                                            <td ALIGN="CENTER"
                                                ><?php echo floor($jourouvre*$tr); ?><span>Nombre <br/>Tickets Restaurants</span></td>
                                            <td><span>Signature</span></td>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>
                    </div>
        <form id="pdf" method="POST" action="pdf_TR.php">
            <input name="mois" type="hidden" value="<?php echo $nomMois; ?>"></input>
            <input name="annee" type="hidden" value="<?php echo $annee; ?>"></input>
            <input name="joursouvres" type="hidden" value="<?php echo $jourouvre; ?>"></input>
            <input name="mois" type="hidden" value="<?php echo $nomMois; ?>"></input>
            <input name="tab_tr" type="hidden" value=""></input>
            <input name="titrejo" type="hidden" value="Jours ouvr&eacute;s"></input>
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
                                    ligne.push(texte);
                                });
                                tableau.push(ligne);

                            });
                            $('input[name="tab_tr"]').val(JSON.stringify(tableau));
                        });
                    });
        </script>
    </body>
</html>
        