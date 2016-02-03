<?php
/* Remplissage de la Nelle table MISSION depuis la table PROJET existante
 * A NE LANCER QU'UNE FOIS !!!!
*/
require 'inc/verif_session.php';
include 'inc/connection.php';
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php 
        include 'head.php';
        ?>
        <title>Remplissage table RAMENT</title> 

    </head>
    <body>
        <?php
        $GLOBALS['titre_page'] = '<div class="ram">Remplissage table RAMENT</div>';
        include ("menu/menu_global.php");

        $qram = 'SELECT * FROM RAM GROUP BY RAM_ANNEE, RAM_MOIS, COL_NO, PRO_NO';
        $sram = $GLOBALS['connexion']->query($qram);
        while($rram = $sram->fetch_assoc())
        {
            $qent  = 'INSERT INTO RAMENT (RAE_ANNEE, RAE_MOIS, COL_NO, PRO_NO, RAE_COMENT, RAE_COMCLI, RAE_VALIDATION) ';
            $qent .= 'VALUES ("' . $rram['RAM_ANNEE'] . '", "' . $rram['RAM_MOIS'] . '", "' . $rram['COL_NO'] . '", "' . $rram['PRO_NO'] . '", ';
            $qent .= '"' . $rram['COM_NO_APSA'] . '", "' . $rram['COM_NO_CLI'] . '", "' . $rram['RAM_VALIDATION'] . '")';
            echo $qent . "</br>";
            $GLOBALS['connexion']->query($qent);
        }

?>
        <div>
        </br></br>
        <H4>TRAITEMENT TERMINE</H4>
        </div>
    </body>
</html>