<?php session_start (); ?>
<?php
    include ("inc/verif_login_password.php");
    $connect = connexion();
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Accueil</title>
        <?php include 'head.php' ?>
    </head>

    <body>
        <?php
            if (!empty($connect)) {
                echo $connect;
            }
        ?>
        <!-- Barre de menu-->
        <?php include ("menu/menu_accueil.php"); ?>   
        <div class="container ">
            <div class="row">
                <div class="span12"><br /></div>
            </div>
            <div class="row">
                <div class="offset3 span6">
                    <form action="index.php" method="post" enctype="multipart/form-data" class="well" id="formulaire">
                        <fieldset> 
                            <legend>Saisissez vos identifiants</legend> <br /><br />
                            <div class="row">
                                <div class="span2">
                                     <label for="ident" > Identifiant</label>
                                </div>
                                <div class="span2">
                                    <input id="ident" name="login" type="text" class="input-large" size="80px" required><br /><br /><br />
                                </div>
                            </div>
                            <div class='row'>
                                <div class="span2">
                                    <label for="pass" > Mot de passe</label> 
                                </div>
                                <div class="span2">
                                    <input id="pass" type="password" name="mdp" class="input-large" size="80px" required><br /><br /><br />                                                              
                                </div>
                                <div class='offset2 span2 '>
                                    <button class="btn btn-primary" type="submit">Se connecter <i class="icon-user"></i> </button><br /><br />
                                </div>
                        </fieldset> 
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

