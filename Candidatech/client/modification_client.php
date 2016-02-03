<?php 

    include 'inc/connection.php';
    
    $query = "SELECT * FROM CLIENT WHERE  CLI_NO = '".$_POST['recherche']."'";

        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();
        $code_client = $row['CLI_CODE'];
        $nom_client = $row['CLI_NOM'];
        $nom_facturation = $row['CLI_NOMFAC'];
        $dtcreation_client = $row['CLI_DTCREATION'];
        $adrcom1 = $row['CLI_ADRCOM_1'];
        $adrcom2 = $row['CLI_ADRCOM_2'];
        $cpcom = $row['CLI_CPCOM'];
        $villecom = $row['CLI_VILLECOM'];
        $adrfac1 = $row['CLI_ADRFAC_1'];
        $adrfac2 = $row['CLI_ADRFAC_2'];
        $cpfac = $row['CLI_CPFAC'];
        $villefac = $row['CLI_VILLEFAC'];
        $pays = $row['CLI_PAYS'];
        $codefourn = $row['CLI_CODE_FOUR'];
        $archive = $row['CLI_ARCHIVE'];
        $logo = null;
        $image = null;
        if(!empty($row['CLI_LOGO']) && !($row['CLI_LOGO']=='client/images_clients/'))
        {
            $logo = $row['CLI_LOGO'];
            $image = true;
        }
        else 
        {
            $logo = 'Ce client n\'a pas de logo';
            $image = false;
        }
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
        <?php include 'head.php'; ?>
        <title><?php echo $page['titre']; ?></title>
</head>
<body>
    <?php
        if (is_string($page['message']) && !empty($page['message'])) {
            echo '<script type="text/javascript">alert("' . $page['message'] . '");</script>';
        }
    ?>
    <!-- Barre de menu-->
    <?php
    $GLOBALS['titre_page'] = '<div class="ram">' . $page['titre'] . '</div>';
    include ("menu/menu_global.php"); ?>

    <div class="container-fluid ">
<fieldset>
    <form id="saicli" action="remplissage_modif_client.php" method="post" class="form-horizontal well" enctype="multipart/form-data">
 
        <legend>Modifiez le client choisi puis validez</legend>
        <div class="row">
            <div class="span3 ">Code client* :</div>
            <div class="span3"><input  name="CODE" required="required" value="<?php echo $code_client;?>" type="text"></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3">Début de collaboration* :</div>
            <div class="span3"><input name="DTCREATION" required="required" placeholder="AAAA-MM-JJ" value="<?php echo $dtcreation_client;?>" type="date"></div>

            <div class="span3">Etat du client :</div>
            <div class="span1">
                <input type="radio" name="ARCHIVE" value="0" <?php if ($archive == 0) echo "checked";?>>Actif</input>
            </div>
            <div class="span1">
                <input type="radio" name="ARCHIVE" value="1" <?php if ($archive == 1) echo "checked";?>>Archivé</input>
            </div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3 offset2"><b>Informations commerciales</b></div>
            <div class="span3 offset2"><b>Informations facturation</b></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3">Nom du client* :</div>
            <div class="span3"><input name="NOM" required="required" value="<?php echo $nom_client;?>" type="text"></div>

            <div class="span3">Nom de facturation :</div>
            <div class="span3"><input name="NOMFAC" value="<?php echo $nom_facturation;?>" type="text"></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3">1ère Adresse* :</div>
            <div class="span3"><input name="ADRCOM_1" required="required" value="<?php echo $adrcom1;?>" type="text"></input></div>

            <div class="span3">1ère Adresse :</div>
            <div class="span3"><input name="ADRFAC_1" value="<?php echo $adrfac1;?>" type="text"></input></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3">2ème Adresse :</div>
            <div class="span3"><input name="ADRCOM_2" value="<?php echo $adrcom2;?>" type="text"></input></div>

            <div class="span3">2ème Adresse :</div>
            <div class="span3"><input name="ADRFAC_2" value="<?php echo $adrfac2;?>" type="text"></input></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3">Code postal* :</div>
            <div class="span3"><input name="CPCOM" required="required" maxlength="5" value="<?php echo $cpcom;?>" type="text"></input></div>

            <div class="span3">Code postal :</div>
            <div class="span3"><input name="CPFAC" value="<?php echo $cpfac;?>" maxlength="5" type="text"></input></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3">Ville* :</div>
            <div class="span3"><input name="VILLECOM" required="required" value="<?php echo $villecom;?>" type="text"></input></div>

            <div class="span3">Ville :</div>
            <div class="span3"><input name="VILLEFAC" value="<?php echo $villefac;?>" type="text"></input></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3">Pays :</div>
            <div class="span3"><input name="PAYS" value="<?php echo $pays;?>" type="text"></input></div>

            <div class="span3">Code fournisseur :</div>
            <div class="span3"><input name="CODE_FOUR" value="<?php echo $codefourn;?>" type="text"></input></div>

            <div class="row"><div class="span12"><br></div></div>
            <div class="span3 ">Logo du client :</div>
            <div id="divlogo" class="span3">
            <?php
            if($image == true)
            {
                echo '<img name="IMAGELOGO" src="'.$logo.'" width="200px" height="100px"></img>';
            }
            else 
            {
               echo $logo;
            }
            ?>
            </div>
            <div class="row"><div class="span12"><br></div></div>
            <div class="span3"><input name="LOGO" type="file"></div><div class="span2"></div>
            <div class="span3">
                <button name="supprime" class="btn btn-primary" type="button">Supprimer l'image <i class="icon-ok"></i> </button>
            </div>
            <br/>
            </div>
            <input name="recherche" value="" type="hidden"></input>
<!--            <input name="image" value="<?php echo $image; ?>" type="hidden"></input>-->
            <div class="row-fluid">
                <div class="offset5 span7">
                    <button class="btn btn-primary" style="margin-bottom:-6.2em;" type="submit">Valider <i class="icon-ok"></i> </button>
                </div>
            </div>
            <input type="hidden" name="recherche" value="<?php echo $_POST['recherche']; ?>"></input>
            <input type="hidden" name="logo_client" value="<?php echo $logo; ?>"></input>
        <script>
            $(document).on("click", "button[name='supprime']", function () {
                $("input[name='LOGO']").val('');
//                $("input[name='logo_client']").val('');
                $("#divlogo").html('Ce client n\'a pas de logo');
            });
        </script>
    </form>
</fieldset>
</div>
<?php
    include ('inc/regex.php');
    include ('inc/regex_javascript.php');
?>
</body>
</html>