<?php 
require "inc/verif_session.php";
include "inc/connection.php";
include "calendrier/fonction_nomMois.php";
include 'inc/email_maj_note_frais.php';

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">";

// On teste si on revient d'une validation, d'un refus ou d'un règlement
if (isset($_POST['envoi']) && $_POST['envoi'] != null && $_POST['envoi'] != '') {
    if ($_POST['envoi'] == 'valide'){
        envoimail_valide($_POST['col_id'], $_POST['mois'], $_POST['annee']);
    }else if ($_POST['envoi'] == 'refuse'){
        envoimail_refuse($_POST['col_id'], $_POST['mois'], $_POST['annee']);
    }else if ($_POST['envoi'] == 'regle'){
    }else if ($_POST['envoi'] == 'nonregle'){
    }
}

// Si on arrive de la validation des frais, on récupère l'id collaborateur et le mois
$collab = null;
if (isset($_POST['coll_demande'])) {
    $collab = $_POST['coll_demande'];
}
if (isset($_POST['date_demande'])) {
    list($_POST['mois'], $_POST['annee']) = explode('-', $_POST['date_demande']);
}
//Si on arrive pour la 1ère fois sur cette page, on initialise ces deux variables
//Elle serviront à conserver le mois et l'année
//La page étant rafraichie et le post n'est pas conservé pour ne pas avoir à cliquer sur un bouton "Envoyer" 
//lorsque qu'un post est renvoyé
//Les variables session sont vidées dans inc/verif_session
//if(!isset($_SESSION['mois']) && !isset($_SESSION['annee']))
//{
    $_SESSION['mois'] = $_POST['mois'];
    $_SESSION['annee'] = $_POST['annee'];
    $_SESSION['PHP_SELF'] = $_SERVER['PHP_SELF'];
//}
    $mois = $_POST['mois'];
    $annee = $_POST['annee'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php' ?>
        <title>Liste des frais</title>  
    </head>
    <body id="body">
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="frais">Liste des frais de '.nomMois($mois).' '.$annee.'</div>';
        $GLOBALS['retour_page'] = 'chx_date.php?type=tab_frais';
        include ("menu/menu_global.php"); ?>
        <!-- Affiche Bonjour Prénom Nom de la personne en loguée + date du jour-->

	<form id="form" action="#" method="post">
        <div class="container-fluid ">
            <div class="row-fluid">
                <div class="legend1">Légende</div>
                <fieldset class="legend">
                    <nav>
                    <ul>
                        <li class="bleu">Bleu = En attente  </li>
                        <li class="rouge">Rouge = Non payée</li>
                        <li class="vert">Vert = Payée</li>
                    </ul>
                    </nav>
                </fieldset>
                <table border='1' style='margin:auto;' class='table-bordered  table-condensed'>
                    <thead><tr>
                        <th>Collaborateurs</th>
                        <th>Numéros<br>Note de frais</th>
                        <th>État</th>
                        <th></th>
                    </tr></thead>
                    <?php
                    if($collab) {
                        $query_coll = "SELECT * FROM COLLABORATEUR WHERE COL_NO = " . $collab;
                    }else{
                        $query_coll = "SELECT * FROM COLLABORATEUR WHERE COL_NO != 0 ORDER BY COL_NOM, COL_PRENOM";
                    }
                    $result_coll = $GLOBALS['connexion']->query($query_coll);

                    function bouton($texte, $classe, $valeur, $classeCouleur) {
                        return '<button type="button" class="btn ' . $classeCouleur . ' ' . $classe . '" value="' . $valeur . '">' . $texte . '</button>';
                    }
                    
                    while ($row_coll = $result_coll->fetch_assoc()) {
                        $query_note = "SELECT * FROM NOTE_FRAIS WHERE COL_NO =" . $row_coll['COL_NO'] . " AND NOF_MOIS = " . $mois . "  AND NOF_ANNEE = " . $annee;
                        $result_note = $GLOBALS['connexion']->query($query_note);
                        echo '<tr>';
                        if (mysqli_num_rows($result_note) > 0) {
                            echo '<td class="nomColl" id="' . $row_coll['COL_NO'] . '" style="width: 300px" rowspan=' . mysqli_num_rows($result_note) . '>' . $row_coll['COL_NOM'] . " " . $row_coll['COL_PRENOM'] . " </td>";
                            if (mysqli_num_rows($result_note) > 1) {
                                while ($row_note = $result_note->fetch_assoc()) {
                                    echo '<td>' . bouton($row_note['NOF_NSEQUENTIEL'], $row_note['TYF_NO'], $row_coll['COL_NO'], 'btn-primary') . '</td>';
                                    if ($row_note['TYF_NO'] == 1) {
                                        if ($row_note['NOF_ETAT'] == 'V') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-success') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } elseif ($row_note['NOF_ETAT'] == 'R') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-danger') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } else {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-primary') . '</td>';
                                        }
                                    } else {
                                        if ($row_note['NOF_ETAT'] == 'V') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-success') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } elseif ($row_note['NOF_ETAT'] == 'R') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('M', $row_note['TYF_NO'] . 'modifier', $row_coll['COL_NO'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-danger') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } else {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('M', $row_note['TYF_NO'] . 'modifier', $row_coll['COL_NO'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-primary') . '</td>';
                                        }
                                    }
                                    if ($row_note['NOF_REGLER'] == 0) {
                                        echo '<td>' . bouton('Non Payée', 'nonpayee', $row_note['NOF_NSEQUENTIEL'], 'btn-danger') . '</td>';
                                    } else {
                                        echo '<td>' . bouton('Payée', 'payee', $row_note['NOF_NSEQUENTIEL'], 'btn-success') . '</td>';
                                    }
                                    echo '</tr>';
                                }
                            } else {
                                while ($row_note = $result_note->fetch_assoc()) {
                                    echo '<td>' . bouton($row_note['NOF_NSEQUENTIEL'], $row_note['TYF_NO'], $row_coll['COL_NO'], 'btn-primary') . '</td>';
                                    if ($row_note['TYF_NO'] == 1) {
                                        if ($row_note['NOF_ETAT'] == 'V') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-success') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } elseif ($row_note['NOF_ETAT'] == 'R') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-danger') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } else {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-primary') . '</td>';
                                        }
                                    } else {
                                        if ($row_note['NOF_ETAT'] == 'V') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-success') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } elseif ($row_note['NOF_ETAT'] == 'R') {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('M', $row_note['TYF_NO'] . 'modifier', $row_coll['COL_NO'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-danger') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . '</td>';
                                        } else {
                                            echo '<td>' . bouton('V', 'valider', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('M', $row_note['TYF_NO'] . 'modifier', $row_coll['COL_NO'], 'btn-black') . ' '
                                            . bouton('R', 'refuser', $row_note['NOF_NSEQUENTIEL'], 'btn-black') . ' '
                                            . bouton('A', 'attente', $row_note['NOF_NSEQUENTIEL'], 'btn-primary') . '</td>';
                                        }
                                    }
                                    if ($row_note['NOF_REGLER'] == 0) {
                                        echo '<td>' . bouton('Non Payée', 'nonpayee', $row_note['NOF_NSEQUENTIEL'], 'btn-danger') . '</td>';
                                    } else {
                                        echo '<td>' . bouton('Payée', 'payee', $row_note['NOF_NSEQUENTIEL'], 'btn-success') . '</td>';
                                    }
                                    echo '</tr>';
                                }
                            }
                        } else {
                            echo '<td class="nomColl" id="' . $row_coll['COL_NO'] . '" style="width: 300px">' . $row_coll['COL_NOM'] . " " . $row_coll['COL_PRENOM'] . " </td>";
                            echo '<td></td><td></td><td></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
                <br/>
                <br/>
            </div>
        </div>
            <input type="hidden" name="col_id" value="0" />
            <input type="hidden" name="mode" value="0" />
            <input type="hidden" name="coll_demande" value="<?php echo $collab; ?>" />
            <input type="hidden" name="annee" value="<?php echo $annee; ?>" />
            <input type="hidden" name="mois" value="<?php echo $mois; ?>"/>
            <input type="hidden" name="envoi" value="" />
        </form>

        <div id="mail-frais-form" title="Envoi mail">
            <form id="contact">
                <fieldset>
                    <label for="mail_frais_objet">Objet</label>
                    <input type="text" name="objet" id="mail_frais_objet" class="text ui-widget-content ui-corner-all">
                        <label for="mail_frais_message">Message</label>
                        <textarea name="message" id="mail_frais_message" class="text ui-widget-content ui-corner-all" rows="10"></textarea>
                        <input type="hidden" id="mail_frais_to" name="to"></input>
                </fieldset>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                mois = <?php echo $mois; ?>;
                annee = <?php echo $annee; ?>;

                $('.valider').click(function() {
                    var id = $(this).val();
                    $(".valider[value='" + id + "']").hide();
                    $(".modifier[value='" + id + "']").hide();
                    $('input[name="col_id"]').val(id);
                    $('input[name="envoi"]').val('valide');
                    $('#form').submit();
                    
                    //ajaxValider($(this).val(), mois, annee);
                    //return false;
                });

                $('.refuser').click(function() {
                    var id = $(this).val();
                    $(".refuser[value='" + id + "']").hide();
                    $(".imprimer[value='" + id + "']").hide();
                    $('input[name="col_id"]').val(id);
                    $('input[name="envoi"]').val('refuse');
                    $('#form').submit();
//                    ajaxRefuser($(this).val(), mois, annee);
//                    return false;
                });
                
                $('.attente').click(function() {
                    ajaxAttente($(this).val(), mois, annee);
                    return false;
                });

                $('.nonpayee').click(function() {
                    ajaxRadioValide($(this).val(), mois, annee);
                    return false;
                });

                $('.payee').click(function() {
                    ajaxRadioRefuse($(this).val(), mois, annee);
                    return false;
                }); 

                $('.1').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_urb');
                    $('input[name="mode"]').val('voir');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.2').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_reel');
                    $('input[name="mode"]').val('voir');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.3').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_gd');
                    $('input[name="mode"]').val('voir');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.4').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_km');
                    $('input[name="mode"]').val('voir');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.1modifier').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_urb');
                    $('input[name="mode"]').val('modif');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.2modifier').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_reel');
                    $('input[name="mode"]').val('modif');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.3modifier').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_gd');
                    $('input[name="mode"]').val('modif');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.4modifier').click(function() {
                    $('#form').attr('action', 'demande_frais.php?type=frais_km');
                    $('input[name="mode"]').val('modif');
                    $('input[name="col_id"]').val($(this).val());
                    $('#form').submit();
                });

                $('.nomColl').click(function() {
                    $('#mail_frais_to').val($(this).attr('id'));
                    $('#mail-frais-form').dialog('open');
                });

                $('#mail-frais-form').dialog({
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
                                    objet: $(this).find('#mail_frais_objet').val(),
                                    message: $(this).find('#mail_frais_message').val(),
                                    to: $(this).find('#mail_frais_to').val()
                                }
                            }).success(function() {
                                alert("Le mail a bien été envoyé");
                                $('#mail-frais-form').dialog('close');
                            });
                        },
                        "Annuler": function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });

            function attente(id) {
                $(".attente[value='" + id + "']").hide();
                $(".imprimer[value='" + id + "']").hide();
            }
            
//            function refuser(id) {
//                $(".refuser[value='" + id + "']").hide();
//                $(".imprimer[value='" + id + "']").hide();
//                $('input[name="col_id"]').val(id);
//                $('input[name="envoi"]').val('refuse');
//            }

//            function validation(id) {
//                $(".valider[value='" + id + "']").hide();
//                $(".modifier[value='" + id + "']").hide();
//                $('input[name="col_id"]').val(id);
//                $('input[name="envoi"]').val('valide');
//            }

            function regler(id) {
                $(".nonpayee[value='" + id + "']").hide();
                $('input[name="col_id"]').val(id);
                $('input[name="envoi"]').val('regle');
            }

            function nonregler(id) {
                $(".payee[value='" + id + "']").hide();
                $('input[name="col_id"]').val(id);
                $('input[name="envoi"]').val('nonregle');
            }

//            function ajaxValider(id, mois, annee) {
//                $.ajax({
//                    url: 'inc/valider_note.php',
//                    type: 'POST',
//                    async: true,
//                    data: {
//                        id: id,
//                        annee: annee,
//                        mois: mois
//                    }
//                }).success(function() {
//                    validation(id);
//                });
//                window.location.reload();
//            }

//            function ajaxRefuser(id, mois, annee) {
//                $.ajax({
//                    url: 'inc/refuser_note.php',
//                    type: 'POST',
//                    async: true,
//                    data: {
//                        id: id,
//                        annee: annee,
//                        mois: mois
//                    }
//                }).success(function() {
//                    refuser(id);
//                });
//                window.location.reload();
//            }
            
            function ajaxAttente(id, mois, annee) {
                $.ajax({
                    url: 'inc/attente_note.php',
                    type: 'POST',
                    async: true,
                    data: {
                        id: id,
                        annee: annee,
                        mois: mois
                    }
                }).success(function() {
                    attente(id);
                });
                window.location.reload();
            }

            function ajaxRadioValide(id, mois, annee) {
                $.ajax({
                    url: 'inc/validerReglement.php',
                    type: 'POST',
                    async: true,
                    data: {
                        id: id,
                        annee: annee,
                        mois: mois
                    }
                }).success(function() {
                    regler(id);
                });
                window.location.reload();
            }

            function ajaxRadioRefuse(id, mois, annee) {
                $.ajax({
                    url: 'inc/refuseReglement.php',
                    type: 'POST',
                    async: true,
                    data: {
                        id: id,
                        annee: annee,
                        mois: mois
                    }
                }).success(function() {
                    nonregler(id);
                });
                window.location.reload();
            }    
        </script>
    </body>
</html>