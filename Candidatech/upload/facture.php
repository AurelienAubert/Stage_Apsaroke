<?php
/*Prefacturation
 * 1    |   CREPU   | 23:06/2014    | Modification des colonnes
 * 
 * 
 * 
 * 
 * 
 * 
 */
require 'inc/verif_session.php';
include ('calendrier/fonction_nomMois.php');
include ('inc/connection.php');
include ("menu/menu_global.php");

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php 
        include 'head.php';
        ?>
        <title>Facture</title> 

    </head>   
    <body>    
        <?php
        
        if(!isset($_GET['action']) && isset($_GET['type']))
        {
            if($_GET['type'] == 'officiel')
            {
                $query_facture = 'SELECT * FROM facture WHERE FACT_ANNEE ="'.$_POST['annee'].'" AND FACT_MOIS ="'.$_POST['mois'].'"';
            }
            else
            {
                $query_facture = 'SELECT * FROM facture_pro_format WHERE FACT_ANNEE ="'.$_POST['annee'].'" AND FACT_MOIS ="'.$_POST['mois'].'"';
            }
            
            $stmtFacture = $GLOBALS['connexion']->query($query_facture);
            echo '<div class="text-center">';
                echo '<form name="chx_fact" method="post" action="facture.php?action=v">';
                    echo '<fieldset>';
                        echo '<legend>Choix d\'une facture - '.nomMois($_POST['mois']).' '.$_POST['annee'].'</legend>';
                        echo '<select name="FACT_NUM">';
                        while($row_fact = $stmtFacture->fetch_assoc())
                        {
                        echo '<option name="'.$row_fact['FACT_NUM'].'" value="'.$row_fact['FACT_NUM'].'">'.$row_fact['FACT_NUM'].'</option>';
                        }
                        echo '</select>';
                        echo '</br><input class="btn btn-primary" name="submitForm" type="submit"/>';
                    echo '</fieldset>';
                echo '</form>';
            echo '</div>';
        }
        
        if(isset($_GET['action']) && $_GET['action'] == 'v')
        {   
            $query_fact_num = 'SELECT * FROM facture_pro_format WHERE FACT_NUM="'.$_POST['FACT_NUM'].'"';
            $stmtFact = $GLOBALS['connexion']->query($query_fact_num)->fetch_assoc();
           
            echo '<form id="view_fact" name="view_fact" method="post" action="facture/ex.php?action=v">';
            
            while(list($nom,$valeur) = each($stmtFact))
            {
                echo $nom.'<input name="'.$nom.'"  value="'.$valeur.'"/>';
            }
            echo '</form>';
            ?>
            <script type="text/javascript">
                document.getElementById('view_fact').submit();
            </script>
            <?php
        }
?>
    </body>
</html>