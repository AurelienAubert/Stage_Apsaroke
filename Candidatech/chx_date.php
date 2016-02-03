<?php require "inc/verif_session.php"; ?>
<?php include 'calendrier/fonction_mois.php'; ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

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
            case 'edit_ram':
                $cible = 'ram_chx_client.php';
                $legende = 'Remplir un RAM pour';
                $titre = '<span class="ram">Rapport d\'activité mensuel</span>';
                break;
            case 'print_ram':
                $cible = 'tableau_ram.php';
                $mode = 'imprimer';
                $legende = 'Imprimer un RAM pour';
                $titre = '<span class="ram">Rapport d\'activité mensuel</span>';
                break;
            case 'tab_ram':
                $cible = 'tab_coll_ram.php';
                $legende = 'Liste des RAM pour';
                $titre = '<span class="ram">Rapport d\'activité mensuel</span>';
                break;
            case 'dem_conge':
                $cible = 'demande_conges.php';
                $legende = 'Demande de congés pour';
                $titre = '<span class="conges">Congés</span>';
                break;
            case 'tab_conges':
                $cible = 'tab_conges.php';
                $legende = 'Liste des congés pour';
                $titre = '<span class="conges">Congés</span>';
                break;
            case 'frais':
                $cible = 'demande_frais.php';
                $legende = 'Note de frais pour';
                $titre = '<span class="frais">Choix d\'une note de frais</span>';
                break;
            case 'csv':
                $cible = 'csv.php';
                $legende = 'Export CSV pour ';
                $titre = '<span class="accueil">Export CSV</span>';
                break;
            case 'edit_prepaie':
                $cible = 'remplissage_PrePaie.php';
                $legende = 'Remplir pré-paie pour';
                $titre = '<div class="adm">Choix d\'une pré-paie</div>';
                break;
            case 'tr':
                $cible = 'tickets_restaurant.php';
                $legende = 'Fiche tickets restaurant pour';
                $titre = '<div class="adm">Choix de la fiche des TR</div>';
                break;
            case 'visu_prepaie':
                $cible = 'visu_prepaie.php';
                $legende = 'Affichage pré-paie pour';
                $titre = '<div class="adm">Choix d\'une pré-paie</div>';
                break;
            case 'prefacturation':
                $cible = 'prefacturation.php';
                $legende = 'Affichage préfacturation pour';
                $titre = '<div class="adm">Choix d\'une préfacturation</div>';
                break;
            case 'facture':
                $cible = 'recherche.php?type=facture&action=affichage';
                $legende = 'Visualisation des factures';
                $titre = '<div class="adm">Choix d\'une facture</div>';
                break;
            case 'proforma':
                $cible = 'visu_proforma.php?type=proforma&action=affichage';
                $legende = 'Visualisation des proforma';
                $titre = '<div class="adm">Choix d\'une proforma</div>';
                break;
            case 'suivi':
                $cible = 'suivi_mission.php';
                $legende = 'Suivi des missions pour';
                $titre = '<div class="adm">Suivi des missions</div>';
                break;
            case 'frais_urb':
                $cible = 'demande_frais.php?type=frais_urb';
                $legende = 'Note de frais forfaitaire pour';
                $titre = '<div class="frais">Note de frais forfaitaire</div>';
                break;
            case 'frais_reel':
                $cible = 'demande_frais.php?type=frais_reel';
                $legende = 'Note de frais réelle pour';
                $titre = '<div class="frais">Note de frais réelle</div>';
                break;
            case 'frais_gd':
                $cible = 'demande_frais.php?type=frais_gd';
                $legende = 'Note de frais grand déplacement pour';
                $titre = '<div class="frais">Note de frais grand déplacement</div>';
                break;
            case 'frais_km':
                $cible = 'demande_frais.php?type=frais_km';
                $legende = 'Note de frais kilométrique pour';
                $titre = '<div class="frais">Note de frais kilométrique</div>';
                break;
            case 'tab_frais':
                $cible = 'tab_frais_collab.php';
                $legende = 'Tableau des notes de frais pour';
                $titre = '<div class="frais">Note de frais</div>';
                break;
            case 'ens_collab':
                $cible = 'tous_les_collab.php';
                $legende = 'Tous les collaborateurs';
                $titre = '<div class="frais">Les collaborateurs</div>';
                break;
            default:
                break;
        }
        $GLOBALS['titre_page'] = $titre;
        include ("menu/menu_global.php");
        ?>

        <div class="container ">
            <div class="row">
                <div class="offset3 span7 ">
                    <form action="<?php echo $cible; ?>" method="post" class="well" id="form2">
                        <fieldset> 
                            <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>
                            <input type="hidden" name="legend" value="<?php echo $legende; ?>"/>
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
                            <div class='row'>
                                <div class="span2">
                                    <label for="mois_conges" > Mois :</label> 
                                </div>
                                <div class="span2">
                                    <?php
                                    // Affichage de la liste déroulante du mois via la fonction mois()
                                    echo mois();
                                    echo "
                                    <script type=text/javascript>
                                        $('#" . date('n') . "').attr('selected', 'true');
                                    </script>";
                                    ?>
                                </div>
                                <div class='offset2 span8 '>
                                    <button class="btn btn-primary" type="submit">Continuer <i class="icon-ok"></i> </button>
                                </div>
                            </div>
                        </fieldset> 
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(document).on("change", "select[name='mois']", function () {
            document.forms[0].submit();
        });
    </script>
</html>
