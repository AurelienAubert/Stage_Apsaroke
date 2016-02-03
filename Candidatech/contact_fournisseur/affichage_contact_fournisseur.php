<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_contact_fournisseur($recherche) {
        $query = "SELECT * FROM CONTACT_FOURNISSEUR CTF LEFT JOIN FONCTION FC ON CTF.FCT_NO = FC.FCT_NO, FOURNISSEUR F WHERE CTF.FOU_NO = F.FOU_NO AND CTF_NO = " . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        return afficher('Nom du fournisseur :', $row['FOU_NOM'], 'span3', 'span3')
            . afficher('Nom du contact :', $row['CTF_NOM'], 'span3', 'span3')
            . sautLigne()
            . afficher('Prénom du contact :', $row['CTF_PRENOM'], 'span3', 'span3')
            . afficher('E-mail du contact :', $row['CTF_EMAIL'], 'span3', 'span3')
            . sautLigne()
            . afficher('No de téléphone portable :', $row['CTF_PRT'], 'span3', 'span3')
            . afficher('Fonction du contact :', $row['FCT_NOM'], 'span3', 'span3')
            . sautLigne()
            . afficher('Etat du contact :', $row['CTF_ARCHIVE'] == 1 ? 'Archivé' : 'Actif', 'span3', 'span3')
            . sautLigne()
            . afficher('Commentaire :', $row['CTF_COMMENTAIRE'], 'span3', 'span3')
        ;
    }
?>
