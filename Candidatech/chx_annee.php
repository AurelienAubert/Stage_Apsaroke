<?php require "inc/verif_session.php";
    include 'calendrier/fonction_mois.php';
    echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
    
    if($_GET['type'] == 'collaborateur_interne')
    {
        include 'inc/connection.php';
        $collaborateur = $GLOBALS['connexion']->query('SELECT COL_NOM, COL_PRENOM FROM COLLABORATEUR WHERE COL_NO='.$_POST['recherche'])->fetch_assoc();
    }
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Choisir une date</title>
        <?php include "head.php"; ?>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $mode = '';
        switch ($_GET['type']) {
            case 'tab_conges_coll':
                $cible = 'tab_conges_coll.php';
                $legende = 'Liste des congés pour';
                $titre = '<span class="conges">Congés</span>';
                break;
            case 'graph_col':
                $cible = 'graph_col.php';
                $legende = 'Graphique des collaborateurs pour';
                $titre = '<div>Graph</div>';
                break;
            case 'graph_ca':
                $cible = 'graph_ca.php';
                $legende = 'Graphique du CA pour';
                $titre = '<div>Graph</div>';
                break;
            default:
                break;
        }
        $GLOBALS['titre_page'] = $titre;
        include ("menu/menu_global.php");
        ?>

        <div class="container ">
            <div class="row">
                <div class="offset3 span6 ">
                    <form action="<?php echo $cible; ?>" method="post" enctype="multipart/form-data" class="well" id="form2">
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>
                        <fieldset> 
                            <legend><?php echo $legende; ?></legend> <br /><br />
                            <div class="row">
                                <div class="span2">
                                    <label for="annee_conges" > Année :</label>
                                </div>
                                <div class="span2">
                                    <?php
                                    // Variable qui ajoutera l'attribut selected de la liste déroulante
                                    $selected = '';
                                    // Parcours du tableau
                                    echo '<select name="annee">', "\n";
                                    for ($i = 2013; $i <= 2030; $i++) {
                                        // L'année est-elle l'année courante ?
                                        if ($i == date('Y')) {
                                            $selected = ' selected="selected"';
                                        }
                                        // Affichage de la ligne
                                        echo "\t", '<option value="', $i, '"', $selected, '>', $i, '</option>', "\n";
                                        // Remise à zéro de $selected
                                        $selected = '';
                                    }
                                    echo '</select>', "\n";
                                    ?>
                                </div>
                            </div>
                            <div class='offset2 span8 '>
                                <button class="btn btn-primary" type="submit">Continuer <i class="icon-ok"></i> </button>
                            </div>
                        </fieldset> 
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
