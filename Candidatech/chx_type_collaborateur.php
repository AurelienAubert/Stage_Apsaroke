<?php 
    /**
     * variable nécéssaire : $page, tableau contenant :
     * - titre
     * - action
     * - contenu
     * - message
     */
include 'inc/verif_session.php';
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title><?php echo 'Type de collaborateur'; ?></title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = '<div class="autre">Choix du type de collaborateur</div>';
            include ("menu/menu_global.php");
        ?>

        <div class="container-fluid ">
            <form action="redirection_par_type.php" method="post" class="form-horizontal well">
                <div class="row-fluid">
                    <div class="offset2">
                        <p>Sélectionner le type de collaborateur :</p>
                        <div class='offset3'>
                            <select name="type" style="margin-top: -30px;" required>
                                <option> </option>
                                <option value="col_interne">Collaborateur interne</option>
                                <option value="col_externe"> Collaborateur externe</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="<?php echo $_GET['action'];?>"></input>
                    <div class="row"><div class="span12"><br></div></div>
                    <div class="offset5 span3">
                        <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i> </button>
                    </div>
                </div>
                <script>
                    $(document).on("change", "select[name='type']", function () {
                        document.forms[0].submit();
                    });
                </script>
            </form>
        </div>
    </body>
</html>


