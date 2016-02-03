<?php

    include_once "inc/creer_input.php";
    include_once ("inc/liste.php");
    
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $legende = 'Modifiez le libellé choisi puis validez';
        }
        else {
            $legende = 'Nouveau libellé';
        }
        
        return creerFieldset($legende, array(
            inputRO('Document :', 'DOCNOM', 2, 4),
            inputRO('No d\'ordre* :', 'ORDRE', 2, 2),
            sautLigne(),
            input('Intitulé* :', 'NOMD', 2, 4, true, 'text', '', 'input500'),
            //select('Collaborateur :', 'COL', array(''=>'') + donner_liste('COLLABORATEUR', 'COL'), 2, 4, false),
            input('', 'COL', 2, 4, false, 'hidden'),
            sautLigne(),
            textarea('Contenu* :', 'CONTENU', 2, 8, true, 10, 80, 'textarea800'),
            input('', 'DOCNO', 2, 4, false, 'hidden'),
        ));
    }
?>
