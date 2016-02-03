<?php
    require "inc/verif_session.php"; 
    include 'inc/liste_categorie.php';
    
    $page = array(
        'titre'     => 'Modifier un ',
        'message'   => '',
        'contenu'   => '',
    );
    
// Id document
    $iddoc = $_POST['iddoc'];
    
// Id de recherche (du paragraphe)
    $idrec = 0;
    if (isset($_POST['recherche'])) {
        $idrec = $_POST['recherche'];
    }else if (isset($_GET['recherche'])){
        $idrec = $_GET['recherche'];
    }
    
// Url retour :
    $retour = "rechercheLibDoc.php";
    if ($idrec > 0) {
        $retour = "rechercheLibDoc.php?iddoc=" . $iddoc;
        $page['recherche'] = htmlspecialchars (addslashes (trim (strtoupper ($idrec))));
        $type = 'libdocument';
        $dossier = 'libdocument';
        include $dossier . '/ajout_formulaire_libdocument.php';
        include $dossier .'/modif_libdocument.php';

        if (count($_POST) > 2) {
            $page['message'] = call_user_func('update_libdocument', $page['recherche']);
        }
        else {
            call_user_func('recuperer_libdocument', $page['recherche']);
        }
        $page['titre'] .= 'libellé document';
        $page['contenu'] = afficherFormulaire(true);

        echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title><?php echo $page['titre']; ?></title>

<!--        <script src="ckeditor/ckeditor.js"></script>-->
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
            $GLOBALS['retour_page'] = $retour;
            include ("menu/menu_global.php");

            // urlRetourMAJ : recherchelibdoc
//            $urlRetour = str_replace('modifier' . $type, 'recherche?', $_SERVER['REQUEST_URI']);
        ?>

        <div class="container-fluid ">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="form-horizontal well" <?php if($type == 'client') echo ' enctype="multipart/form-data"' ?>>
                <?php
                    echo $page['contenu'];
                ?>
                <input type="hidden" name="recherche" value="<?php echo $page['recherche']; ?>"></input>
                <input type="hidden" name="iddoc" value="<?php echo $iddoc; ?>"></input>
                <input type="hidden" name="urlRetourMAJ" value="<?php echo $retour; ?>"></input>
                <div class="row-fluid">
                    <div class="offset5 span7">
                        <button class='btn btn-primary' type='submit'>Valider <i class='icon-ok'></i> </button>
                    </div>
                </div>
                <input type="hidden" name="urlRetourMAJ" value="<?php echo $_SERVER['REQUEST_URI']; ?>"></input>
            </form>
        </div>                                                                            
        <?php
            include_once "inc/regex.php";
            include_once "inc/regex_javascript.php";
        ?> 
<!--        <script>
            CKEDITOR.replace( 'CONTENU' );
        </script>-->
    </body>
</html>
<?php
    }
    else {
        $page['message']='Aucune recherche demandée';
        include 'inc/page_modification.php';
    }
?>
