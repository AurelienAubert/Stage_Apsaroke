<?php require ("inc/verif_session.php") ?>
<?php include 'calendrier/fonction_mois.php'; ?>
<?php include 'inc/liste.php'; ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Choisir une date et un collaborateur</title>
        <?php include "head.php"; ?>
    </head>
    <body>
        <?php
            $GLOBALS['titre_page'] = '<div class="adm">Modifier des congés</div>';
            include ("menu/menu_global.php");
        ?>
        <div class="container ">
            <div class="row">
                <div class="offset3 span6 ">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="well" id="form2">
                        <fieldset> 
                            <legend>Choisir un collaborateur et une date</legend> <br /><br />
                            <div class="row">
                                <div class="span2">
                                    <label for="collaborateur_conges" > Collaborateur :</label>
                                </div>
                                <div class="span2">
                                    <?php
                                    echo '<select name="col_id">', "\n";
                                    $collabs = donner_liste_conges('collaborateur');
                                    foreach ($collabs as $num=>$collab){
                                        echo '<option value="' . $num . '">' . $collab . '</option>';
                                    }
                                    echo '</select>';
                                    ?>
                                </div>
                            </div>
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
                                    for ($i = 2013; $i <= 2030; $i++)
                                    {
                                        // L'année est-elle l'année courante ?
                                        if ($i == date ('Y'))
                                        {
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
                                    echo mois ();
                                    echo "
                                    <script type=text/javascript>
                                        $('#" . date ('n') . "').attr('selected', 'true');
                                    </script>";
                                    ?>
                                </div>
                            </div>
                            <div class='offset2 span8 '>
                                <button id="invalider" class="btn btn-primary" type="button">Continuer <i class="icon-ok"></i> </button>
                            </div>
                        </fieldset>
                    </form>
                    <script>
                        $(document).ready(function() {
                            $('#invalider').click(function() {
                                $.ajax({
                                    url: 'inc/insertion_conges.php',
                                    type: 'POST',
                                    async: true,
                                    data: {
                                        col_id: $('[name="col_id"]').val(),
                                        annee:  $('[name="annee"]').val(),
                                        mois:   $('[name="mois"]').val(),
                                        action: 'invalider'
                                    }
                                }).success(function() {
                                    alert('Congés invalidés');
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </body>
</html>