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
        <title>Paramétrage des logo</title>
        <?php include 'head.php'; ?>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="parametre">Paramètres</div>';
        include ("menu/menu_global.php");
        ?>
        <?php
        $tab_parametre = array();

        $rq_parametre = "SELECT * FROM PARAMETRE;";
        $res_parametre = $GLOBALS['connexion']->query($rq_parametre);

        while ($ligne = mysqli_fetch_assoc($res_parametre)) {
            $tab_parametre[$ligne['PAR_NO']]['LIBELLE'] = $ligne['PAR_LIBELLE'];
            $tab_parametre[$ligne['PAR_NO']]['CONTENU'] = $ligne['PAR_VALEUR'];
        }

        ?>
        <div style="margin-top: 339px;" class="container-fluid ">
            <form action="inc/param_logo.php" method="post" enctype="multipart/form-data" class="form-horizontal well">
                <fieldset> 
                    <legend>Logos :</legend>
                    <div class="row">
                        <div class="span3 ">Logo Apsaroke :</div>
                        <div class="span3"><img src="<?php
        foreach ($tab_parametre as $contenu) {
            if ($contenu['LIBELLE'] == 'logo_apsaroke') {
                echo $contenu['CONTENU'];
            }
        }
        ?>" alt="logo apsaroke" width="200px" height="100px"></img></div>
                        <div class="span3"><input name="LOGO_APSAROKE" type="file"></div>

                        <div class="span12"><br></div>
                        <div class="span3 ">Logo Chiricahuas :</div>
                        <div class="span3"><img src="<?php
                            foreach ($tab_parametre as $contenu) {
                                if ($contenu['LIBELLE'] == 'logo_chiricahuas') {
                                    echo $contenu['CONTENU'];
                                }
                            }
        ?>" alt="logo chiricahuas" width="200px" height="100px"></img></div>
                        <div class="span3"><input name="LOGO_CHIRICAHUAS" type="file"></div>
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

