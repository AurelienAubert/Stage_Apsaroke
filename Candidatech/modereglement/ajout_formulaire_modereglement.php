<?php
    include_once 'inc/connection.php';
    include_once 'inc/creer_input.php';
    include_once 'inc/liste.php';

    /**
     * affiche le formulaire correspondant à l'ajout ou à la modification d'un enregistrement
     * @param bool $modification
     */
    function afficherFormulaire($modification=false) {
        if ($modification) {
            $legende = 'Modifiez le mode de règlement choisie puis validez';
        }
        else {
            $legende = 'Nouveau mode de règlement';
        }
        
        $retour = creerFieldset($legende, array(
            input('Code :', 'CODE', 3, 3, true),
            sautLigne(),
            input('Libellé :', 'LIBELLE', 3, 3, true, 'text', '', 'input500'),
            sautLigne(),
        ));
        return $retour;
    }