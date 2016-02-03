<?php require ("inc/verif_session.php") ?>
<?php include ("inc/connection.php"); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Modifier un mot de passe</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<span class="">Modification du mot de passe';
            include ("menu/menu_global.php");
        ?>
        <div class="row-fluid ">
            <div class="offset3 span5 ">
                <form action="validation_modif_pwd.php" method="post" enctype="multipart/form-data" class="well" id="form2">
                    <fieldset>
                        <legend>Insérez l'ancien mot de passe, entrez le nouveau mot de passe et confirmez le</legend>
                        <div class="row-fluid">
                            <div class="span4 ">
                                <label for='ancien_pwd'>Ancien mot de passe :</label>
                            </div>
                            <div class="span3">
                                <input type='password' name='ancien_pwd' required></input>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span4 ">
                                <label for='pwd'>Nouveau mot de passe :</label>
                            </div>
                            <div class="span3">
                                <input type='password' name='pwd' required></input>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span4 ">
                                <label for='pwd_confirm'>Confirmer le nouveau mot de passe:</label>
                            </div>
                            <div class="span3">
                                <input type='password' name='pwd_confirm' required></input>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span4 "></div>
                            <div class="span6 ">
                                <button class='btn btn-primary' type='submit'>Valider <i class='icon-ok'></i> </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>    
        </div>
    </body>
</html>