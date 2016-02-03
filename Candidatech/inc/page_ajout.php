<?php 
    /**
     * squelette d'une page d'ajout
     * variable nécéssaire : $page, tableau contenant :
     * - titre  titre de l'onglet
     * - message
     * - contenu
     */
?>
<?php //echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"/>
    <head>
        <?php include 'head.php'; ?>
        <title><?php echo $page['titre']; ?></title>
    </head>
    <body>
        <?php
            if (is_string($page['message']) && !empty($page['message'])) {
                echo '<script type="text/javascript">alert("' . $page['message'] . '");</script>';
            }
        ?>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div>' . $page['titre'] . '</div>';
        
        if(strstr( $_GET['type'], 'collaborateur_interne'))
            include ("gestion_mnemonic.php"); // Eviter qu'un input mnemonic soit présent dans chaque page d'ajout
        
        //include ("gestion_mnemonic.php");
        include ("menu/menu_global.php"); 
        ?>
        <div class="container-fluid ">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="form-horizontal well">
                <?php
                    echo $page['contenu'];
                ?>
                <div class="row-fluid">
                    <div class="offset5 span7">
                        <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i> </button>
                    </div>
                </div>
                <input type="hidden" name="urlRetourMAJ" value="<?php echo $_SERVER['REQUEST_URI']; ?>"></input>
            </form>
        </div>
        <?php
            include_once "inc/regex.php";
            include_once "inc/regex_javascript.php";
        ?>
    </body>
</html>