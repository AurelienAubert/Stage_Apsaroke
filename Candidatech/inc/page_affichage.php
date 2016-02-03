<?php
    /**
     * contenu de la variable $page nécessaire :
     * - titre
     * - action
     * - contenu
     * - recherche
     * - message
     */
require "inc/verif_session.php";

$facExist = $GLOBALS['connexion']->query('SELECT FAC_NUM FROM FACTURE WHERE FAC_NO="'.$_POST['recherche'].'"')->fetch_assoc();

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
    $GLOBALS['titre_page'] = '<div class="ram">' . $page['titre'] . '</div>';
    $type = $_GET['type'];
    $chemin = 'window.print();';
    $GLOBALS['retour_page'] = "recherche.php?type=$type&action=affichage";

    // Affichages supplémentaires : mise en place nom du fichier
    if (strpos($type, 'collaborateur') !== false) {
        $dossier = 'collaborateur';
    }
    else {
        $dossier = $type;
    }
    $supplement = $dossier . '/aff_btn_' . $type . '.php';

    // Barre de menu
    include ("menu/menu_global.php");

// Affichage d'un message si existe, et revision du positionnement du <div> du contenu de la page
//  avec décalage du contenu vers le bas (1 seule fois la class  qui contient uun margin-top=259px).
    if (is_string($page['message']) && !empty($page['message'])) {
        echo '<div class="container-fluid " style="height: 30px; text-align:center;">';
        echo '    <font color="green">' . $page['message'] . '</font>';
        echo '</div>';
        echo '        <div class="container-fluid">';
    }else{
        echo '        <div class="container-fluid ">';
    }
?>
            <form action="<?php echo $page['action']; ?>" method="post" class="form-horizontal well">
                <?php
                    echo $page['contenu'];
                ?>
                <input type="hidden" name="recherche" value="<?php echo $page['recherche']; ?>"></input>
                <div class="row-fluid not_printed">
                    <div class="span12" style="text-align: center;">
                        <input type="submit" value="MODIFIER" class="btn btn-primary"></input>
                        <img style="width:10px;"></img>
                        <input type="button" value="IMPRIMER" onclick="<?php echo $chemin; ?>" class='btn btn-primary'></input>
                        <?php
                            // Gestion des boutons supplémentaires pour un $type.
                            @include $supplement;
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
