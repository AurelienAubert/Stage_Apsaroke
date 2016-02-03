<?php
require "inc/verif_session.php";
include_once 'inc/connection.php';
include_once 'calendrier/fonction_nbjoursMois.php';
include_once 'calendrier/fonction_nomMois.php';
include_once 'inc/tableau_ram.php';
include_once 'inc/verif_parametres.php';

if (!isset($_POST['nb_client'])) {
    $_POST['nb_client'] = 0;
}

if (!isset($_POST['col_id'])) {
    $_POST['col_id'] = $_SESSION['col_id'];
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
} else {
    $result = $GLOBALS['connexion']->query("SELECT COL_NOM, COL_PRENOM FROM COLLABORATEUR WHERE COL_NO = " . $_POST['col_id'])->fetch_assoc();
    $prenom = $result['COL_PRENOM'];
    $nom = $result['COL_NOM'];
}

$ram = generer_ram();
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>RAM</title>
        <?php include "head.php"; ?>
    </head>
    <body>
        <?php
        $GLOBALS['titre_page'] = '<div class="ram">Rapport d\'Activité Mensuel ' . nomMois($_POST['mois']) . ' ' . $_POST['annee'] . '</div>';

        if ($_POST['mode'] == 'creer') {
            $GLOBALS['retour_page'] = 'chx_date.php?type=edit_ram';
        } elseif ($_POST['mode'] == 'imprimer') {
            $GLOBALS['retour_page'] = 'chx_date.php?type=print_ram';
        } else {
            $GLOBALS['retour_page'] = 'tab_coll_ram.php';
        }
        include ("menu/menu_global.php");
        ?>
        <div class="container-fluid ">
            <div class="row-fluid">
                <div class="span3 printed">
                    <span class="not_printed">
                        <b>Statut : </b>
                        <?php
                        $result = $GLOBALS['connexion']->query("SELECT DISTINCT RAM_VALIDATION FROM RAM WHERE RAM_ANNEE='" . $_POST['annee'] . "' AND RAM_MOIS='" . $_POST['mois'] . "' AND COL_NO='" . $_POST['col_id'] . "'");
                        if (mysqli_num_rows($result) == 1) {
                            $row = $result->fetch_assoc()['RAM_VALIDATION'];
                            if ($row) {
                                echo '<span style="color:green">Validé</span>';
                            }
                        } else {
                            echo '<span style="color:red">En cours</span>';
                        }
//                        if ($_POST['mode'] == "imprimer" || $_POST['mode'] == 'imprimerAdm' || $_POST['mode'] == 'modif') {
                        if ($_POST['mode'] != "creer") {
                        ?>
                            </span>
                            <br />
                            <b>Collaborateur : </b><?php echo $prenom . " " . $nom ?> <br />
                        <?php
                            if ($_POST['mode'] == "imprimer" || $_POST['mode'] == 'imprimerAdm') {
                        ?>
                            <b>Client : </b><span id="nom_client"></span><br />
                            <b>Projet : </b><span id="nom_projet"></span>
                        <?php
                            }
                        }
                        ?>

                </div>
            </div >
            <form id="form" action="ram_envoyes.php" method="post">
                <div id="modifconge"></div>
                <input type="hidden" name="mode" value="<?php echo $_POST['mode'] ?>"></input>
                <input type="hidden" name="annee" value="<?php echo $_POST['annee'] ?>"></input>
                <input type="hidden" name="mois" value="<?php echo $_POST['mois'] ?>"></input>
                <?php echo $ram ?>
                <?php
                if ($_POST['mode'] == 'modif' || $_POST['mode'] == "creer") {
                    ?>
                    <input type="hidden" name="col_id" value="<?php echo isset($_POST['col_id']) ? $_POST['col_id'] : ''; ?>"></input>
                    <div class="row-fluid">
                        <div class="offset6 span10"> 
                            <button class='btn btn-primary' type='submit'> Envoyer  <i class='icon-ok'></i> </button>
                        </div>
                    </div>
                    <br />
                    <?php
                }
                if ($_POST['mode'] == 'imprimer' || $_POST['mode'] == 'imprimerAdm') {
                    ?>
                    <div class="row-fluid">
                        <div class="offset6 span10"> 
                            <button class='btn btn-primary not_printed' type='button' onClick="javascript:ImprimeRAM();"> Imprimer </button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
        <div id='footer'>
            <div id="nbJours" style="display:none"><?php echo nbjoursMois($_POST['mois'], $_POST['annee']); ?></div>
            <script type="text/javascript" src="js.js"></script>
        </div>
        <script type="text/javascript">
            function ImprimeRAM(){
                $('.').css('margin-top', '0px');
                var comcli = $('textarea[name^=comcli_]').val();
                if (comcli == ""){
                    $('div[name=libcomcli]').attr('class', 'not_printed');
                    $('textarea[name^=comcli_]').attr('class', 'not_printed');
                }
                window.print();
            }
    <?php if ($_POST['mode'] == 'modif' || $_POST['mode'] == 'voir' || $_POST['mode'] == 'imprimerAdm') {
    ?>
            $(document).ready(function() {
                $('#boutonRetour').removeAttr("onclick").click(function() {
                    $('#form').attr('action', 'tab_coll_ram.php').off().submit();
                });
            });
    <?php
    }
    ?>
    <?php if ($_POST['mode'] == 'modif') {
    ?>
            function changeConge(jour, val){
                var nom = "c-" + jour;
                var col = '#ff0000';
                var cls = 'Congés sans solde';
                if (val == '0'){
                    $('select[name=' + nom + ']').html('<option name="' + nom + '0" value="1" selected>1<option>');
                }else if (val == '0.5'){
                    $('select[name=' + nom + ']').html('<option name="' + nom + '0" value="0.5" selected>0.5<option>');
                }if (val == '1'){
                    $('select[name=' + nom + ']').html('<option name="' + nom + '0" value="0" selected>0<option>');
                    col = '#ffffff';
                    cls = '';
                }
                $('select[name=' + nom + ']').css('background-color', col);
                $('select[name=' + nom + ']').attr('class', cls);
                
                var valconge = 1 - val;
                var modconge = $('#modifconge').html();
                var position = modconge.indexOf('mod_' + jour);
                if (position == -1){
                    modconge += '<input type="text" name="mod_' + jour + '" value="' + valconge + '" />';
                    $('#modifconge').html(modconge);
                }else{
                    $('input[name=mod_' + jour + ']').val(valconge);
                }
            }
    <?php
    }
    ?>
        </script>
        <?php
        if ($_POST['mode'] == 'imprimer' || $_POST['mode'] == 'imprimerAdm'){
            ?>
            <script type="text/javascript">
                function affiche_client(client) {
                    console.log(client);
                    $('select[name="client"]').children().each(function() {
                        if ($(this).val() == client) {
                            $('#nom_client').html($('tr.' + client).children().first().text());
                            $('#nom_projet').html($('tr.' + client).children().eq(1).text());
                            $('.' + client).show();

                            var travaille = 0;
                            var feries = 0;
                            var absence = 0;
                            $('tr.' + client).children('td').slice(2).each(function() {
                                var val = eval($(this).find('select').val());
                                travaille += val;
                                if ($(this).attr('class')) {
                                    feries += val;
                                }
                                else {
                                    absence += (1 - val);
                                }
                            });
                            $('#nbJoursTravailles').html(travaille);
                            $('#nbJoursTravaillesWE').html(feries);
                            $('#nbJoursAbsence').html(absence);
                        }
                        else {
                            $('.' + $(this).val()).hide();
                        }
                    });
                }

                $(document).ready(function() {
                    affiche_client($('select[name="client"]').val());

                    $('select[name="client"]').change(function() {
                        affiche_client($(this).val());
                    });
                });
            </script>
            <?php
        }
        ?>
    </body>
</html>