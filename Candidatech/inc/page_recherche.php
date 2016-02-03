<?php 
    /**
     * variable nécéssaire : $page, tableau contenant :
     * - titre
     * - action
     * - contenu
     * - message
     */
//echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title><?php echo $page['titre']; ?></title>
        <?php include 'head.php'; ?>
    </head>
    <body>
        <?php
//            if (is_string($page['message']) && !empty($page['message'])) {
//                echo '<script type="text/javascript">alert("' . $page['message'] . '");</script>';
//            }
        ?>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = $page['titre'];
            
            if (isset($_POST['mode']) && ($_POST['mode'] == 'voir' || $_POST['mode'] == 'modif')) {
                $GLOBALS['retour_page'] = 'tab_frais_collab.php';
            }
            if(isset($_GET['type']) && ($_GET['type'] == 'collaborateur_externe' || $_GET['type'] == 'collaborateur_interne'))
            {
                $GLOBALS['retour_page'] = 'chx_type_collaborateur.php?action=affichage';
            }
    
            include ("menu/menu_global.php");
        ?>
        <div class="container-fluid ">
            <div class="row">
            <form action="<?php echo $page['action']; ?>" method="post" class="form-horizontal well">
                <div class="row-fluid">
                    <div class="row"><div class="span12"><br></div></div>
                    <div class="offset2">
                        <?php
                        // INSERER ICI tous les $type qui possèdent un code ARCHIVAGE (au lieu des suppressions, on archive) :
                        //  il faut ensuite gérer la requête dans 'inc/liste.php', pour y inclure ou non les enregistrements archivés
                            if ($type == 'projet' || $type == 'client' || $type == 'fournisseur' || $type == 'contact_client' || $type == 'contact_fournisseur' || 
                                strpos($type, 'collaborateur') !== false){
                        ?>
                            <div class="span4">Enregistrements archivés</div>
                            <div class="span3"><input type="checkbox" name="archive" value="archive" <?php if($_POST['archive'] == 'archive') echo 'checked'; ?>></input></div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="row"><div class="span12"><br></div></div>
                    <div class="offset2">
                        <?php
                            echo $page['contenu'];
                        ?>
                    </div>
                    <div class="row"><div class="span12"><br></div></div>
                    <div class="offset5 span3">
                        <button class="btn btn-primary" type="submit" > Afficher <i class="icon-ok"></i> </button>
                    </div>
                </div>
                <script>
                    $(document).on("change", "select[name='recherche']", function () {
                        document.forms[0].submit();
                    });
                    $(document).on("change", "input[name='archive']", function () {
                        var archive = $("input[name='archive']:checked").val();
                        //alert(archive);
                        document.forms[0].action = 'recherche.php?type=<?php echo $type; ?>&action=<?php echo $_GET['action']; ?>&archive=' + archive;
                        document.forms[0].submit();
                    });
                </script>
            </form>
        </div>
        </div>
    </body>
</html>