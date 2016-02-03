<?php
    /**
     * contenu de la variable $page nécessaire :
     * - titre
     * - contenu
     * - message
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
            $GLOBALS['titre_page'] = '<div class="ram">' . $page['titre'] . '</div>';
            include ("menu/menu_global.php");
        ?>
        <div class="container-fluid ">
            <div class="row-fluid">
                <div class="span12">
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="form-horizontal well">
                        <div class="row-fluid">
                            <div class="offset4 span10">
                                <?php echo $page['contenu']; ?>
                            </div>
                            <div class="row"><div class="span12"><br></div></div>
                            <div class="offset5 span3">
                                <button name="supprime" class='btn btn-primary' type='button' >Supprimer <i class='icon-ok'></i> </button>
                            </div>
                        </div>
                        <script>
                            $(document).on("click", "button[name='supprime']", function () {
                                if (confirm("Etes-vous sur de vouloir supprimer cet enregistrement ?")){
                                    document.forms[0].submit();
                                }
                            });
                        </script>
                    </form>
                 </div>
            </div>
        </div>
    </body>
</html>