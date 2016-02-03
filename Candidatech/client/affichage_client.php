<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_client($recherche) {
        $query = "SELECT * FROM CLIENT WHERE  CLI_NO='" . $recherche . "'";

        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        $contenu = null;
        $image = null;
        if(isset($row['CLI_LOGO']))
        {
            $contenu = $row['CLI_LOGO'];
            $image = true;
        }
        else 
        {
            $contenu = 'Ce client n\'a pas de logo';
            $image = false;
        }
        
        return creerFieldset('Identité :',
            afficher('Code client :', $row['CLI_CODE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Début de collaboration :', format_date($row['CLI_DTCREATION']), 'span3', 'span3', false)
            . afficher('Etat du client :', $row['CLI_ARCHIVE'] == 1 ? 'Archivé' : 'Actif', 'span3', 'span3')
            . sautLigne()
            . afficher('', '<b>Informations commerciales</b>', 'span2', 'span4', false)
            . afficher('', '<b>Informations facturation</b>', 'span2', 'span4', false)
            . sautLigne()
            . afficher('Nom  :', $row['CLI_NOM'], 'span3', 'span3', false)
            . afficher('Nom de facturation :', $row['CLI_NOMFAC'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('1ère adresse :', $row['CLI_ADRCOM_1'], 'span3', 'span3', false)
            . afficher('1ère adresse :', $row['CLI_ADRFAC_1'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('2ème adresse :', $row['CLI_ADRCOM_2'], 'span3', 'span3', false)
            . afficher('2ème adresse :', $row['CLI_ADRFAC_2'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Code postal :', $row['CLI_CPCOM'], 'span3', 'span3', false)
            . afficher('Code postal :', $row['CLI_CPFAC'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Ville :', $row['CLI_VILLECOM'], 'span3', 'span3', false)
            . afficher('Ville :', $row['CLI_VILLEFAC'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Pays :', $row['CLI_PAYS'], 'span3', 'span3', false)
            . afficher('Code fournisseur :', $row['CLI_CODE_FOUR'], 'span3', 'span3', false)
            . sautLigne()
            . afficher_image('Logo du client :', $contenu, 'span3', 'span3', $image)
        );
        
    }
?>
