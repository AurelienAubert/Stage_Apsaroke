<?php
include 'inc/verif_session.php';
include 'inc/connection.php';
include 'calendrier/fonction_nomMois.php';
include 'calendrier/fonction_mois.php';
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Paramétrage du mois de la prime ancienneté</title>
        <?php include 'head.php'; ?>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="parametre">Paramètres</div>';
        include ("menu/menu_global.php");
        ?>
        <?php
        $rq_parametre = "SELECT PAR_VALEUR FROM PARAMETRE WHERE PAR_LIBELLE = 'mois_prime';";
        $res_parametre = $GLOBALS['connexion']->query($rq_parametre);
        $row = mysqli_fetch_assoc($res_parametre);
        $moisprime = $row['PAR_VALEUR'];

        if (isset($_POST['mois'])) {
            $moisprimeenre = $_POST['mois'];
            $rq_moisprime = "UPDATE PARAMETRE SET PAR_VALEUR = '" . $moisprimeenre . "' WHERE PAR_LIBELLE = 'mois_prime';";
            $GLOBALS['connexion']->query($rq_moisprime);

            header('Location:page_enregistre.php');
        }
        ?>
        <div style="/*margin-top: 339px;*/" class="container-fluid ">
            <form action="param_mois_prime.php" method="post" enctype="multipart/form-data" class="form-horizontal well">
                <fieldset>
                    <legend>MOIS DE LA PRIME ANCIENNETE :</legend>
                    <div class="row">
                        <div class="span3 ">Mois de la prime ancienneté :</div>
                        <div class="span3"><?php
                                    // Affichage de la liste déroulante du mois via la fonction mois()
                                    echo mois ();
                                    echo "
                                    <script type=text/javascript>
                                        $('#" . $moisprime . "').attr('selected', 'true');
                                    </script>";
                                    ?></div>
                        <div class="span12"><br></div>
                    </div>
                </fieldset>
                <br/>
                    <div class="row-fluid">
                        <div class="offset5 span7">
                            <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i> </button>
                        </div>
                    </div>
            </form>
    </body>
</html>
