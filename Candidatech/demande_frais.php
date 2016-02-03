<?php
require "inc/verif_session.php";
include 'inc/connection.php';
include 'calendrier/fonction_nbjoursMois.php';
include 'calendrier/fonction_nomMois.php';
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>Frais</title>
        <?php include "head.php"; ?>
    </head>
    <body>
        <?php
        $collab = null;

        if (isset($_POST['col_id'])) {
            $collab = $_POST['col_id'];
        } else {
            $collab = $_SESSION['col_id'];
        }
        echo '<input type="hidden" name="col_id" value="' . $collab . '" />';

        $mois = $_POST['mois'];
        $annee = $_POST['annee'];
        ?> 

        <!--<div class="container-fluid">-->
            <?php
            switch ($_GET['type']) {
                case 'frais_urb':
                    $cible = 'inc/tab_frais.php';
                    $next = 'validation_frais.php?type=frais_urb';
                    $titre = '<div class="frais">Note de frais forfaitaires</div>';
                    $previous = 'chx_date.php?type=frais_urb';
                    break;
                case 'frais_reel':
                    $cible = 'inc/tab_frais_reel.php';
                    $next = 'validation_frais.php?type=frais_reel';
                    $titre = '<div class="frais">Note de frais réels</div>';
                    $previous = 'chx_date.php?type=frais_reel';
                    break;
                case 'frais_gd':
                    $cible = 'inc/tab_frais_gd.php';
                    $next = 'validation_frais.php?type=frais_gd';
                    $titre = '<div class="frais">Note de frais grand déplacement</div>';
                    $previous = 'chx_date.php?type=frais_gd';
                    break;
                case 'frais_km':
                    $cible = 'inc/tab_frais_km.php';
                    $next = 'validation_frais.php?type=frais_km';
                    $titre = '<div class="frais">Note de frais kilométriques</div>';
                    $previous = 'chx_date.php?type=frais_km';
                    break;
            }
            $GLOBALS['titre_page'] = $titre;
            if ($_POST['mode'] == 'voir' || $_POST['mode'] == 'modif') {
                $GLOBALS['retour_page'] = 'tab_frais_collab.php';
            } else {
                $GLOBALS['retour_page'] = $previous;
            }
            include ("menu/menu_global.php");
            ?>
        
        <div class="container-fluid">
            <form id='form' action="<?php echo $next; ?>" method="post">
                <div>
                    <input type="hidden" name="mode" value="<?php echo $_POST['mode'] ?>"></input>
                    <input type="hidden" name="annee" value="<?php echo $_POST['annee'] ?>"></input>
                    <input type="hidden" name="mois" value="<?php echo $_POST['mois'] ?>"></input>
                    <input type="hidden" name="coll_demande" value="<?php echo $_POST['coll_demande'] ?>"></input>
                    <?php include $cible; ?>
                    <br/>
                    <div id="btn" style="text-align: center;">
                        <?php
//if ($_POST['mode'] == 'modif') {
                        if ($_POST['mode'] != 'voir' && $etat != "V") {
                            ?>
                            <input type="submit" value="Soumettre" class="btn btn-primary not_printed"></input>
                            <?php
                        }
                        if ($nof_no != null) {
//} elseif ($_POST['mode'] == 'voir') {
                            ?>
                            <button type="button" value="Imprimer" class="btn btn-primary not_printed" onclick="javascript:ImprimeFrais();">Imprimer <i class="icon-print"></i></button>
                            <?php
                        }
//} elseif ($_POST['mode'] == null) {
                        ?>
                        <!--                    <div id="btn" style="text-align: center;">
                                                <input type="submit" value="Soumettre" class="btn btn-primary"></input>
                                            </div>-->
                        <?php
//}
                        ?>
                    </div>
                </div></form>
        </div>
        <div id='footer'>
            <script type="text/javascript" src="js.js"></script>
        </div>

        <script type="text/javascript">
                            function ImprimeFrais() {
                                $('.').css('margin-top', '0px');
                                window.print();
                            }
<?php if ($_POST['mode'] == 'voir' || $_POST['mode'] == 'modif') {
    ?>
                                $(document).ready(function () {
                                    $('#boutonRetour').removeAttr("onclick").click(function () {
                                        $('#form').attr('action', 'tab_frais_collab.php').off().submit();
                                    });
                                });
    <?php
}
?>
        </script>
    </body>
</html>