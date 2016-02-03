<?php include "inc/verif_session.php"; ?>
<?php include 'calendrier/fonction_nomMois.php'; ?>
<?php include 'conges/calendrier_conges.php'; ?>
<?php

$action = 'demande';
$texte = 'Demande ';
if (isset($_POST['col_id']) && !isset($_POST['mode'])) {
    $id = $_POST['col_id'];
} elseif (isset($_POST['coll_demande'])) {
    $id = $_POST['coll_demande'];
    list($_POST['mois'], $_POST['annee']) = explode('-', $_POST['date_demande']);
    unset($_POST['demande']);
    $action = 'valider';
    $texte = 'Validation ';
} elseif (isset($_POST['mode']) && $_POST['mode'] == 'voir') {
    $id = $_POST['col_id'];
    $action = 'valider';
    $texte = 'Validation ';
} else {
    $id = $_SESSION['col_id'];
}

if(isset($_SERVER['REDIRECT_HTTP_REFERER']))
{
    print_r($_SERVER['REDIRECT_HTTP_REFERER']);
}

$table = generer_calendrier($id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Demande de congés</title>
        <?php include "head.php"; ?>
        <style>
            tr {
                height: 25px;
            }
            td {
                min-width: 25px;
            }
            <?php echo $table['style']; ?>
        </style>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="conges">' . $texte . ' de congés pour ' . nomMois($_POST['mois']) . ' ' . $_POST['annee'] . '</div>';
        if (isset($_POST['coll_demande'])) {
            $GLOBALS['retour_page'] = 'choix_valid_conges.php';
        } elseif (isset($_POST['mode']) && $_POST['mode'] == 'voir') {
            $GLOBALS['retour_page'] = 'tab_conges.php';
        } else {
            $GLOBALS['retour_page'] = 'chx_date.php?type=dem_conge';
        }
        include ("menu/menu_global.php");
        ?>
        <div class="row-fluid  container-fluid">
            <form id="principale" action="conges_envoyes.php" method="post">
                <div class="row-fluid">
                    <div class="span3 offset4">
                        <div style="position: absolute;right:0px;">
                            <div style=""><?php echo $table['select']; ?></div>
                            <div style=""><?php echo $table['recap']; ?></div>
                        </div>
                        <?php
                        if ($action == 'valider') {
                            $result = $GLOBALS['connexion']->query("SELECT COL_NOM, COL_PRENOM FROM COLLABORATEUR WHERE COL_NO='" . $id . "'");
                            $row = $result->fetch_assoc();
                            echo '<h4 class="   ">' . $row['COL_NOM'] . ' ' . $row['COL_PRENOM'] . '</h4>';
                        }

                        echo $table['calendrier'];
                        ?>
                    </div>
                    <div class="span3">
                        <input type="hidden" name="annee" value="<?php echo $_POST['annee']; ?>"</input>
                        <input type="hidden" name="mois" value="<?php echo $_POST['mois']; ?>"</input>
                        <input type="hidden" name="action" value="<?php echo $action; ?>"</input>
                        <input type="hidden" name="col_id" value="<?php echo $id; ?>"</input>
                    </div>
                </div>

                <br/>
                <?php if ($action != 'valider') { ?>
                    <div class="span3 offset4">
                        <button class="btn btn-primary" type="submit" name="valider">Envoyer <i class="icon-ok"></i></button>
                    </div>
                    <?php
                } else {

                    function bouton($texte, $classe, $valeur, $style = "") {
                        return '<button style="display: block;margin: 5px auto;' . $style . '" type="button" class="btn btn-primary ' . $classe . '" value="' . $valeur . '">' . $texte . '</button>';
                    }

                    function radio($nom, $valeur) {
                        return '<input type="radio" name="' . $nom . '" value="1" ' . ($valeur == 1 ? 'checked' : '') . '/>Oui <input type="radio" name="' . $nom . '" value="0" ' . ($valeur == 0 ? 'checked' : '') . '/>Non';
                    }

                    $droit = 0;
                    $notification = 0;
                    $commentaire = '';
                    $query = "SELECT * FROM ABSENCE WHERE COL_NO = '" . $id . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_ETAT!=3";
                    $result = $GLOBALS['connexion']->query($query);
                    while ($row = $result->fetch_assoc()) {
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
                    if ($_SESSION['col_id'] != $id || $_SESSION['accreditation'] == 1) {
                        echo '<div class="span3 offset4" style="text-align:center"><strong>Droit aux congés</strong><br />' . radio('droit' . $id, $droit) . '<br /><strong>Client notifié</strong><br />' . radio('notification' . $id, $notification) . '<br /><strong>Commentaire :</strong><br /><textarea style="width:180px;" cols="10" rows="1" class="commentaire" id="commentaire" name="commentaire' . $id . '">' . $commentaire . '</textarea><br/>';
                        ?>
                        <button class="btn btn-primary not_printed" type="submit" name="valider">Valider <i class="icon-ok"></i></button>
                        <button class="btn btn-primary not_printed" type="button" name="refuser">Refuser <i class="icon-ban-circle"></i></button>
                        <button class="btn btn-primary not_printed" type="button" onClick="javascript:ImprimeConges();">Imprimer</button>
                        <?php
                        echo '</div>';
                    }
                }
                ?>
            </form>
        </div>
        <script type="text/javascript">
            accreditation = <?php echo $_SESSION['accreditation']; ?>;
            mois = <?php echo $_POST['mois']; ?>;
            annee = <?php echo $_POST['annee']; ?>;
            id = <?php echo $id; ?>;

            function ImprimeConges(){
                $('.').css('margin-top', '0px');
                window.print();
            }
        </script>
    <?php
    if (isset($_POST['mode']) && $_POST['mode'] == 'voir') {
    ?>
        <script type="text/javascript">
                $(document).ready(function() {
                    $('#boutonRetour').removeAttr("onclick").click(function() {
                        $('#principale').attr('action', 'tab_conges.php').off().submit();
                    });
                });
        </script>
    <?php
    }
    ?>
        <?php if ($action == 'valider') { ?>
            <script type="text/javascript">
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
                    $('[name="refuser"]').click(function() {
                        //Script permettant de supprimer des congés qui ont été invalidés auparavent
                        var data = {
                            mois: mois,
                            annee: annee,
                            action: 'refuser',
                            col_id: id,
                            commentaire: $('.commentaire').val()
                        };
                        if ($('.commentaire').val() == '')
                        {
                            alert('Vous devez entrer un commentaire pour confirmer la suppression.');
                        }
                        else
                        {
                                if (confirm("Voulez-vous supprimer la demande de congé ?")) {
                                    // Après rajout envoi mail, problème pour lancer mail(...) 
                                    // ?? cause : asynchrone ?? du coup on revient à du classique
                                    $('[name="action"]').val('refuser');
                                    $('#principale').submit();
                                }
                            
                        }
                    });
                });

                function affichage_bouton(valider, refuser) {
                    if (valider) {
                        $('button[name="valider"]').show();
                    }
                    else {
                        $('button[name="valider"]').hide();
                    }

                    if (refuser) {
                        $('button[name="refuser"]').show();
                    }
                    else {
                        $('button[name="refuser"]').hide();
                    }
                }

                function afficher_bouton() {
                    if (click_correct($(this))) {
                        $('[name="valider"], [name="refuser"]').show();
                    }
                }

                function ajax(data) {
                    data.col_id = $('[name="col_id"]').val();
                    $.ajax({
                        url: 'inc/insertion_conges.php',
                        type: 'POST',
                        async: true,
                        data: data
                    });
                }
            </script>
            <script type="text/javascript" src="tableau_conges.js"></script>
        <?php }
        ?>
        <script type="text/javascript" src="conges.js"></script>
    </body>
</html>