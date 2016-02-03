<?php
include 'inc/verif_session.php';
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Demande refusée</title>
        <?php include 'head.php'; ?>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="autre">Demande refusée</div>';
        include ("menu/menu_global.php");
        ?>
        <div class="container-fluid " style="text-align:center;"><font color="red">La demande de congés a été refusée.</font></div>
    </body>
</html>
