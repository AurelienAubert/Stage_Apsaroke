<?php
    include_once "inc/creer_input.php";
    include_once ("inc/liste.php");
    
    /**
     * affiche le formulaire correspondant à l'ajout ou à la modification d'un collaborateur interne
     * @param bool $modification
     */
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $mailApsa = input('Email Apsaroke :', 'EMAILAPSA', 3, 3, true);
        }
        else {
            $mailApsa = '';
        }
        $retour = creerFieldset('Identité :', array(
            select('Civilité :', 'CIVILITE', array('M.' => 'M.', 'Mme.' => 'Mme.', 'Mlle.' => 'Mlle.'), 3, 3),
            input('Prénom* :', 'PRENOM', 3, 3, true),
            sautLigne(),
            input('Nom* :', 'NOM', 3, 3, true),
            input('Nom de jeune fille :', 'NOMJEUNEFILLE', 3, 3),
            sautLigne(),
            inputMNEMO('Mnémonique* :', 'MNEMONIC', 3, 3),
            '<span id="txtHint"></span>',
        ));

        $retour .= creerFieldset('Coordonnées :', array(
            input('Adresse* :', 'ADRESSE', 3, 3, true),
            input('Adresse 2 :', 'ADRESSE2', 3, 3),
            sautLigne(),
            input('Code postal* :', 'CP', 3, 3, true),
            input('Ville* :', 'VILLE', 3, 3, true),
            sautLigne(),
            input('Numéro de téléphone :', 'TEL', 3, 3),
            input('Numéro de téléphone portable* :', 'PRT', 3, 3, true),
            sautLigne(),
            input('E-mail :', 'EMAIL', 3, 3),
            $mailApsa,
        ));

        $retour .= creerFieldset('Informations Complémentaires :', array(
            input('N° de sécurité sociale :', 'NSS', 3, 3),
            input('Date de naissance* :', 'DTNAISSANCE', 3, 3, true, 'date'),
            sautLigne(),
            input('Lieu de naissance* :', 'LIEUNAISSANCE', 3, 3, true),
            input('Nationalité :', 'NATIONALITE', 3, 3),
        ));

        $retour .= creerFieldset('Détails :', array(
            input('Date d\'entrée* :', 'DTENTREE', 3, 3, true, 'date'),
            input('Date de départ :', 'DTDEPART', 3, 3, false, 'date'),
            sautLigne(),
            select('Fonction :', 'FONCTION', array(''=>'') + donner_liste('FONCTION', 'FCT'), 3, 3, false),
            input('Statut :', 'STATUT', 3, 3),
            sautLigne(),
            input('Coefficient :', 'COEFF', 3, 3),
            input('Position :', 'POSITION', 3, 3),
            sautLigne(),
            input('Type de contrat :', 'TYPECONTRAT', 3, 3),
            input('Type d\'horaire :', 'TYPEHORAIRE', 3, 3),
            sautLigne(),
            input('Rémunération fixe :', 'REMUNFIXE', 3, 3),
            input('Rémunération variable :', 'REMUNVAR', 3, 3),
            sautLigne(),
            input('Frais journaliers :', 'FRAIS', 3, 3),
            sautLigne(),
            radio('Etat :', 'ETAT', 'Actif', 'Inactif', 3, 1, 1),
            '<div class="span1"></div>',
            radio('Archiver :', 'ARCHIVE', 'Oui', 'Non  ', 3, 1, 1),
            '<div class="span1"></div>',
            sautLigne(),
            radio('Tickets restaurants :', 'TR', 'Oui', 'Non', 3, 1, 1),
            sautLigne(),
            radio('Période d\'essai :', 'PERIODEESSAI', 'Oui', 'Non', 3, 1, 1),
            '<div class="span1">&nbsp;</div>',
            radio('Prolongation de la période d\'essai :', 'PPE', 'Oui', 'Non', 3, 1, 1),
        ));

        $retour .= creerFieldset('Informations bancaires :', array(
            input('Nom de la banque :', 'NOMBANQUE', 3, 3),
            input('IBAN :', 'IBAN', 3, 3),
            sautLigne(),
            input('BIC :', 'BIC', 3, 3),
        ));

        $retour .= creerFieldset('Spécification collaborateur', array(
            radio('Facturable :', 'FACTURABLE', 'Oui', 'Non', 3, 1, 0),
            '<div class="span1"></div>',
            input('Coût GSM :', 'GSM', 3, 3),
            sautLigne(),
            radio('Treizième mois :', 'TREIZIEME', 'Oui', 'Non', 3, 1, 0),
            '<div class="span1"></div>',
            input('Coût PEE mensuel :', 'PEE', 3, 3),
            sautLigne(),
            radio('Prime variable :', 'PART_VARI', 'Oui', 'Non', 3, 1, 0),
            '<div class="span1"></div>',
            input('Prime d\'ancienneté :', 'PRIME_ANCI', 3, 3),
        ));
        if ($_SESSION['accreditation'] < 2){
            $retour .= creerFieldset('Accréditation :', array(
                select('Accréditation :', 'TAUNO', array(''=>'') + donner_liste('TYPE_AUTORISATION', 'TAU'), 3, 3, false),
            ));
        }
        
        return $retour;
    }
    
?>
