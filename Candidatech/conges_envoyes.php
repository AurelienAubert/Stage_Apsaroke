<?php session_start (); ?>
<?php
    if (!isset($_POST['col_id'])) {
        $_POST['col_id'] = $_SESSION['col_id'];
    }

?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"/> 
    <head>
        <?php include "head.php"; ?>
        <title>Demande de congé</title>
    </head>

    <body>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = '<span class="conges">Confirmation d\'envoi</span>';
            include ("menu/menu_global.php");
        ?> 
        <div class="container-fluid ">
            <div class="row-fluid">
                <div class="offset3 span6 ">
                    <?php include ("inc/insertion_conges.php"); ?>
                </div>
            </div>
        </div>
    </body>
</html>

