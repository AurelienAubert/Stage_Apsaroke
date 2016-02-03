<?php
    include_once 'inc/connection.php';
    include_once "inc/creer_input.php";
    include_once ("inc/liste.php");
    /**
     * affiche le formulaire correspondant à l'ajout ou à la modification d'un projet
     * @param bool $modification
     */
    function afficherFormulaire($modification=false) {
        if ($modification) {
            $legende = 'Modifiez le projet choisi puis validez';
            $cloture = sautLigne() . radio('Etat du projet :', 'ARCHIVE', 'Archivé', 'Encours', 2, 1, 2) . '<div class="span1"></div>';
            $dtcloture = input('Date de cloture :', 'DTCLOTURE', 3, 3, false, 'date');
            //$mission = sautLigne() . afficher('<b>MISSION</b>', '', 'span2', 'span3');
            $mission = 'MISSION';

        }
        else {
            $legende = 'Nouveau projet';
            $cloture = '';
            $dtcloture = '';
            //$mission = sautLigne() . afficher('<b>Nouvelle MISSION</b>', '', 'span2', 'span3');
            $mission = 'Nouvelle MISSION';
        }
        if ($_POST['action'] == 'creer'){
            //$mission = sautLigne() . afficher('<b>Nouvelle MISSION</b>', '', 'span2', 'span3');
            $mission = 'Nouvelle MISSION';
        }
        
        $retour = creerFieldset($legende, array(
            select('Client :', 'CLIENT', array() + donner_liste('CLIENT', 'CLI'), 3, 3, true),
            input('Nom du projet :', 'NOM', 3, 3, true),
            sautLigne(),
            select('Contact client :', 'CTC', array() + donner_liste('CONTACT_CLIENT', 'CTC'), 3, 3, false),
            select('Contact fournisseur (si il y en a un) :', 'CTF', array('' => '') + donner_liste('CONTACT_FOURNISSEUR', 'CTF'), 3, 3, false),
            sautLigne(),
            input('Date de démarrage :', 'DTDEBUT', 3, 3, true, 'date'),
            input('Numéro de cde du projet :', 'NUMCMDE', 3, 3, true),
            sautLigne(),
            select('Collaborateur :', 'COL', array() + donner_liste('COLLABORATEUR', 'COL'), 3, 3, false),
            select('Projet suivi par :', 'SUIVIPAR', array() + donner_liste('COMMERCIAL', 'COL'), 3, 3, false),
            sautLigne(),
            input('Durée prévisionnelle (jours) :', 'NBJOURS', 3, 3, false),
            input('Date de fin prévue :', 'DTFINPREVUE', 3, 3, false, 'date'),
            sautLigne(),
            textarea('Détails :', 'DETAIL', 3, 8, true, 1, 80, 'textarea800'),
            sautLigne(),
            textarea('Modalités :', 'MODALITE', 3, 8, false, 4, 80, 'textarea800'),
            $cloture,
            $dtcloture,
            //$mission,
            input('', 'NO', 2, 3, true, 'hidden')
        ));

            // Partie mission
        $retour .= creerFieldset($mission, array(
            //sautLigne(),
            input('Nom de la mission :', 'MISNOM', 3, 3, true),
            sautLigne(),
            input('Numéro de cde :', 'MISNUMCMDE', 3, 3, true),
            input('Date de cde :', 'MISDATECMDE', 3, 3, true, 'date'),
            sautLigne(),
            input('Début de mission :', 'MISDTDEBUT', 3, 3, true, 'date'),
            input('Fin de mission :', 'MISDTFIN', 3, 3, true, 'date'),
            sautLigne(),
            input('Durée prévisionnelle (jours) :', 'MISNBJOURS', 3, 3, false),
            //select('Suivi par :', 'MISSUIVIPAR', array() + donner_liste('COMMERCIAL', 'COL'), 3, 3, false),
            sautLigne(),
            radio('Forfait :', 'MISFORFAIT', 'Oui', 'Non', 3, 1, 1),
            '<div class="span1"></div>',
            input('Montant du forfait :', 'MISMONTFORFAIT', 3, 3, false),
            sautLigne(),
            input('Taux journalier :', 'MISTJM', 3, 3, false),
            input('Prix d\'achat :', 'MISPA', 3, 3, false),
            sautLigne(),
            textarea('Commentaire :', 'MISCOMMENTAIRE', 3, 8, false, 4, 80, 'textarea800'),
            
            input('', 'MISORDRE', 2, 3, false, 'hidden'),
            input('', 'MISNSEQUENTIEL', 2, 3, false, 'hidden'),
            input('', 'MISNO', 2, 3, false, 'hidden'),
            input('', 'PRONO', 2, 3, false, 'hidden'),
            input('', 'idmission', 2, 3, false, 'hidden'),
            input('', 'action', 2, 3, false, 'hidden'),
        ));

        ob_start();
        ?>
<script>
    $(document).ready(function() {
        $('[name="DETAIL"]').css('width', '800px');
        $('[name="NOM"]').change(function(){
            $('[name="MISNOM"]').val($('[name="NOM"]').val());
        });
        $('[name="NUMCMDE"]').change(function(){
            $('[name="MISNUMCMDE"]').val($('[name="NUMCMDE"]').val());
        });
    });
</script>
        <?php
        $retour .= ob_get_contents();
        ob_end_clean();
        return $retour;
    }
    
?>