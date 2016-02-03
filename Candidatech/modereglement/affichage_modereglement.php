<?php
    include_once "inc/connection.php";
    include_once "inc/creer_input.php";

    function afficher_modereglement($recherche) {
        $query = "SELECT * FROM MODEREGLEMENT WHERE MOR_NO=" . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        $result = afficher('Code : ', $row['MOR_CODE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Libellé : ', $row['MOR_LIBELLE'], 'span3', 'span6', false)
            . sautLigne()
            ;
        return $result;
    }
?>
