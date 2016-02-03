<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_collaborateur_interne($recherche) {
        $query = "SELECT * FROM COLLABORATEUR C, INTERNE I LEFT JOIN FONCTION FCT ON FCT.FCT_NO=I.FCT_NO WHERE I.COL_NO = C.COL_NO AND I.COL_NO = " . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();
        
        $retour = creerFieldset('Identité :',
            afficher('Civilité :', $row['COL_CIVILITE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Prénom* :', $row['COL_PRENOM'], 'span3', 'span3', false)
            . afficher('Nom* :', $row['COL_NOM'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Etat :', $row['COL_ETAT'] == 1 ? 'Actif' : 'Inactif', 'span3', 'span3', false)
            . afficher('Archivé :', $row['COL_ARCHIVE'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
            . sautLigne()
            . afficher('Mnémonique* :', $row['COL_MNEMONIC'], 'span3', 'span3', false)
            . afficher('Période d\'essai :', $row['INT_PERIODEESSAI'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
            . sautLigne()
            . afficher('Prolongation de la période d\'essai :', $row['INT_PPE'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
            //. afficher('Nom de jeune fille :', $row['COL_NOMJEUNEFILLE'], 'offset1 span2', 'span3', false)
        );
        
        $retour .= creerFieldset('Coordonnées :',
            afficher('Adresse* :', $row['INT_ADRESSE'], 'span3', 'span3', false)
            . afficher('Adresse 2 :', $row['INT_ADRESSE2'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Code postal* :', $row['INT_CP'], 'span3', 'span3', false)
            . afficher('Ville* :', $row['INT_VILLE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Numéro de téléphone :', $row['COL_TEL'], 'span3', 'span3', false)
            . afficher('Numéro de téléphone portable* :', $row['COL_PRT'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('E-mail :', $row['COL_EMAIL'], 'span3', 'span3', false)
            . afficher('E-mail Apsaroke :', $row['COL_EMAILAPSA'], 'span3', 'span3', false)
        );

        $retour .= creerFieldset('Informations Complémentaires :',
            afficher('N° de sécurité sociale :', $row['INT_NSS'], 'span3', 'span3', false)
            . afficher('Date de naissance* :', format_date($row['INT_DTNAISSANCE']), 'span3', 'span3', false)
            . sautLigne()
            . afficher('Lieu de naissance* :', $row['INT_LIEUNAISSANCE'], 'span3', 'span3', false)
            . afficher('Nationalité :', $row['INT_NATIONALITE'], 'span3', 'span3', false)
        );

        $retour .= creerFieldset('Détails :', 
            afficher('Date d\'entrée* :', format_date($row['INT_DTENTREE']), 'span3', 'span3', false)
            . afficher('Date de départ :', format_date($row['INT_DTDEPART']), 'span3', 'span3', false)
            . sautLigne()
            . afficher('Fonction :', $row['FCT_NOM'], 'span3', 'span3', false)
            . afficher('Statut :', $row['INT_STATUT'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Coefficient :', $row['INT_COEFF'], 'span3', 'span3', false)
            . afficher('Position :', $row['INT_POSITION'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Type de contrat :', $row['INT_TYPECONTRAT'], 'span3', 'span3', false)
            . afficher('Type d\'horaire :', $row['INT_TYPEHORAIRE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Rémunération fixe annuelle (euros) :', $row['INT_REMUNFIXE'], 'span3', 'span3', false)
            . afficher('Rémunération variable annuelle (euros) :', $row['INT_REMUNVAR'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Frais journaliers (euros) :', $row['INT_FRAIS'], 'span3', 'span3', false)
        );

        $retour .= creerFieldset('Informations bancaires :', 
            afficher('Nom de la banque :', $row['INT_NOMBANQUE'], 'span3', 'span3', false)
            . afficher('IBAN :', $row['INT_IBAN'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('BIC :', $row['INT_BIC'], 'span3', 'span3', false)
        );

        $retour .= creerFieldset('Spécification collaborateur', 
            afficher('Facturable :', $row['INT_FACTURABLE'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
            . afficher('Coût GSM (euros) :', $row['INT_GSM'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Treizième mois :', $row['INT_TREIZIEME'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
            . afficher('Coût PEE mensuel (euros) :', $row['INT_PEE'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Prime variable :', $row['INT_PART_VARI'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
            . afficher('Prime d\'ancienneté (euros) :', $row['INT_PRIME_ANCI'], 'span3', 'span3', false)
            . sautLigne()
            . afficher('Tickets restaurants :', $row['INT_TR'] == 1 ? 'Oui' : 'Non', 'span3', 'span3', false)
        );
        return $retour;
    }
?>
