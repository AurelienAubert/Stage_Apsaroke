<?php require "inc/verif_session.php"; ?>
<?php include "inc/connection.php"; ?>
<?php include "calendrier/fonction_nomMois.php"; ?>
<?php include "conges/calendrier_conges.php"; ?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <?php
        $calendrier = generer_calendrier($_SESSION['col_id']);
        ?>
        <style>
<?php echo $calendrier['style']; ?>
        </style>
        <title>Liste des congés</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="conges">Gestion des congés pour ' . nomMois($_POST['mois']) . ' ' . $_POST['annee'] . '</div>';
        $GLOBALS['retour_page'] = 'chx_date.php?type=tab_conges';
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

                    function radio($nom, $valeur) {
                        return '<input type="radio" name="' . $nom . '" value="1" ' . ($valeur == 1 ? 'checked' : '') . '/>Oui <input type="radio" name="' . $nom . '" value="0" ' . ($valeur == 0 ? 'checked' : '') . '/>Non';
                    }

                    $query_coll = "SELECT * FROM COLLABORATEUR WHERE COL_NO != 0 ORDER BY COL_NOM, COL_PRENOM";
                    $result_coll = $GLOBALS['connexion']->query($query_coll);

                    while ($row_coll = $result_coll->fetch_assoc()) {
                        $affiche = false;
                        $tab_abs_no = array();
                        $tab_com = array();
                        $tab_jour = array();
                        $debutSR = true;
                        $debutR = true;
                        $abs = true;
                        $affiche_demande = 'display: none;';
                        $affiche_valide = 'display: none;';
                        $droit = 0;
                        $notification = 0;
                        $query = "SELECT * FROM ABSENCE WHERE COL_NO = '" . $row_coll['COL_NO'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_ETAT != 0";
                        $result = $GLOBALS['connexion']->query($query);
                        if (mysqli_num_rows($result) == 0) {
                            $abs = false;
                        }
                        while ($row = $result->fetch_assoc()) {
                            array_push($tab_abs_no, $row['ABS_NO']);

                            if ($row['ABS_VALIDATION'] == 1 || $row['ABS_VALIDATION'] == NULL) {
                                $affiche_valide = '';
                            } else {
                                $affiche_demande = '';
                            }

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
                            $query_comm = "SELECT AC.COM_NO, C.COM_TEXTE, A.ABS_ETAT, A.ABS_JOUR FROM ABSENCE A, COMMENTAIRE C, ABSENCE_COMMENTAIRE AC WHERE A.ABS_NO = AC.ABS_NO AND AC.COM_NO = C.COM_NO AND A.ABS_NO IN (" . implode(', ', $tab_abs_no) . ") ORDER BY A.ABS_ETAT, A.ABS_JOUR;";
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

                        $conges = generer_calendrier($row_coll['COL_NO']);

                        if ($abs == false) {
                            $tableau = '<tr id ="' . $row_coll['COL_NO'] . '">'
                                    . '<td class="nomColl" id="' . $row_coll['COL_NO'] . '">' . $row_coll['COL_NOM'] . '<br />' . $row_coll['COL_PRENOM'] . ' </td>'
                                    . '<td class="calendrier">' . $conges['calendrier'] . '</td>'
                                    . '<td class="not_printed"></td>'
                                    . '<td class="not_printed"></td>';
                        } else {
                            $tableau = '<tr id ="' . $row_coll['COL_NO'] . '">'
                                    . '<td class="nomColl" id="' . $row_coll['COL_NO'] . '">' . $row_coll['COL_NOM'] . '<br />' . $row_coll['COL_PRENOM'] . '</td>'
                                    . '<td class="calendrier">' . $conges['calendrier'] . '</td>'
                                    . '<td class="not_printed"><strong>Droit aux congés</strong><br />' . radio('droit' . $row_coll['COL_NO'], $droit) . '<br /><strong>Client notifié</strong><br />' . radio('notification' . $row_coll['COL_NO'], $notification) . '<br /></td>';
                            $tableau .= '<td class="not_printed"><strong>Etats et commentaires</strong><br />';

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
                                        $tableau .= implode(',', $jour) . ':<br />' . $com . '<br />';
                                    }
                                }
                            }
                            $tableau .= '</td>';
                        }
                        $tableau .= '<td class="not_printed">' . bouton('Gérer', 'voir', $row_coll['COL_NO']) . '</td>';
                        echo $tableau . '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
<!--        <form action="#" method="post">
            <input type="hidden" name="col_id" value="0"/>
            <input type="hidden" name="mode" value="0"/>
            <input type="hidden" name="annee" value="<?php echo $_POST['annee']; ?>"/>
            <input type="hidden" name="mois" value="<?php echo $_POST['mois']; ?>"/>
        </form>-->

        <form id="form" action="#" method="post">
            <input type="hidden" name="col_id" value="0"/>
            <input type="hidden" name="mode" value="0"/>
            <input type="hidden" name="annee" value="<?php echo $_POST['annee']; ?>"/>
            <input type="hidden" name="mois" value="<?php echo $_POST['mois']; ?>"/>
        </form>

        <div id="mail-conge-form" title="Envoi mail">
            <form id="contact">
                <fieldset>
                    <label for="mail_conge_objet">Objet</label>
                    <input type="text" name="objet" id="mail_conge_objet" class="text ui-widget-content ui-corner-all">
                        <label for="mail_conge_message">Message</label>
                        <textarea name="message" id="mail_conge_message" class="text ui-widget-content ui-corner-all" rows="10"></textarea>
                        <input type="hidden" id="mail_conge_to" name="to"></input>
                </fieldset>
            </form>
        </div>

        <script type="text/javascript">
            accreditation = <?php echo $_SESSION['accreditation']; ?>;
            mois = <?php echo $_POST['mois']; ?>;
            annee = <?php echo $_POST['annee']; ?>;

            function affichage_bouton(valider, refuser, id) {
                valider = valider ? 'block' : 'none';
                refuser = refuser ? 'block' : 'none';

                $('button[value="' + id + '"][class$=" valider"]').css('display', valider);
                $('button[value="' + id + '"][class$="refuser"]').css('display', refuser);
            }

            function afficher_bouton() {
                if (click_correct($(this))) {
                    var id = $(this).parents('table').parents('tr').attr('id');
                    $('button[value="' + id + '"][class$=" valider"]').css('display', 'block');
                    $('button[value="' + id + '"][class$="refuser"]').css('display', 'block');
                }
            }

            $('.voir').click(function() {
                $('#form').attr('action', 'demande_conges.php');
                $('input[name="mode"]').val('voir');
                $('input[name="col_id"]').val($(this).val());
                $('#form').submit();
            });

            function ajax(data) {
                $.ajax({
                    url: 'inc/insertion_conges.php',
                    type: 'POST',
                    async: true,
                    data: data
                }).success(function() {
                    if (data.action == 'valider') {
                        var td = $('#' + data.col_id).find('tbody').find('td[class$=" clickable"]');
                        var classe = '';
                        $(td).each(function() {
                            classe = $(this).attr('class').split(' ')[0];
                            classe = classe.split('-');
                            if (classe[1]) {
                                classe[1] = '-' + classe[1];
                            }
                            else {
                                classe[1] = '';
                            }
                            $(this).attr('class', classe.join('-valid')).unbind('click');
                        });
                        affichage_bouton(false, false, data.col_id);
                    }
                    else if (data.action == 'refuser') {
                        var td = $('#' + data.col_id).find('tbody').find('td[class$=" clickable"]');
                        $(td).each(function() {
                            $(this).attr('class', 'clickable');
                        });
                        affichage_bouton(false, false, data.col_id);
                    }
                });
            }

            $(document).ready(function() {
                $('input[name^="notification"]').change(function() {
                    var data = {
                        mois: mois,
                        annee: annee,
                        action: $(this).attr('name').substring(0, 12),
                        col_id: $(this).attr('name').substring(12, 16),
                        commentaire: $(this).prevAll('textarea').val(),
                        valeur: $(this).val()
                    };
                    ajax(data);
                });

                $('input[name^="droit"]').change(function() {
                    var data = {
                        mois: mois,
                        annee: annee,
                        action: $(this).attr('name').substring(0, 5),
                        col_id: $(this).attr('name').substring(5, 8),
                        commentaire: $(this).prevAll('textarea').val(),
                        valeur: $(this).val()
                    };
                    ajax(data);
                });

                $('.commentaire').click(function() {
                    var commentaire = $(this).prevAll('textarea');
                    var data = {
                        mois: mois,
                        annee: annee,
                        action: 'commentaire',
                        col_id: $(commentaire).attr('name').substring(11, 14),
                        commentaire: $(commentaire).val()
                    };
                    ajax(data);
                });
            
                $('.nomColl').click(function() {
                    $('#mail_conge_to').val($(this).attr('id'));
                    $('#mail-conge-form').dialog('open');
                });

                $('#mail-conge-form').dialog({
                    autoOpen: false,
                    height: 500,
                    width: 400,
                    modal: true,
                    buttons: {
                        "Envoyer": function() {
                            $.ajax({
                                url: 'inc/contactmail_coll.php',
                                type: 'POST',
                                async: true,
                                data: {
                                    objet: $(this).find('#mail_conge_objet').val(),
                                    message: $(this).find('#mail_conge_message').val(),
                                    to: $(this).find('#mail_conge_to').val()
                                }
                            }).success(function() {
                                alert("Le mail a bien été envoyé");
                                $('#mail-conge-form').dialog('close');
                            });
                        },
                        "Annuler": function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript" src="tableau_conges.js"></script>

    </body>
</html>