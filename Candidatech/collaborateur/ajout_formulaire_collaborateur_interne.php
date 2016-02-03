<?php
    include_once "inc/creer_input.php";
    include_once ("inc/liste.php");
    
    /**
     * affiche le formulaire correspondant � l'ajout ou � la modification d'un collaborateur interne
     * @param bool $modification
     */
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $mailApsa = input('Email Apsaroke :', 'EMAILAPSA', 3, 3, true);
        }
        else {
            $mailApsa = '';
        }
        $retour = creerFieldset('Identit� :', array(
            select('Civilit� :', 'CIVILITE', array('M.' => 'M.', 'Mme.' => 'Mme.', 'Mlle.' => 'Mlle.'), 3, 3),
            input('Pr�nom* :', 'PRENOM', 3, 3, true),
            sautLigne(),
            input('Nom* :', 'NOM', 3, 3, true),
            input('Nom de jeune fille :', 'NOMJEUNEFILLE', 3, 3),
            sautLigne(),
            inputMNEMO('Mn�monique* :', 'MNEMONIC', 3, 3),
            '<span id="txtHint"></span>',
        ));

        $retour .= creerFieldset('Coordonn�es :', array(
            input('Adresse* :', 'ADRESSE', 3, 3, true),
            input('Adresse 2 :', 'ADRESSE2', 3, 3),
            sautLigne(),
            input('Code postal* :', 'CP', 3, 3, true),
            input('Ville* :', 'VILLE', 3, 3, true),
            sautLigne(),
            input('Num�ro de t�l�phone :', 'TEL', 3, 3),
            input('Num�ro de t�l�phone portable* :', 'PRT', 3, 3, true),
            sautLigne(),
            input('E-mail :', 'EMAIL', 3, 3),
            $mailApsa,
        ));

        $retour .= creerFieldset('Informations Compl�mentaires :', array(
            input('N� de s�curit� sociale :', 'NSS', 3, 3),
            input('Date de naissance* :', 'DTNAISSANCE', 3, 3, true, 'date'),
            sautLigne(),
            input('Lieu de naissance* :', 'LIEUNAISSANCE', 3, 3, true),
            input('Nationalit� :', 'NATIONALITE', 3, 3),
        ));

        $retour .= creerFieldset('D�tails :', array(
            input('Date d\'entr�e* :', 'DTENTREE', 3, 3, true, 'date'),
            input('Date de d�part :', 'DTDEPART', 3, 3, false, 'date'),
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
            input('R�mun�ration fixe :', 'REMUNFIXE', 3, 3),
            input('R�mun�ration variable :', 'REMUNVAR', 3, 3),
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
            radio('P�riode d\'essai :', 'PERIODEESSAI', 'Oui', 'Non', 3, 1, 1),
            '<div class="span1">&nbsp;</div>',
            radio('Prolongation de la p�riode d\'essai :', 'PPE', 'Oui', 'Non', 3, 1, 1),
        ));

        $retour .= creerFieldset('Informations bancaires :', array(
            input('Nom de la banque :', 'NOMBANQUE', 3, 3),
            input('IBAN :', 'IBAN', 3, 3),
            sautLigne(),
            input('BIC :', 'BIC', 3, 3),
        ));

        $retour .= creerFieldset('Sp�cification collaborateur', array(
            radio('Facturable :', 'FACTURABLE', 'Oui', 'Non', 3, 1, 0),
            '<div class="span1"></div>',
            input('Co�t GSM :', 'GSM', 3, 3),
            sautLigne(),
            radio('Treizi�me mois :', 'TREIZIEME', 'Oui', 'Non', 3, 1, 0),
            '<div class="span1"></div>',
            input('Co�t PEE mensuel :', 'PEE', 3, 3),
            sautLigne(),
            radio('Prime variable :', 'PART_VARI', 'Oui', 'Non', 3, 1, 0),
            '<div class="span1"></div>',
            input('Prime d\'anciennet� :', 'PRIME_ANCI', 3, 3),
        ));
        if ($_SESSION['accreditation'] < 2){
            $retour .= creerFieldset('Accr�ditation :', array(
                select('Accr�ditation :', 'TAUNO', array(''=>'') + donner_liste('TYPE_AUTORISATION', 'TAU'), 3, 3, false),
            ));
        }
        
        return $retour;
    }
    
?>
