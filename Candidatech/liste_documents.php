<?php
/*
12 Septembre 2014

Indice  | Nom du developpeur | Date              | Commentaire
    1   | Paul-André MULCEY  | 12 Septembre 2014 | Conception

*/
require 'inc/verif_session.php';
require_once 'inc/connection.php';

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 

$query = "SELECT * FROM DOCUMENT ORDER BY DOC_NOM";
$results = $GLOBALS['connexion']->query($query);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Liste des documents générés</title>      
    </head>
    <body>
        <?php
            $GLOBALS['titre_page'] = '<div class="adm">Liste des documents générés</div>';
            $GLOBALS['retour_page'] = 'accueil.php';
            include ("menu/menu_global.php");
        ?>
        <div class="container-fluid ">
            <form action="liste.php" method="post">
                <table border="0" class="table-bordered table-condensed" style="text-align: center;" width="250px">
                    <thead>
                        <tr>
                            <th style="width:70%">Nom du document</th>
                            <th style="width:30%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($results as $row){
                            echo '<tr><td>' . $row['DOC_NOM'] . '</td><td><input type="button" value="Modifier" onclick="javascript:modifier(' . $row['DOC_NO'] . ')"></td></tr>';
                        }
                    ?>
                    </tbody>

                </table>
            </form>
            <?php
            ?>
        </div>
    </body>
</html>