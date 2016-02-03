<?php
require "inc/verif_session.php";
include 'inc/connection.php';
include 'calendrier/fonction_nbjoursouvres.php';
?>

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
            td:active span
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
        $GLOBALS['retour_page'] = 'chx_date.php?type=tr';
        $GLOBALS['export'] = '';
        $GLOBALS['titre_page'] = '<div class="adm">Fiche tickets restaurant de ' . nomMois($_POST['mois']) . ' ' . $_POST['annee'].'</div>';
        include ("menu/menu_global.php");
        ?> 

        <div id = div1 class='' style="text-align: center;">
            <div id=div2 style ='margin-left: 2em;'> 
                <?php
                echo '<br>';
                echo '<br>';
                echo '<b>';
                echo 'Tickets Restaurant -- ' . nomMois($_POST['mois']) . ' ' . $_POST['annee'];
                echo '</b>';
                echo '<br>';
                echo 'Jour ouvrés : ' . joursouvres($_POST['mois'], $_POST['annee']) . '.';
                echo '<br>';
                echo '<br>';
                ?>
            </div>

            <?php
            $query_coll = "SELECT C.COL_NO, C.COL_NOM, C.COL_PRENOM, I.INT_TR, SUM(R.RAM_NBH) AS JW
                      FROM COLLABORATEUR C, INTERNE I, RAM R
                      WHERE I.COL_NO = C.COL_NO
                      AND C.COL_NO = R.COL_NO
                      AND RAM_MOIS = '" . $_POST['mois'] . "'
                      AND RAM_ANNEE = '" . $_POST['annee'] . "'
                      GROUP BY C.COL_NO
                      ORDER BY C.COL_NOM, C.COL_PRENOM";

            $rq_abs = "SELECT SUM(ABS_NBH) AS ABS
                  FROM ABSENCE
                  WHERE ABS_MOIS = '" . $_POST['mois'] . "'
                  AND ABS_ANNEE = '" . $_POST['annee'] . "'";

            $result_coll = $GLOBALS['connexion']->query($query_coll);

            $tableau_TR = array();

            while ($row_coll = $result_coll->fetch_assoc()) {

                $query_abs = $rq_abs . ' AND COL_NO=' . $row_coll['COL_NO'] . ';';

                $result_abs = $GLOBALS['connexion']->query($query_abs);
                $row_abs = mysqli_fetch_assoc($result_abs);

                if ($row_abs['ABS'] == null) {
                    $row_abs['ABS'] = 0;
                }

                $tableau_TR[$row_coll['COL_NO']] = array(
                    'COL_NOM' => $row_coll['COL_NOM'] . ' ' . $row_coll['COL_PRENOM'],
                    'COL_JW' => $row_coll['JW'],
                    'COL_ABS' => $row_abs['ABS'],
                    'COL_TR' => ($row_coll['INT_TR'] == 1 ? 'Oui' : 'Non'),
                    'NB_TR' => ($row_coll['INT_TR'] == 1 ? floor($row_coll['JW']) : '0'),
                    'SIGN' => ''
                );
            }

            echo '<table border="1" class="table-bordered table-condensed" align=center>
                <tr>
                <th>Collaborateurs</th>
                <th>JW</th>
                <th>Nb. ABS</th>
                <th>Droit aux<br>tickets restaurant</th>
                <th>Nb. de<br>tickets restaurant</th>
                <th style="padding-right: 8em; padding-left: 8em;">Signatures</th>
                </tr>';

            foreach ($tableau_TR as $ligne) {
                echo '<tr>';
                foreach ($ligne as $id => $contenu) {
                    echo'<td style="padding-top: 1em; padding-bottom: 1em;">';
                    echo $contenu;
                    echo'<span>';
                    if ($id == 'COL_NOM') {
                        echo 'Collaborateur';
                    }
                    if ($id == 'COL_JW') {
                        echo 'Jours travaillés';
                    }
                    if ($id == 'COL_ABS') {
                        echo 'Jours absents';
                    }
                    if ($id == 'COL_TR') {
                        echo 'Droit au Tickets Restaurants';
                    }
                    if ($id == 'NB_TR') {
                        echo 'Nb. de Tickets Restaurants';
                    }
                    if ($id == 'SIGN') {
                        echo 'Signature';
                    }
                }
                echo '</span></td></tr>';
            }
            ?>
        </div>
        <script type="text/javascript">
            $("tr:odd").each(function() {
                $(this).children().css("background-color", "#bbbbff");
            });
        </script>
    </body>
</html>


