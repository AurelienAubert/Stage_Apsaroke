<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
        <?php include 'head.php'; ?>
        <title> Nouveau client<?php echo $page['titre']; ?></title>
</head>
<body>
    <?php
        if (is_string($page['message']) && !empty($page['message'])) {
            echo '<script type="text/javascript">alert("' . $page['message'] . '");</script>';
        }
    ?>
    <!-- Barre de menu-->
    <?php
    $GLOBALS['titre_page'] = '<div class="">' . $page['titre'] . '</div>';
    include ("menu/menu_global.php"); ?>
    
    <div class="container-fluid ">
        <form action="remplissage_client.php" method="post" class="form-horizontal well" enctype="multipart/form-data">
            <fieldset> 
            <legend>Nouveau client</legend>
            <div class="row">
                <div class="span2 ">Code client* :</div>
                <div class="span2"><input name="CODE" required="required" type="text"></input></div>

                <div class="span2 offset2">Début de collaboration* :</div>
                <div class="span2"><input name="DTCREATION" required="required" placeholder="AAAA-MM-JJ" type="date"></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2"></div><div class="span3"><b>Informations commerciales</b></div>
                <div class="span1 offset2"></div><div class="span3"><b>Informations facturation</b></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2">Nom du client* :</div>
                <div class="span2"><input name="NOM" required="required" type="text"></div>

                <div class="span2 offset2">Nom de facturation :</div>
                <div class="span2"><input name="NOMFAC" type="text"></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2 ">1ère Adresse* :</div>
                <div class="span2"><input name="ADRCOM_1" required="required" type="text"></input></div>

                <div class="span2 offset2">1ère Adresse :</div>
                <div class="span2"><input name="ADRFAC_1" type="text"></input></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2 ">2ème Adresse :</div>
                <div class="span2"><input name="ADRCOM_2" type="text"></input></div>

                <div class="span2 offset2">2ème Adresse :</div>
                <div class="span2"><input name="ADRFAC_2" type="text"></input></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2 ">Code postal* :</div>
                <div class="span2"><input name="CPCOM" required="required" maxlength="5" type="text"></input></div>

                <div class="span2 offset2">Code postal :</div>
                <div class="span2"><input name="CPFAC" maxlength="5" type="text"></input></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2 ">Ville* :</div>
                <div class="span2"><input name="VILLECOM" required="required" type="text"></input></div>

                <div class="span2 offset2">Ville :</div>
                <div class="span2"><input name="VILLEFAC" type="text"></input></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2 ">Pays :</div>
                <div class="span2"><input name="PAYS" type="text"></input></div>

                <div class="span2 offset2">Code fournisseur :</div>
                <div class="span2"><input name="CODE_FOUR" type="text"></input></div>

                <div class="row"><div class="span12"><br></div></div>
                <div class="span2 ">Logo du client :</div>
                <div class="span2"><input class="btn" name="LOGO" type="file"></input></div>

            </div>
            </fieldset>
            <div class="row-fluid">
                <div class="offset5 span7">
                    <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i></button>
                </div>
            </div>
        </form>
    </div>
    <?php
        include_once "inc/regex.php";
        include_once "inc/regex_javascript.php";
    ?>
</body>
</html>

