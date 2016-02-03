<?php
    include_once "inc/connection.php";
    include_once "inc/creer_input.php";

    function afficher_banque($recherche) {
        $query = "SELECT * FROM BANQUE WHERE BAN_NO=" . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        $result = afficher('Nom de la banque : ', $row['BAN_NOM'], 'span3', 'span5', false)
            . sautLigne()
            . afficher('Code banque : ', $row['BAN_CDE_BAN'], 'span3', 'span3', false)
            . afficher('Code guichet : ', $row['BAN_CDE_GUI'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Num�ro de compte banque : ', $row['BAN_NUM_CPT'], 'span3', 'span3', false)
            . afficher('Num�ro de RIB : ', $row['BAN_RIB'], 'span3', 'span3', false)
            ;
        return $result;
    }
?>
