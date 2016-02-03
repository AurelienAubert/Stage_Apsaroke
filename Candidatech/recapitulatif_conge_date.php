<?php session_start (); ?>
<?php include 'calendrier/fonction_mois.php';
      include 'inc/connection.php'; ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php include 'head.php'; ?>
        <title>Gestion TR</title>
    </head>
    
    <body>
        <!-- Barre de menu-->
        <?php include ("menu/menu_global.php"); ?>
        <!-- Affiche Bonjour Prénom Nom de la personne en loguée + date du jour-->
        <?php include ('inc/session.php'); ?>
        
        <div class="container">
            <div class="row">
            </div>
            <div class="row">
                <div class="offset3 span6 ">
                    <form action='remplissage_conge_date.php' method="post" enctype="multipart/form-data" class="well" id="form2">
                                    <fieldset> 
                                        
                                        <legend><?php echo 'R&eacute;capitulatif des cong&eacute;s'; ?></legend> <br /><br />
                                        <div class="row">
                                            <div class="span2">
                                                <label for="annee_conges" > Ann&eacute;e :</label>
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
                                            <div class='offset2 span8 '>
                                                <button class="btn btn-primary" type="submit">Continuer <i class="icon-ok"></i> </button>
                                                <button class='btn btn-primary' type='button' onClick="javascript:window.location.replace('accueil.php');"> Quitter<i class='icon-remove'></i> </button>
                                            </div>
                                    </fieldset> 
                                </form>
                            </div>
                        </div>
                   </div>
    </body>
</html>

