<?php require "inc/verif_session.php"; ?>
<?php include "inc/connection.php"; ?>
<?php include "calendrier/fonction_nomMois.php"; ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php' ?>
        <title>Liste des RAM</title>        
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="ram">Gestion des RAM par collaborateurs ' . nomMois($_POST['mois']) . ' ' . $_POST['annee'] . '</div>';
        $type = 'tab_ram';
        $GLOBALS['retour_page'] = "chx_date.php?type=$type";
        include ("menu/menu_global.php");
        ?>

        <div class="container-fluid ">
            <div class="row-fluid">
                <table border='1' style='margin:auto;' class='table-bordered  table-condensed'>
                    <?php
                    $query_coll = "SELECT * FROM COLLABORATEUR WHERE COL_NO != 0 AND COL_ARCHIVE = 0 ORDER BY COL_NOM, COL_PRENOM";
                    $result_coll = $GLOBALS['connexion']->query($query_coll);

                    function bouton($texte, $classe, $valeur) {
                        return '<button style="display: none" type="button" class="btn btn-primary ' . $classe . '" value="' . $valeur . '">' . $texte . '</button>';
                    }

                    while ($row_coll = $result_coll->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td class="nomColl" id="coll' . $row_coll['COL_NO'] . '" style="width: 300px">' . $row_coll['COL_NOM'] . " " . $row_coll['COL_PRENOM'] . " </td>";
                        echo '<td style="width: 15px"><i id=chk-' . $row_coll['COL_NO'] . ' class="icon-remove"></i></td>';
                        echo '<td>' . bouton('Visualiser le RAM', 'voir', $row_coll['COL_NO']) . '</td>';
                        if ($_SESSION['accreditation'] < 4) {
                            echo '<td>' . bouton('Valider le RAM', 'valider', $row_coll['COL_NO']) . bouton('Invalider le RAM', 'invalider', $row_coll['COL_NO']) . '</td>';
                            echo '<td>' . bouton('Modifier le RAM', 'modifier', $row_coll['COL_NO']) . '</td>';
                            echo '<td>' . bouton('Supprimer le RAM', 'supprimer', $row_coll['COL_NO']) . '</td>';
                        }
                        echo '<td>' . bouton('Imprimer le RAM', 'imprimer', $row_coll['COL_NO']) . '</td>';
                        echo '</tr>';
                        ;
                    }

                    $query_ram = "SELECT * FROM RAM WHERE RAM_MOIS = " . $_POST['mois'] . " AND RAM_ANNEE = " . $_POST['annee'];
                    echo $query_ram;
                    $result_ram = $GLOBALS['connexion']->query($query_ram);

                    $invalide = '';
                    $valide = '';
                    if (mysqli_num_rows($result_ram) >= 1) {
                        $precedent = '';
                        while ($row_ram = $result_ram->fetch_assoc()) {
                            if ($row_ram['COL_NO'] != $precedent) {
                                if ($row_ram['RAM_VALIDATION'] == 0) {
                                    $invalide .= $row_ram['COL_NO'] . ', ';
                                } elseif ($row_ram['RAM_VALIDATION'] == 1) {
                                    $valide .= $row_ram['COL_NO'] . ', ';
                                }
                            }
                            $precedent = $row_ram['COL_NO'];
                        }
                    }
                    ?>
                </table>
                <br/>
                <br/>
            </div>
        </div>
        <form id="form" action="#" method="post">
            <input type="hidden" name="col_id" value="0"/>
            <input type="hidden" name="mode" value="0"/>
            <input type="hidden" name="annee" value="<?php echo $_POST['annee']; ?>"/>
            <input type="hidden" name="mois" value="<?php echo $_POST['mois']; ?>"/>
        </form>

        <div id="mail-ram-form" title="Envoi mail">
            <form id="contact">
                <fieldset>
                    <label for="mail_ram_objet">Objet</label>
                    <input type="text" name="objet" id="mail_ram_objet" class="text ui-widget-content ui-corner-all">
                        <label for="mail_ram_message">Message</label>
                        <textarea name="message" id="mail_ram_message" class="text ui-widget-content ui-corner-all" rows="10"></textarea>
                        <input type="hidden" id="mail_ram_to" name="to"></input>
                </fieldset>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                mois = <?php echo $_POST['mois']; ?>;
                annee = <?php echo $_POST['annee']; ?>;

                var valide = [<?php echo substr($valide, 0, -2); ?>];
                var invalide = [<?php echo substr($invalide, 0, -2); ?>];

                valide.forEach(function(element) {
                    validation(element);
                });

                invalide.forEach(function(element) {
                    invalidation(element);
                });

                $('.valider').click(function() {
                    ajax(true, $(this).val());
                    return false;
                });

                $('.supprimer').click(function() {
                    if (confirm("Voulez-vous supprimer le RAM ?")) {
                        ajaxSupprimer($(this).val());
                        return false;
                    }
                });

                $('.invalider').click(function() {
                    ajax(false, $(this).val());
                    return false;
                });

                $('.voir').click(function() {
                    $('#form').attr('action', 'tableau_ram.php');
                    $('input[name="mode"]').val('voir');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.modifier').click(function() {
                    $('#form').attr('action', 'tableau_ram.php');
                    $('input[name="mode"]').val('modif');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.imprimer').click(function() {
                    $('#form').attr('action', 'tableau_ram.php');
                    $('input[name="mode"]').val('imprimerAdm');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.nomColl').click(function() {
                    $('#mail_ram_to').val($(this).attr('id').substr(4));
                    $('#mail-ram-form').dialog('open');
                });

                $('#mail-ram-form').dialog({
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
                                    objet: $(this).find('#mail_ram_objet').val(),
                                    message: $(this).find('#mail_ram_message').val(),
                                    to: $(this).find('#mail_ram_to').val()
                                }
                            }).success(function() {
                                alert("Le mail a bien été envoyé");
                                $('#mail-ram-form').dialog('close');
                            });
                        },
                        "Annuler": function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });

            function invalidation(id) {
                $(".invalider[value='" + id + "']").hide();
                $(".imprimer[value='" + id + "']").hide();
                $(".supprimer[value='" + id + "']").show();
                $(".valider[value='" + id + "']").show();
                $(".modifier[value='" + id + "']").show();
                $(".voir[value='" + id + "']").show();
                $('#chk-' + id).attr('class', 'icon-remove');
            }

            function validation(id) {
                $(".invalider[value='" + id + "']").show();
                $(".imprimer[value='" + id + "']").show();
                $(".supprimer[value='" + id + "']").hide();
                $(".valider[value='" + id + "']").hide();
                $(".modifier[value='" + id + "']").hide();
                $(".voir[value='" + id + "']").show();
                $('#chk-' + id).attr('class', 'icon-ok');
            }

            function suppression(id) {
                $(".invalider[value='" + id + "']").hide();
                $(".imprimer[value='" + id + "']").hide();
                $(".supprimer[value='" + id + "']").hide();
                $(".valider[value='" + id + "']").hide();
                $(".modifier[value='" + id + "']").hide();
                $(".voir[value='" + id + "']").hide();
                $('#chk-' + id).attr('class', 'icon-remove');
            }

            function ajax(valider, id) {
                $.ajax({
                    url: 'inc/validation_ram.php',
                    type: 'POST',
                    async: true,
                    data: {
                        mois: mois,
                        annee: annee,
                        validation: valider,
                        id: id
                    }
                }).success(function() {
                    if (valider) {
                        validation(id);
                    }
                    else {
                        invalidation(id);
                    }
                });
            }

            function ajaxSupprimer(id) {
                $.ajax({
                    url: 'inc/suppression_ram.php',
                    type: 'POST',
                    async: true,
                    data: {
                        mois: mois,
                        annee: annee,
                        id: id
                    }
                }).success(function() {
                    suppression(id);
                });
            }
        </script>
    </body>
</html>