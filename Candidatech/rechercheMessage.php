<?php 
include 'inc/verif_session.php';
include_once 'inc/connection.php';
include 'inc/suppression_donnees.php';
include_once "inc/creer_input.php";

// Test si suppression demandée
if ($_GET['suppOK']){
    $where = 'MES_NO=' . $_GET['suppOK'];
    supprimer($where, array('MESSAGE'));
}

// Test si création demandée
if ($_GET['creer']){
    $val = htmlspecialchars(addslashes(trim($_POST['NOTE'])), ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
    $q1 = "INSERT INTO MESSAGE (MES_DATE, MES_NOTE) VALUES ('" . $_POST['DATE'] . "','" . $val . "')";
    $r1 = $GLOBALS['connexion']->query($q1);
}

// Test si modification demandée
if ($_GET['modif']){
    $val = htmlspecialchars(addslashes(trim($_POST['NOTE'])), ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
    $q1 = "UPDATE MESSAGE SET MES_DATE ='" . $_POST['DATE'] . "' , MES_NOTE='" . $val . "'";
    $r1 = $GLOBALS['connexion']->query($q1);
}

// recherche si message existant
$query = "SELECT * FROM MESSAGE";
$result = $GLOBALS['connexion']->query($query)->fetch_assoc();
$titre = 'Création du message accueil';
$iddoc = 0;
$_POST['DATE'] = date('Y-m-d');
$_POST['NOTE'] = '';
if (count($result) > 0){
    $iddoc = $result['MES_NO'];
    $_POST['DATE'] = $result['MES_DATE'];
    $_POST['NOTE'] = $result['MES_NOTE'];
    $titre = 'Modification du message accueil';
}
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Gestion du message accueil</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = '<div class="autre">' . $titre . '</div>';
            include ("menu/menu_global.php");
        ?>

        <div class="container-fluid ">
            <form id="message" action="rechercheMessage.php" method="post" class="form-horizontal well">
        <?php
                $contenu = creerFieldset($titre, array(
                    input('Date* :', 'DATE', 2, 3, true, 'date'),
                    sautLigne(),
                    textarea('Message* :', 'NOTE', 2, 8, true, 8, 80, 'textarea800'),
                    sautLigne(),
                ));    
                echo $contenu;
        ?>
                <div class="row-fluid">
                    <div class="offset3 span5">
        <?php
        if ($iddoc == 0){
        ?>
                        <button class="btn btn-primary" name='creer' id='creer' type="button">Créer<i class="icon-ok"></i></button>
        <?php
        }else{
        ?>
                        <button class="btn btn-primary" name='modif' id='<?php echo $iddoc; ?>' type='button'>Modifier<i class="icon-ok"></i></button> 
                        <button class="btn btn-primary" name='suppr' id='<?php echo $iddoc; ?>' type='button'>Supprimer<i class="icon-ok"></i></button>
        <?php
        }
        ?>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>

<script>
$(document).ready(function(){
    $(document).on("click", "button[name='modif']", function () {
        var id = $(this).attr('id');
        $("#message").attr('action', 'rechercheMessage.php?modif=' + id);
        $("#message").submit();
    });

    $(document).on("click", "button[name='suppr']", function () {
        var id = $(this).attr('id');
        if (confirm('Veuillez confirmer la suppression de ce message')){
            $("#message").attr('action', 'rechercheMessage.php?suppOK=' + id);
            $("#message").submit();
        }
    });
    $(document).on("click", "button[name='creer']", function () {
        $("#message").attr('action', 'rechercheMessage.php?creer=1');
        $("#message").submit();
    });
});
</script>
