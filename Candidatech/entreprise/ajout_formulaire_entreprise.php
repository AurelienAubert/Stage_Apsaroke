<?php
    include_once 'inc/connection.php';
    include_once 'inc/creer_input.php';
    include_once 'inc/liste.php';

    /**
     * affiche le formulaire correspondant à l'ajout ou à la modification d'un projet
     * @param bool $modification
     */
    function afficherFormulaire($modification=false, $link=false) {
        if ($modification) {
            $legende = 'Modifiez l\'entreprise choisie puis validez';
        }
        else {
            $legende = 'Nouvelle entreprise';
        }
        $_POST['IMGENT'] = $_POST['LOGO'];
        $_POST['IMGPIE'] = $_POST['LOGOPIED'];
        
        $retour = creerFieldset($legende, array(
            input('Nom de l\entreprise : ', 'NOM', 3, 3, true),
            input('Statut de l\'entreprise : ', 'STATUT', 3, 3, true),
            sautLigne(),
            input('Adresse de l\'entreprise : ', 'ADRESSE', 3, 3, true),
            input('Complément d\'adresse de l\'entreprise : ', 'ADRESSE_2', 3, 3, false),
            sautLigne(),
            input('Code postal de l\'entreprise : ', 'CP', 3, 3, true),
            input('Numéro de téléphone l\'entreprise : ', 'TEL', 3, 3, true),
            sautLigne(),
            input('Ville de l\'entreprise : ', 'VILLE', 3, 3, true),
            input('Capital de l\'entreprise : ', 'CAPITAL', 3, 3, true),
            sautLigne(),
            input('Numéro de TVA intracommunautaire de l\'entreprise : ', 'TVA_INTRA', 3, 3, true),
            input('Numéro du RCS de l\'entreprise : ', 'RCS', 3, 3, true),
            sautLigne(),
            input('Numéro SIRET de l\'entreprise : ', 'SIRET', 3, 3, true),
            input('Numéro APE de l\'entreprise : ', 'APE', 3, 3, true),
            sautLigne(),
            input('Adresse web de l\'entreprise : ', 'SITE_WEB', 3, 3, false),
            sautLigne(),
            input('Logo entête : ', 'LOGO', 3, 3, false, 'file', '', '', $_POST['LOGO']),
            sautLigne(),
            input('Logo pied : ', 'LOGOPIED', 3, 3, false, 'file', '', '', $_POST['LOGOPIED']),
            input('', 'IMGENT', 0, 0, false, 'hidden'),
            input('', 'IMGPIE', 0, 0, false, 'hidden'),
            ));
        return $retour;
    }
