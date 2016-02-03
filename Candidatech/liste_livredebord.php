<?php
/*
26 Septembre 2014

Indice  | Nom du developpeur | Date              | Commentaire
    1   | Paul-André MULCEY  | 26 Septembre 2014 | Conception

*/
require 'inc/verif_session.php';
require_once 'inc/connection.php';

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 

// Recherche des fichiers du répertoire livredebord
$dossier = opendir('./livredebord');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Liste des documents du livre de bord</title>      
    </head>
    <body>
        <?php
            $GLOBALS['titre_page'] = '<div class="adm">Documents du livre de bord</div>';
            $GLOBALS['retour_page'] = 'accueil.php';
            include ("menu/menu_global.php");
            
        ?>
        
        <div class="container-fluid " style="margin-left:150px; margin-top: 30px; width:500px;">
            <form action="liste_livredebord.php" method="post">
                <table border="1" style="border-style: solid; border-width: 1px; border:#999999;" width="100%">
                    <thead>
                        <tr class="entetecolonne">
                            <th style="width:100%; text-align: left; ">Nom du document</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while(false !== ($fichier = readdir($dossier))) {
                        if($fichier != '.' && $fichier != '..' && !is_dir('./livredebord/' . $fichier)) {
                            $fichier = trim($fichier);
                            echo '<tr><td style="width:100%; text-align: left; "><a href="javascript:ouvre(\'' . $fichier . '\');">' . $fichier . '</a></td></tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </form>
            <?php
            ?>
        </div>
        <script>
            function ouvre(fic){
                window.open('livredebord/' + fic, '','');
            }
        </script>
    </body>
</html>