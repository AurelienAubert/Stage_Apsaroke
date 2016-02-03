<?php

    include_once "inc/creer_input.php";
    
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $legende = 'Modifiez le fournisseur choisi puis validez';
        }
        else {
            $legende = 'Nouveau fournisseur';
            $_POST['ARCHIVE'] = 0;
        }
        
        return creerFieldset($legende, array(
            input('Code fournisseur* :', 'CODE', 3, 3, true),
            input('Nom du fournisseur* :', 'NOM', 3, 3, true, 'text'),
            sautLigne(),
            input('Date de d�but de collaboration* :', 'DTCREATION', 3, 3, true, 'date'),
            radio('Etat du fournisseur :', 'ARCHIVE', 'Archiv�', 'Encours', 3, 1, 2)
        ));
    }
?>
