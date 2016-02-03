<?php
    include "inc/verif_session.php";
    include "inc/connection.php"; 
    include 'inc/liste_categorie.php';
    
    $page = array(
        'titre'     => 'Nouveau ',
        'message'   => '',
        'contenu'   => '',
    );

    if (isset($_POST['recherche'])) {
        $page['recherche'] = $_POST['recherche'];
        $type = 'libdocument';
        $dossier = 'libdocument';
        include $dossier . '/ajout_formulaire_libdocument.php';
        include $dossier.'/ajout_libdocument.php';
        
        if (count($_POST) > 2) {
            $page['message'] = call_user_func('ajout_libdocument');
        }
        else {
            call_user_func('recuperer_libdocument', $page['recherche']);
        }
        $page['titre'] .= 'libellé document';
        $page['contenu'] = afficherFormulaire();
        
        echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
?>
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
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="form-horizontal well">
                <?php
                    echo $page['contenu'];
                ?>
                <input type="hidden" name="recherche" value="<?php echo $page['recherche']; ?>"></input>
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
    </body>
</html>
<?php
    }else{
        $page['contenu']='';
        $page['message']='Type d\'ajout manquant';
    }
?>
