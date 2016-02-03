<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_contact_client($recherche) {
        $query = "SELECT * FROM CONTACT_CLIENT CTC LEFT JOIN FONCTION FC ON CTC.FCT_NO = FC.FCT_NO, CLIENT C WHERE CTC.CLI_NO = C.CLI_NO AND CTC_NO=" . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        return afficher('Nom du client :', $row['CLI_NOM'], 'span3', 'span3')
            . afficher('Nom du contact :', $row['CTC_NOM'], 'span3', 'span3')
            . sautLigne()
            . afficher('Prénom du contact :', $row['CTC_PRENOM'], 'span3', 'span3')
            . afficher('E-mail du contact :', $row['CTC_EMAIL'], 'span3', 'span3')
            . sautLigne()
            . afficher('No de téléphone portable :', $row['CTC_PRT'], 'span3', 'span3')
            . afficher('Fonction du contact :', $row['FCT_NOM'], 'span3', 'span3')
            . sautLigne()
            . afficher('Etat du contact :', $row['CTC_ARCHIVE'] == 1 ? 'Archivé' : 'Actif', 'span3', 'span3')
            . sautLigne()
            . afficher('Commentaire :', $row['CTC_COMMENTAIRE'], 'span3', 'span3')
        ;
    }
?>
