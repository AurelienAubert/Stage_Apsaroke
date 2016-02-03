<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_collaborateur_externe($recherche) {
        //TODO:remettre correctement les liens...
        $query = "SELECT * FROM COLLABORATEUR C, EXTERNE E LEFT JOIN FOURNISSEUR F ON E.FOU_NO = F.FOU_NO WHERE E.COL_NO = C.COL_NO AND E.COL_NO = " . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();
        
        return creerFieldset('Identit� :',
            afficher('Civilit� :', $row['COL_CIVILITE'], 'span3', 'span3', false)
            . afficher('Pr�nom* :', $row['COL_PRENOM'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Nom* :', $row['COL_NOM'], 'span3', 'span3', false)
            . afficher('Nom de jeune fille :', $row['COL_NOMJEUNEFILLE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Etat :', $row['COL_ETAT']==1? 'Actif' : 'Inactif', 'span3', 'span3', false)
            . afficher('Mn�monique* :', $row['COL_MNEMONIC'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Num�ro de t�l�phone:', $row['COL_TEL'], 'span3', 'span3', false)
            . afficher('Num�ro de t�l�phone portable*:', $row['COL_PRT'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('E-mail :', $row['COL_EMAIL'], 'span3', 'span3', false)
            . afficher('Nom du fournisseur :', $row['FOU_NOM'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Archiv� :', $row['COL_ARCHIVE'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
        );
    }
?>
