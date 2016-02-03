<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_fonction($recherche) {
        $query = "SELECT * FROM FONCTION WHERE FCT_NO=" . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        return afficher('Libellé :', $row['FCT_NOM'], 'span3', 'span3')
            . sautLigne()
        ;
    }
?>
