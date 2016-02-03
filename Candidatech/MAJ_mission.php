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
        <title>Remplissage table MISSION</title> 

    </head>
    <body>
        <?php
        $GLOBALS['titre_page'] = '<div class="ram">Remplissage table MISSION</div>';
        include ("menu/menu_global.php");

        $qpro = 'SELECT P.*, C.COL_NO AS COM_NO FROM PROJET P LEFT JOIN COLLABORATEUR C ON P.PRO_SUIVIPAR=C.COL_MNEMONIC ORDER BY PRO_NO';
        $spro = $GLOBALS['connexion']->query($qpro);
        while($rpro = $spro->fetch_assoc())
        {
            $qmis  = 'INSERT INTO MISSION (PRO_NO, MIS_ORDRE, MIS_NOM, MIS_DATECMDE, MIS_NUMCMDE, MIS_DTDEBUT, MIS_DTFIN, MIS_NBJOURS, MIS_FORFAIT, MIS_MONTFORFAIT, MIS_COMMENTAIRE, MIS_TJM, MIS_PA, MIS_SUIVIPAR, MIS_NSEQUENTIEL, MIS_ARCHIVE) ';
            $qmis .= 'VALUES ("' . $rpro['PRO_NO'] . '", "1", "' . $rpro['PRO_NOM'] . '", "' . $rpro['PRO_DATECMDE'] . '", "' . $rpro['PRO_NUMCMDE'] . '", ';
            $qmis .= '"' . $rpro['PRO_DTDEBUT'] . '", "' . $rpro['PRO_DTFINPREVUE'] . '", "' . $rpro['PRO_NBJOURS'] . '", "' . $rpro['PRO_FORFAIT'] . '", "' . $rpro['PRO_MONTFORFAIT'] . '", ';
            $qmis .= '"' . $rpro['PRO_COMMENTAIRE'] . '", "' . $rpro['PRO_TJM'] . '", "' . $rpro['PRO_PA'] . '", "' . $rpro['COM_NO'] . '", "' . $rpro['PRO_NSEQUENTIEL'] . '", "0")';
            echo $qmis . "</br>";
            $GLOBALS['connexion']->query($qmis);
            $qpro = 'UPDATE PROJET SET PRO_SUIVIPAR="' . $rpro['COL_NO'] . '" WHERE PRO_NO=' . $rpro['PRO_NO'];
            $GLOBALS['connexion']->query($qpro);
        }

?>
        <div>
        </br></br>
        <H4>TRAITEMENT TERMINE</H4>
        </div>
    </body>
</html>