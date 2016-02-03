<?php require "inc/verif_session.php"; ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Accueil</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = '<div class="accueil">Reporting</div>';
            include ("menu/menu_global.php");
        ?>
        <h4 class="not_printed "><?php echo "Page en cours de construction."; ?></h4>
<?php
if (isset($_SESSION['col_id'])) {
    ?>
        <form id='reporting' action='' method='post'>
            <div class="row-fluid not_printed">
                <div class="span4">
                    
                </div>
            </div>
        </form>
    <?php
}
?>
    </body>
</html>