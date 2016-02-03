<?php require "inc/verif_session.php"; ?>
<?php include "inc/connection.php"; ?>
<?php include ("calendrier/fonction_nomMois.php"); ?>
<?php include 'conges/calendrier_conges_coll.php'; ?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <?php
        $id_col = $_SESSION['col_id'];
        $calendrier = generer_calendrier($_SESSION['col_id'], '1', $_POST['annee']);
        ?>
        <style>
<?php echo $calendrier['style']; ?>
        </style>
        <title>Liste des congés</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="conges">Liste des congés de ' . $_POST['annee'] . '</div>';
        $GLOBALS['retour_page'] = 'chx_annee.php?type=tab_conges_coll';
        include ("menu/menu_global.php");
        ?>
        <!-- Affiche Bonjour Prénom Nom de la personne en loguée + date du jour-->

        <div class="container-fluid ">
            <div class="row-fluid">
                <div style="position: fixed;right:50px;">
                    <div style=""><?php echo $calendrier['recap']; ?></div>
                </div>
                <table border='1' class='table-bordered  table-condensed'>
                    <?php

                    function bouton($texte, $classe, $valeur, $style = "") {
                        return '<button style="display: block;margin: 5px auto;' . $style . '" type="button" class="btn btn-primary ' . $classe . '" value="' . $valeur . '">' . $texte . '</button>';
                    }

                    $month = array('1' => 'Janvier',
                        '2' => 'F&eacute;vrier',
                        '3' => 'Mars',
                        '4' => 'Avril',
                        '5' => 'Mai',
                        '6' => 'Juin',
                        '7' => 'Juillet',
                        '8' => 'Ao&ucirc;t',
                        '9' => 'Septembre',
                        '10' => 'Octobre',
                        '11' => 'Novembre',
                        '12' => 'D&eacute;cembre');


                    foreach ($month as $id_mois => $mois) {
                        $affiche = false;
                        $droit = 0;
                        $notification = 0;
                        $abs = true;
                        $debutSR = true;
                        $debutR = true;
                        $tab_com = array();
                        $tab_jour = array();
                        $tab_abs_no = array();

                        $query = "SELECT * FROM ABSENCE WHERE COL_NO = '" . $id_col . "' AND ABS_MOIS = '" . $id_mois . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_ETAT != 0 ORDER BY ABS_MOIS";
                        $result = $GLOBALS['connexion']->query($query);

                        if (mysqli_num_rows($result) == 0) {
                            $abs = false;
                        }

                        while ($row = $result->fetch_assoc()) {
                            array_push($tab_abs_no, $row['ABS_NO']);

                            if ($row['ABS_ETAT'] != 3) {
                                if ($row['ABS_DROIT'] == '0') {
                                    $droit = 0;
                                } elseif ($row['ABS_DROIT'] == '1') {
                                    $droit = 1;
                                }

                                if ($row['ABS_NOTIFICATION'] == '0') {
                                    $notification = 0;
                                } elseif ($row['ABS_NOTIFICATION'] == '1') {
                                    $notification = 1;
                                }
                            }
                        }
                        if ($abs == true) {
                            $query_comm = "SELECT AC.COM_NO, C.COM_TEXTE, A.ABS_ETAT, A.ABS_JOUR FROM ABSENCE A, COMMENTAIRE C, ABSENCE_COMMENTAIRE AC WHERE A.ABS_NO = AC.ABS_NO AND AC.COM_NO = C.COM_NO AND A.ABS_NO IN (" . implode(', ', $tab_abs_no) . ") ORDER BY A.ABS_ETAT, ABS_JOUR;";
                            $result_comm = $GLOBALS['connexion']->query($query_comm);

                            while ($row_comm = $result_comm->fetch_assoc()) {
                                $tab_com[$row_comm['COM_NO']][$row_comm['COM_TEXTE']][$row_comm['ABS_ETAT']][] = $row_comm['ABS_JOUR'];
                            }

                            $query = "SELECT * FROM ABSENCE WHERE ABS_NO IN (" . implode(', ', $tab_abs_no) . ") AND ABS_ETAT = 1 ORDER BY ABS_JOUR";
                            $result = $GLOBALS['connexion']->query($query);
                            
                            if (mysqli_num_rows($result) > 0) {
                                $affiche = true;
                            }
                            
                            while ($row = $result->fetch_assoc()) {
                                $tab_jour[] = $row['ABS_JOUR'];
                            }
                        }

                        $conges = generer_calendrier($id_col, $id_mois, $_POST['annee']);

                        if ($abs == false) {
                            $tableau = '<tr>'
                                    . '<td>' . $mois . ' ' . $_POST['annee'] . '</td>'
                                    . '<td class="calendrier">' . $conges['calendrier'] . '</td>'
                                    . '<td class="not_printed"></td>'
                                    . '<td class="not_printed"></td>';
                        } else {
                            $tableau = '<tr>'
                                    . '<td>' . $mois . ' ' . $_POST['annee'] . '</td>'
                                    . '<td class="calendrier">' . $conges['calendrier'] . '</td>'
                                    . '<td class="not_printed"><strong>Droit aux congés</strong><br />' . ($droit == 1 ? 'Oui' : 'Non') . '<br /><strong>Client notifié</strong><br />' . ($notification == 1 ? 'Oui' : 'Non') . '<br /></td>';
                            $tableau .= '<td class="not_printed">';

                            if ($affiche == true) {
                                $tableau .= '<strong>Congés acceptés</strong><br />';
                                $tableau .= implode(',', $tab_jour) . '<br />';
                            }

                            foreach ($tab_com as $idcom => $values) {
                                foreach ($values as $com => $values1) {
                                    foreach ($values1 as $etat => $jour) {
                                        if ($etat == 2 && $debutSR == true) {
                                            $tableau .= '<strong>Congés acceptés sous réserves</strong><br />';
                                            $debutSR = false;
                                        } elseif ($etat == 3 && $debutR == true) {
                                            $tableau .= '<strong>Congés refusés</strong><br />';
                                            $debutR = false;
                                        }
                                        $tableau .= implode(',', $jour) . ': ' . $com . '<br />';
                                    }
                                }
                            }
                            $tableau .= '</td>';
                        }
                        echo $tableau . '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
        <form id="form" action="#" method="post">
            <input type="hidden" name="col_id" value="<?php echo $id_col; ?>"/>
            <input type="hidden" name="mode" value="0"/>
            <input type="hidden" name="mois" value="0"/>
            <input type="hidden" name="annee" value="<?php echo $_POST['annee']; ?>"/>
        </form>
        <script type="text/javascript">
            $('.voir').click(function() {
                $('#form').attr('action', 'demande_conges.php');
                $('input[name="mode"]').val('voir');
                $('input[name="mois"]').val($(this).val());
                $('#form').submit();
            });
        </script>
    </body>
</html>