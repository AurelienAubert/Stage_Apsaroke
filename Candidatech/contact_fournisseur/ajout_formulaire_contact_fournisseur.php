<?php
    include_once "inc/creer_input.php";
    include_once 'inc/liste.php';
    
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $legende = 'Modifiez le contact fournisseur choisi puis validez';
        }
        else {
            $legende = 'Nouveau contact fournissur';
            $_POST['ARCHIVE'] = 0;
        }

        return creerFieldset($legende, array(
            select('Fournisseur :', 'FOURNISSEUR', donner_liste('FOURNISSEUR', 'FOU'), 3, 3),
            input('Nom du contact* :', 'NOM', 3, 3, true),
            sautLigne(),
            input('Prénom du contact* :', 'PRENOM', 3, 3, true),
            input('E-mail du contact* :', 'EMAIL', 3, 3, true),
            sautLigne(),
            input('Portable du contact* :', 'PRT', 3, 3, true),
            select('Fonction du contact* :', 'FONCTION', array(''=>'') + donner_liste('FONCTION', 'FCT'), 3, 3, true),
            sautLigne(),
            radio('Etat du contact :', 'ARCHIVE', 'Archivé', 'Encours', 3, 1, 2),
            sautLigne(),
            input('Commentaire :', 'COMMENTAIRE', 3, 3),
        ));
    }
?>
