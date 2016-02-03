<?php include ("inc/connection.php"); ?>
<?php include ("calendrier/fonction_annee_bissextile.php"); ?>
<?php include("calendrier/fonction_dimanche_samedi.php"); ?>
<?php include ("calendrier/fonction_nbjoursMois.php"); ?>
<?php include ("calendrier/fonction_premierJsemJanvier.php"); ?>
<?php include("calendrier/fonction_nomMois.php"); ?>
<?php include("calendrier/jours_feries.php"); ?>
<?php session_start(); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 


    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Formulaire</title>
        <meta name="viewport" content="width=device-width" />
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"></link>
        <link href="style.css" rel="stylesheet" type="text/css"></link>
        <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" type="text/css"></link>
        <script type="text/javascript" src="http://code.jquery.com/jquery.js"></script>

        <!-- icones apple -->
        <link rel="shortcut icon" href="../assets/ico/favicon.ico"></link>
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png"></link>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png"></link>
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png"></link>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="offset2 span7">                                                       
                    <h1 class='conges'>Cong&eacute;s 
                        <!-- Affichage de la date depuis la liste déroulante dans le titre-->
                        <?php
                        $annee_conges = htmlspecialchars(addslashes(trim($_POST['annee_conges'])));
                        $mois_conges = htmlspecialchars(addslashes(trim($_POST['mois_conges'])));
                        $_SESSION['annee_conges'] = $annee_conges;
                        $_SESSION['mois_conges'] = $mois_conges;

                        $nbjoursMois = nbjoursMois($mois_conges, $annee_conges);
                        $jourjanvier = premierJsemJanvier($annee_conges);
                        if (isset($_POST['mois_conges']) && !empty($_POST['mois_conges'])) {
                            echo nomMois($_POST['mois_conges']) . ' ';
                        }
                        if (isset($_POST['annee_conges']) && !empty($_POST['annee_conges'])) {
                            echo $_POST['annee_conges'] . "<br /><br />";
                        }
                        ?>							
                    </h1>
                </div> 
            </div >                                         
            <form action="conges_envoyes.php" method="post" enctype="multipart/form-data" name="monFormulaire">                                            
                <?php include("conges/tableau_conges.php"); ?>
                <div>
                    <img src='' name="signature_pdf" id="sign_pdf" width="100"/>
                </div>
                <div class="row-fluid">
                    <div class="offset4 span7"> 
                        <br /><br />
                    </div>
                </div>
                <div class="row-fluid">
                    <!-- On limite le fichier à 1000Ko -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
                    Fichier : <input type="file" name="signature"/>
                    <br/>
                    L'abscence de signature invalide une demande de congés.
                    <br/>
                    <br/>
                </div>
            </form>
        </div>
        <div id='footer'>
            <script src="js/jquery.min.js"></script>
            				
        </div>
    </body>
</html>