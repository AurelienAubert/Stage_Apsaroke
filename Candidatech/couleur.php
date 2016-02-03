<?php
include 'inc/verif_session.php';
include 'inc/connection.php';
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Paramètrage des couleurs</title>
<?php include 'head.php'; ?>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="parametre">Paramètrage des couleurs</div>';
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
        <div style="/*margin-top: 339px;*/" class="container-fluid ">
            <form action="inc/couleur.php" method="post" enctype="multipart/form-data" class="form-horizontal well">
                <fieldset> 
                    <legend>Couleurs :</legend>
                    <div class="row">
                        <div class="span3 ">Couleur du RAM :</div>
                        <div class="span3"><input name="COULEUR_RAM" style="width:100px;" value="<?php
                            foreach ($tab_parametre as $contenu) {
                                if ($contenu['LIBELLE'] == 'couleur_ram') {
                                    echo $contenu['CONTENU'];
                                }
                            }
                            ?>" type="color"></div>

                        <div class="span3 ">Couleur des cong&eacute;s :</div>
                        <div class="span3"><input name="COULEUR_CONGES" style="width:100px;" value="<?php
                            foreach ($tab_parametre as $contenu) {
                                if ($contenu['LIBELLE'] == 'couleur_conge') {
                                    echo $contenu['CONTENU'];
                                }
                            }
                            ?>" type="color"></div>

                        <div class="span12"><br></div>
                        <div class="span3 ">Couleur administration :</div>
                        <div class="span3"><input name="COULEUR_ADM" style="width:100px;" value="<?php
                            foreach ($tab_parametre as $contenu) {
                                if ($contenu['LIBELLE'] == 'couleur_adm') {
                                    echo $contenu['CONTENU'];
                                }
                            }
                            ?>" type="color"></div>

                        <div class="span3 ">Couleur frais :</div>
                        <div class="span3"><input name="COULEUR_FRAIS" style="width:100px;" value="<?php
                            foreach ($tab_parametre as $contenu) {
                                if ($contenu['LIBELLE'] == 'couleur_frais') {
                                    echo $contenu['CONTENU'];
                                }
                            }
                            ?>" type="color"></div>

                        <div class="span12"><br></div>
                        <div class="span3 "> Autre couleur :</div>
                        <div class="span3"><input name="COULEUR_AUTRE" style="width:100px;" value="<?php
                            foreach ($tab_parametre as $contenu) {
                                if ($contenu['LIBELLE'] == 'couleur_autre') {
                                    echo $contenu['CONTENU'];
                                }
                            }
                            ?>" type="color"></div>
                    </div>
                </fieldset>
                <br/>
                <input name="recherche" value="" type="hidden"></input>
                    <div class="row-fluid">
                        <div class="offset5 span7">
                            <button class="btn btn-primary" type="submit">Enregistrer <i class="icon-ok"></i> </button>
                        </div>
                    </div>
            </form>
        </div>
    </body>
</html>
