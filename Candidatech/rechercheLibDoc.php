<?php 
include 'inc/verif_session.php';
include_once 'inc/connection.php';
include 'inc/suppression_donnees.php';

// Test si suppression demandée
if ($_GET['suppOK']){
    $where = 'LDO_NO=' . $_GET['suppOK'];
    supprimer($where, array('LIBDOCUMENT'));
}

// Test si no d'ordre PLUS
if ($_GET['plus']){
    $ord = $_GET['plus'];
    $q1 = "UPDATE LIBDOCUMENT SET LDO_ORDRE =99999 WHERE LDO_ORDRE =" . $ord;
    $r1 = $GLOBALS['connexion']->query($q1);
    $q2 = "UPDATE LIBDOCUMENT SET LDO_ORDRE =" . $ord . " WHERE LDO_ORDRE =" . ($ord + 1);
    $r2 = $GLOBALS['connexion']->query($q2);
    $q3 = "UPDATE LIBDOCUMENT SET LDO_ORDRE =" . ($ord + 1) . " WHERE LDO_ORDRE =99999";
    $r3 = $GLOBALS['connexion']->query($q3);
}

// Test si no d'ordre MOINS
if ($_GET['moins']){
    $ord = $_GET['moins'];
    $q1 = "UPDATE LIBDOCUMENT SET LDO_ORDRE =99999 WHERE LDO_ORDRE =" . $ord;
    $r1 = $GLOBALS['connexion']->query($q1);
    $q2 = "UPDATE LIBDOCUMENT SET LDO_ORDRE =" . $ord . " WHERE LDO_ORDRE =" . ($ord - 1);
    $r2 = $GLOBALS['connexion']->query($q2);
    $q3 = "UPDATE LIBDOCUMENT SET LDO_ORDRE =" . ($ord - 1) . " WHERE LDO_ORDRE =99999";
    $r3 = $GLOBALS['connexion']->query($q3);
}

// suivant les valeurs déjà saisies, on se positionne
$query = "SELECT * FROM DOCUMENT ORDER BY DOC_NOM";
$result = $GLOBALS['connexion']->query($query);
$titre = 'Gestion des libellés documents';

$iddoc = "0";
if ($_POST['iddoc'] != null){
    $iddoc = $_POST['iddoc'];
}else if ($_GET['iddoc'] != null){
    $iddoc = $_GET['iddoc'];
}
$retour = "accueil.php";
if($iddoc > 0){
    $retour = "rechercheLibDoc.php";
    $queryl = "SELECT * FROM LIBDOCUMENT WHERE DOC_NO =" . $iddoc ." ORDER BY LDO_ORDRE, COL_NO";
    $resultl = $GLOBALS['connexion']->query($queryl);
}

// Test si message
if (isset($_GET['message'])){
    if ($_GET['message'] == 'MAJOK'){
        $page['message'] = 'Vos données ont été enregistrées.';
    }else{
        $page['message'] = $_GET['message'];
    }
}

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Gestion des libell&eacute;s documents</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="autre">Gestion des documents</div>';
        $GLOBALS['retour_page'] = $retour;
        include ("menu/menu_global.php");

    // Affichage d'un message si existe, et revision du positionnement du <div> du contenu de la page
    //  avec décalage du contenu vers le bas (1 seule fois la class  qui contient uun margin-top=259px).
        if (is_string($page['message']) && !empty($page['message'])) {
            echo '<div class="container-fluid " style="height: 30px; text-align:center;">';
            echo '    <font color="green">' . $page['message'] . '</font>';
            echo '</div>';
            echo '        <div class="container-fluid">';
        }else{
            echo '        <div class="container-fluid ">';
        }
        ?>
<!--        <div class="container-fluid ">-->
            <form id="documents" action="rechercheLibDoc.php" method="post" class="form-horizontal well">
                <div class="row-fluid">
                    <div class="offset2">
                        <p>S&eacute;lectionnez un document :
                        <select name="iddoc" class="offset2" required onchange="javascript:relance();">
                            <option> </option>
                        <?php
                            while ($row = $result->fetch_assoc()) {
                         ?>
                          <option value="<?php echo $row['DOC_NO'] ?>" <?php if($row['DOC_NO'] == $iddoc) echo 'selected'; ?>><?php echo $row['DOC_NOM'] ?></option>
                        <?php
                            }
                         ?>
                        </select></p>
                    </div>
                    <input type="hidden" name="recherche" value=""></input>
                </div>
<?php
if ($iddoc > 0){
?>
                <div class="row-fluid">
                    <div class="offset1">
                        <table border="1">
                            <thead>
                                <tr class="entetecolonne">
                                    <th style='width:120px;'>No d'ordre</th>
                                    <th style='width:250px;'>Nom du libell&eacute;</th>
                                    <th style='width:120px;'>Collaborateur</th>
                                    <th style='width:300px;'>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                while ($row = $resultl->fetch_assoc()) {
                            ?>
                                <tr style="background-color: #ffffff">
                                    <td><?php echo $row['LDO_ORDRE'] ?></td>
                                    <td><?php echo $row['LDO_NOM'] ?></td>
                                    <td><?php echo $row['COL_NO'] ?></td>
                                    <td>
                                        <input name='modif' id='<?php echo $row['LDO_NO']; ?>' type='button' value="MODIFIER" class='btn btn-primary'></input> 
                                        <input name='suppr' id='<?php echo $row['LDO_NO']; ?>' type='button' value="SUPPRIMER" class='btn btn-primary'></input>
                                        <input name='plus' id='<?php echo $row['LDO_NO']; ?>' ordre='<?php echo $row['LDO_ORDRE']; ?>' type="button" value="+" onclick="javascript:monter(' . $row['DOC_NO'] . ')"></input>
                                        <input name='moins' id='<?php echo $row['LDO_NO']; ?>' ordre='<?php echo $row['LDO_ORDRE']; ?>' type="button" value="-" onclick="javascript:descendre(' . $row['DOC_NO'] . ')"></input>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br/>
                <div class="row-fluid">
                    <div class="offset3 span6">
                        <button class="btn btn-primary" name='creer' id='<?php echo $iddoc; ?>' type="button">Ajouter à la fin<i class="icon-ok"></i></button>
                    </div>
                </div>
<?php
}
?>
            </form>
        </div>
    </body>
</html>

<script>
$(document).ready(function(){
    $(document).on("click", "input[name='modif']", function () {
        var id = $(this).attr('id');
        $("input[name='recherche']").val(id);
        $("#documents").attr('action', 'modifierLibDoc.php');
        $("#documents").submit();
    });

    $(document).on("click", "input[name='suppr']", function () {
        var id = $(this).attr('id');
        if (confirm('Veuillez confirmer la suppression de ce libellé')){
            $("#documents").attr('action', 'rechercheLibDoc.php?suppOK=' + id);
            $("#documents").submit();
        }
    });
    $(document).on("click", "input[name='plus']", function () {
        var ord = $(this).attr('ordre');
        $("#documents").attr('action', 'rechercheLibDoc.php?plus=' + ord);
        $("#documents").submit();
    });
    $(document).on("click", "input[name='moins']", function () {
        var ord = $(this).attr('ordre');
        $("#documents").attr('action', 'rechercheLibDoc.php?moins=' + ord);
        $("#documents").submit();
    });

    $(document).on("click", "button[name='creer']", function () {
        var id = $(this).attr('id');
        $("input[name='recherche']").val(id);
        $("#documents").attr('action', 'ajoutLibDoc.php');
        $("#documents").submit();
    });
});
function relance(){
    document.forms[0].submit();
}
</script>
