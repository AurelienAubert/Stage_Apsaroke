<?php 
    /**
     * variable necéssaire : $page, tableau contenant :
     * - titre      titre de l'onglet
     * - contenu
     * - recherche
     * la variable $_POST doit être correctement renseignée pour le remplissage des champs
     */

?>
<?php //echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
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
            include ("menu/menu_global.php");
        ?>

        <div class="container-fluid ">
            <form action="<?php echo 'imprime_document.php'; ?>" method="post" class="form-horizontal well">
                <?php
                    echo $page['contenu'];
                    if(isset($_POST['PRO_NO']))
                        echo '<input type="hidden" name="PRO_NO" value="'.$_POST['PRO_NO'].'"></input>';
                ?>
                <input type="hidden" name="recherche" value="<?php echo $page['recherche']; ?>"></input>
                <input type="hidden" name="type" value="<?php echo $type; ?>"></input>
                <input type="hidden" name="id" value="<?php echo $page['recherche']; ?>"></input>
                <input type="hidden" name="dossier" value="<?php echo $page['dossier']; ?>"></input>
                <div class="row-fluid">
                    <div class="offset5 span7">
                        <button class='btn btn-primary' type='submit'>Lancer l'impression <i class='icon-ok'></i> </button>
                    </div>
                </div>
            </form>
        </div>                                                                            
        <?php
            include_once "inc/regex.php";
            include_once "inc/regex_javascript.php";
        ?> 
    </body>
</html>