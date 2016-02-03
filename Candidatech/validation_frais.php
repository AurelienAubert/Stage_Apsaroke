<?php
include 'inc/verif_session.php';
include 'inc/connection.php';
include 'calendrier/fonction_nbjoursMois.php';
include 'calendrier/fonction_nomMois.php';
include 'inc/tableau_ram.php';
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>Demande de frais</title>
        <?php include "head.php"; ?>
    </head>
    <body>
        <?php include ("menu/menu_global.php"); ?>
        <?php
        switch ($_GET['type'])
        {
            case 'frais_urb':
                $cible = 'inc/insertion_frais.php';
                break;
            case 'frais_reel':
                $cible = 'inc/insertion_frais_reel.php';
                break;
            case 'frais_gd':
                $cible = 'inc/insertion_frais_gd.php';
                break;
            case 'frais_km':
                $cible = 'inc/insertion_frais_km.php';
                break;
        }
        ?>
        <div class="container-fluid">
            <?php 
            echo '<input type="hidden" name="col_id" value="' . $_POST['col_id'] . '" />';
            include $cible; 
            ?>
        </div>
        <div id='footer'>
            <script type="text/javascript" src="js.js"></script>
        </div>
    </body>
</html>