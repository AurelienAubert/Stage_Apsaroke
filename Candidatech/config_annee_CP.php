<?php
include 'inc/verif_session.php';
include 'inc/connection.php';

if(isset($_POST['PAR_VALEUR']) && isset($_POST['PAR_NO']))
{
    $par_no = $_POST['PAR_NO'];
    $par_valeur = $_POST['PAR_VALEUR'];
    $update = 'UPDATE PARAMETRE SET PAR_VALEUR="'.$par_valeur.'" WHERE PAR_NO='.$par_no;
    echo $update;
    $GLOBALS['connexion']->query($update);
}
$queryPar = 'select * from PARAMETRE WHERE PAR_LIBELLE="ANNEE_DEPART_SOLDE"';
$par = $GLOBALS['connexion']->query($queryPar)->fetch_assoc();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php';?>
        <title>Solde congés payés</title>
    </head>
    <body>
        <?php
        require_once ("menu/menu_global.php");
        ?>
        <form action="config_annee_CP.php" method="post" class="well">
            <fieldset>
                <legend>Paramètrage de l'année de départ des CP</legend>
                <input type="text" name="PAR_VALEUR" value="<?php echo $par['PAR_VALEUR']?>"/>
                <input type="hidden" name="PAR_NO" value="<?php echo $par['PAR_NO']?>"/>
                <input type="submit" name="Valider"/>
            </fieldset>
        </form>
    </body>
</html>