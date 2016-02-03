<?php
    include_once "inc/creer_input.php";
    include_once "inc/liste.php";
    /**
     * affiche le formulaire correspondant à l'ajout ou à la modification d'un collaborateur interne
     * @param bool $modification
     */
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $legende = 'Modifiez le collaborateur externe choisi puis validez';
            //$mailApsa = input('Email Apsaroke :', 'EMAILAPSA', 3, 3, true, 'hidden');
        }
        else {
            $legende = 'Nouveau collaborateur externe';
            //$mailApsa = input('Email Apsaroke :', 'EMAILAPSA', 3, 3, true, 'hidden');
        }

        return creerFieldset($legende, array(
            select('Civilité :', 'CIVILITE', array('M.' => 'M.', 'Mme.' => 'Mme.', 'Mlle.' => 'Mlle.'), 3, 3),
            input('Prénom* :', 'PRENOM', 3, 3, true),
            sautLigne(),
            input('Nom* :', 'NOM', 3, 3, true),
            input('Nom de jeune fille :', 'NOMJEUNEFILLE', 3, 3),
            sautLigne(),
            radio('Etat :', 'ETAT', 'Actif', 'Inactif', 3, 1, 1),
            inputMNEMO('Mnémonique* :', 'MNEMONIC', 3, 3, 'offset1'),
            '<span id="txtHint"></span>',
            sautLigne(),
            input('Numéro de téléphone :', 'TEL', 3, 3),
            input('Numéro de téléphone portable* :', 'PRT', 3, 3, true),
            sautLigne(),
            input('E-mail :', 'EMAIL', 3, 3),
            select('Fournisseur* :', 'FOURNISSEUR', donner_liste('fournisseur', 'FOU'), 3, 3),
            sautLigne(),
            radio('Archiver :', 'ARCHIVE', 'Oui', 'Non  ', 3, 1, 1),
            //$mailApsa,
        ));
    }
?>
