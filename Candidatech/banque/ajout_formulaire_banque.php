<?php
    include_once 'inc/connection.php';
    include_once 'inc/creer_input.php';
    include_once 'inc/liste.php';

    /**
     * affiche le formulaire correspondant à l'ajout ou à la modification d'un projet
     * @param bool $modification
     */
    function afficherFormulaire($modification=false) {
        if ($modification) {
            $legende = 'Modifiez la banque choisie puis validez';
        }
        else {
            $legende = 'Nouvelle banque';
        }
        
        $retour = creerFieldset($legende, array(
            input('Nom de la banque :', 'NOM', 3, 5, true, 'text', '', 'input500'),
            sautLigne(),
            input('Code banque :', 'CDE_BAN', 3, 3, true),
            input('Code guichet :', 'CDE_GUI', 3, 3, true),
            sautLigne(),
            input('Numéro de compte banque :', 'NUM_CPT', 3, 3, true),
            input('Numéro de RIB :', 'RIB', 3, 3, true),
        ));
        return $retour;
    }