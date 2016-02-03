<?php
    include_once "inc/connection.php";
    include_once "inc/creer_input.php";

    function afficher_entreprise($recherche) {
        $query = "SELECT * FROM ENTREPRISE WHERE ENT_NO=" . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        $result = afficher('Nom de l\'entreprise : ', $row['ENT_NOM'], 'span3', 'span3', false)
            . afficher('Statut : ', $row['ENT_STATUT'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Adresse : ', $row['ENT_ADRESSE'], 'span3', 'span3', false)
            . afficher('Complément d\'adresse : ', $row['ENT_ADRESSE_2'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Code postal : ', $row['ENT_CP'], 'span3', 'span3', false)
            . afficher('Numéro de téléphone : ', $row['ENT_TEL'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Ville : ', $row['ENT_VILLE'], 'span3', 'span3', false)
            . afficher('Capital : ', $row['ENT_CAPITAL'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Numéro de TVA intracommunautaire : ', $row['ENT_TVA_INTRA'], 'span3', 'span3', false)
            . afficher('Numéro du RCS : ', $row['ENT_RCS'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Numéro SIRET : ', $row['ENT_SIRET'], 'span3', 'span3', false)
            . afficher('Numéro APE : ', $row['ENT_APE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Adresse web : ', $row['ENT_SITE_WEB'], 'span3', 'span3', false)
            . sautLigne()
            . afficher_image('Logo entête : ', $row['ENT_LOGO'], 'span3', 'span3', true)
            . afficher_image('Logo pied : ', $row['ENT_LOGOPIED'], 'span3', 'span3', true)
            ;
        return $result;
    }
?>