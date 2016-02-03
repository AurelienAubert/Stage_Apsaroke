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
            $GLOBALS['titre_page'] = '<div class="accueil">Accueil</div>';
            include 'menu/version.php';
            include ("menu/menu_accueil.php");
        ?>
        <h4 class="not_printed "><?php echo $GLOBALS['num_version'] . ' du ' . $GLOBALS['date_version']; ?>, compatible IE 11, Chrome, Firefox</h4>
                <br/>
                <br/>
                <br/>
		<h3>Le site est actuellement en modification. <br/>Aucun traitement n'est disponible</h3>
    </body>
</html>