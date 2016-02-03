<?php
    include_once "inc/creer_input.php";
    
    function afficherFormulaire($modification = false) {
        if ($modification) {
            $legende = 'Modifiez le client choisi puis validez';
        }
        else {
            $legende = 'Nouveau client';
        }
        $contenu = null;
        $image = null;
        if(isset($_POST['LOGO']))
        {
            $contenu = $_POST['LOGO'];
            $image = true;
        }
        else 
        {
            $contenu = 'Ce client n\'a pas de logo';
            $image = false;
        }
        
        return creerFieldset($legende, array(
            input('Code client* :', 'CODE', 3, 3, true),
            input('Début de collaboration* :', 'DTCREATION', 3, 3, true, 'date', 'offset2'),
            sautLigne(),
            input('Nom du client* :', 'NOM', 3, 3, true, 'text'),
            input('Nom de facturation :', 'NOMFAC', 3, 3, true, 'text', 'offset1'),
            sautLigne(),
            input('1ère adresse* :', 'ADRCOM_1', 3, 3, true),
            input('1ème adresse :', 'ADRFAC_1', 3, 3, false, 'text', 'offset1'),
            sautLigne(),
            input('2ère adresse :', 'ADRCOM_2', 3, 3, true),
            input('2ème adresse :', 'ADRFAC_2', 3, 3, false, 'text', 'offset1'),
            sautLigne(),
            input('Code postal* :', 'CPCOM', 3, 3, true),
            input('Code postal :', 'CPFAC', 3, 3, false, 'text', 'offset1'),
            sautLigne(),
            input('Ville* :', 'VILLECOM', 3, 3, true),
            input('Ville :', 'VILLEFAC', 3, 3, false, 'text', 'offset1'),
            sautLigne(),
            input('Pays :', 'PAYS', 3, 3, false),
            input('Code fournisseur :', 'CODE_FOUR', 3, 3, false, 'text', 'offset1'),
            sautLigne(),
            afficher_image('Logo du client :', $contenu, 'span3', 'span3', $image),
            sautLigne(),
            input('', 'LOGO', 0, 2, false, 'file'),
            '<input type="hidden" ordre="1" name="logo_client" value="' . $contenu . '"></input>',
            '<br/><br/><div class="row" style="background-color:#F5F5F5; ">',
            '<button name="supprime" class="btn btn-primary" type="button">Supprimer l\'image <i class="icon-ok"></i> </button>',
            '</div>',
        ));
    }
?>
