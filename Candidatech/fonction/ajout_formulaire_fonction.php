<?php

    include_once "inc/creer_input.php";
    
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $legende = 'Modifiez la fonction choisie puis validez';
        }
        else {
            $legende = 'Nouvelle fonction';
        }
        
        return creerFieldset($legende, array(
            input('Libellé de la fonction* :', 'NOM', 3, 3, true),
            sautLigne(),
        ));
    }
?>
