<?php
include 'inc/verif_session.php';
include 'inc/connection.php';
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Paramétrage de l'e-mail</title>
        <?php include 'head.php'; ?>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="parametre">Paramètres</div>';
        include ("menu/menu_global.php");
        ?>
        <?php
        $rq_parametre = "SELECT PAR_VALEUR FROM PARAMETRE WHERE PAR_LIBELLE = 'email_destinataire';";
        $result = $GLOBALS['connexion']->query($rq_parametre);
        $row = mysqli_fetch_assoc($result);
        $email = $row['PAR_VALEUR'];
        
        if (isset($_POST['EMAIL'])) {
            $email_destinataire = $_POST['EMAIL'];
            $rq_email_destinataire = "UPDATE PARAMETRE SET PAR_VALEUR = '" . $email_destinataire . "' WHERE PAR_LIBELLE = 'email_destinataire';";
            $GLOBALS['connexion']->query($rq_email_destinataire);

            header('Location:page_enregistre.php');
        }
        ?>
        <div style="/*margin-top: 339px;*/" class="container-fluid ">
            <form action="config_email.php" method="post" enctype="multipart/form-data" class="form-horizontal well">
                <fieldset>
                    <legend>EMAIL :</legend>
                    <div class="row">
                        <div class="span3 ">Email du destinataire de contact :</div>
                        <div class="span3"><input name="EMAIL" value="<?php echo $email; ?>" type="text"></input></div>
                        <div class="span12"><br></div>
                    </div>
                </fieldset>
                <br/>
                <input name="recherche" value="" type="hidden">
                    <div class="row-fluid">
                        <div class="offset5 span7">
                            <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i> </button>
                        </div>
                    </div>
            </form>
    </body>
</html>
