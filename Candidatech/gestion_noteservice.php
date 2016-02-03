<?php
/*

Indice  | Nom du developpeur | Date              | Commentaire
    1   | Paul-André MULCEY  | 13 Octobre 2014   | Conception

*/
require 'inc/verif_session.php';
//require_once 'inc/connection.php';

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 

// Test si suppression demandée
if ($_GET['suppOK']){
    $fic = $_GET['suppOK'];
    unlink('./noteservice/' . $fic);
    $res = $ftp->error;
}

// Recherche des fichiers du répertoire livredebord
$dossier = opendir('./noteservice');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Liste des notes de service</title>      
    </head>
    <body>
        <?php
            $GLOBALS['titre_page'] = '<div class="adm">Gestion des notes de service</div>';
            $GLOBALS['retour_page'] = 'accueil.php';
            include ("menu/menu_global.php");
            
        ?>
        <div class="container-fluid " style="margin-left:150px; margin-top: 30px; width:500px;">
            <form id="listeserveur" action="gestion_noteservice.php" method="post">
                <table border="1" style="border-style: solid; border-width: 1px; border:#999999;" width="100%">
                    <thead>
                        <tr class="entetecolonne">
                            <th style="width:60%; text-align: left; ">Nom du document</th>
                            <th style="width:40%; text-align: center; ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while(false !== ($fichier = readdir($dossier))) {
                        if($fichier != '.' && $fichier != '..' && !is_dir('./noteservice/' . $fichier)) {
                            $fichier = trim($fichier);
                            echo '<tr><td style="text-align: left; "><a href="javascript:ouvre(\'' . $fichier . '\');">' . $fichier . '</a></td>';
                            echo '<td style="text-align: center; "><input name="suppr" id="' . $fichier . '" type="button" value="Supprimer" class="btn btn-primary"></input></td></tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </form>
            <form action="recept.php" enctype="multipart/form-data" method="post">
                <div class="row-fluid">
                    <div class="offset3 span5">
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                        <input type="hidden" name="rep_copy" value="./noteservice/" />
                        <input type="hidden" name="urlretour" value="gestion_noteservice.php" />
                        <input type="file" name="fsource"></input>
                        <button class="btn btn-primary" name='envoi' type="submit">Valider<i class="icon-ok"></i></button>
                    </div>
                </div>
            </form>
            <?php
            ?>
        </div>

<script>
    $(document).ready(function(){
        $(document).on("click", "input[name='suppr']", function () {
            var fic = $(this).attr('id');
            if (confirm('Veuillez confirmer la suppression de ce fichier')){
                $("#listeserveur").attr('action', 'gestion_noteservice.php?suppOK=' + fic);
                $("#listeserveur").submit();
            }
        });
    });
    function ouvre(fic){
        window.open('./noteservice/' + fic, '','')
    }
</script>
    </body>
</html>