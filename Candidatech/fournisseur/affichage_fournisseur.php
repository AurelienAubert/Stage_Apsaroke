<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_fournisseur($recherche) {
        $query = "SELECT * FROM FOURNISSEUR WHERE FOU_NO=" . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        return afficher('Code fournisseur :', $row['FOU_CODE'], 'span3', 'span3')
            . afficher('Nom du fournisseur :', $row['FOU_NOM'], 'span3', 'span3')
            . sautLigne()
            . afficher('Date de début de collaboration :', format_date($row['FOU_DTCREATION']), 'span3', 'span3')
            . afficher('Etat du fournisseur :', $row['FOU_ARCHIVE'] == 1 ? 'Archivé' : 'Actif', 'span3', 'span3')
        ;
    }
?>
