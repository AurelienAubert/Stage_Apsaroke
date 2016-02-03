<?php
/*
13 Juin 2014

Indice  | Nom du developpeur | Date            | Commentaire
    1   | Alexandre CREPU    | 13 Juin 2014    |   Création
    2   | Paul-andré MULCEY  | 14 Octobre 2014 | Mises à jour, contrôle de saisie

*/
require_once 'inc/verif_session.php';
require_once 'inc/connection.php';

//var_dump($_POST);

// Test si mise à jour demandée
if ($_GET['modif'] != null){
    $val = htmlspecialchars(addslashes(trim($_POST['comment'])), ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
    if ($_GET['modif'] == 0){
        $query_valid = "INSERT INTO COMMENTAIRE_COLLAB (COC_TEXT, COL_NO) VALUES ('" . $val . "', '" . $_GET['col'] . "')";
    }else{
        $query_valid = "UPDATE COMMENTAIRE_COLLAB SET COC_TEXT='" . $val . "' WHERE COL_NO=" . $_GET['col'];
    }
    $GLOBALS['connexion']->query($query_valid);
}

// Test si suppression demandée
if ($_GET['suppr'] != null){
    $query_valid = "DELETE FROM COMMENTAIRE_COLLAB WHERE COL_NO = '" . $_GET['suppr'] . "'";
    $GLOBALS['connexion']->query ($query_valid);
}

?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Ensemble des collaborateurs</title>      
        <style>
           td span
            {
                display: none;
                font-size: 10px;
            }
            td:active span
            {
                font-size: 10px;
                display: inline;
                position: absolute;
                text-decoration: none;
                background-color: #ffffff;
                border-radius: 6px;
            }
            
/*            #textarea
            {
                width: 90%;
            }*/
            .offset5
            {
                margin-left: 38%;
                
            }
        </style>
    </head>
    <body>
        <?php
            $GLOBALS['titre_page'] = '<div class="adm">Liste des collaborateurs</div>';
            $GLOBALS['retour_page'] = 'accueil.php';
            include ("menu/menu_global.php");
        ?>
        <div class="container-fluid " style="width:100%;" align="center">
            <form id="touscollab" action="tous_les_collab.php" method="post">
            <table border="1" class="table-bordered table-condensed" width="90%">
                <thead>
                    <tr class="entetecolonne">
                        <th style="width:10%;">Nom du collaborateur(Mnemonic)</th>
                        <th style="width:5%;">Etat</th>
                        <th style="width:70%;">Commentaire</th>
                        <th style="width:15%;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
<?php
            // requête récupérant tous les collaborateurs de la base de donnée ainsi que leurs commentaires s'ils en ont
            $requeteCol = "SELECT DISTINCT(CO.COL_NO), CO.COL_NOM, CO.COL_MNEMONIC, CO.COL_ETAT, CC.COC_NO, CC.COC_TEXT 
                            FROM COLLABORATEUR CO 
                            LEFT JOIN COMMENTAIRE_COLLAB CC ON CO.COL_NO = CC.COL_NO 
                            ORDER BY COL_NOM ASC";

            $tab_requete = $GLOBALS['connexion']->query($requeteCol);
            $i = 0;
            while($row_com = $tab_requete->fetch_assoc())
            {
                if($row_com['COL_ETAT'] == 1){
                    $etat = 'Actif';
                }else{
                    $etat = 'Inactif';
                }
                $commentaire = "";
                if(($row_com['COC_TEXT']) != NULL){
                    $commentaire = $row_com['COC_TEXT'];
                }
?>
            <tr id="<?php echo $row_com['COL_NO']; ?>">
                <td><?php echo $row_com['COL_NOM']; ?>(<?php echo $row_com['COL_MNEMONIC']; ?>)</td>
                <td><?php echo $etat; ?></td>
                <td><textarea name="com<?php echo $row_com['COL_NO']; ?>" id="com<?php echo $row_com['COL_NO']; ?>" class="textarea800" rows="2"><?php echo $commentaire; ?></textarea></td>
                <td>
                    <button name="modif" id="<?php echo ($row_com['COC_NO'] != null ? $row_com['COC_NO'] : 0) ?>" col="<?php echo $row_com['COL_NO']; ?>" class="btn btn-black valider" type="button">Post-it</button>
                    <button name="suppr" id="<?php echo $row_com['COL_NO']; ?>" class="btn btn-danger supprimer" type="button">X</button>
                </td>
            </tr>
<?php
            }
?>
                </tbody>
            </table>
            <script>
                $('button[name="modif"]').click(function(){
                    var id = $(this).attr('id') != '' ? $(this).attr('id') : '0';
                    var col = $(this).attr('col');
                    var com = $('#com' + col).val();
                    $('#comment').val(com);
                    $('#touscollab').attr('action', 'tous_les_collab.php?modif=' + id + '&col=' + col);
                    $("#touscollab").submit();
                });
                $('button[name="suppr"]').click(function(){
                    var id = $(this).attr('id');
                    $('#touscollab').attr('action', 'tous_les_collab.php?suppr=' + id);
                    $("#touscollab").submit();
                });
            </script>
            <input type="hidden" id="comment" name="comment" value="">
            </form>
        </div>
    </body>
</html>
