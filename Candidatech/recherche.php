<?php require "inc/verif_session.php"; ?>
<?php
    include 'inc/liste_categorie.php';
    include 'inc/liste.php';
    include 'inc/creer_input.php';
    
    $page = array(
        'titre'     => 'Rechercher ',
        'contenu'   => '',
        'action'    => '#',
        'message'   => '',
        'page'      => '',
        'type'      => ''
    );
    
    $action = array(
        'affichage',
        'modification',
        'etatConges',
        'suivi_commande',
        'etatRTT'
    );
    
    $archive = 0;
    if(isset($_GET['archive']) && $_GET['archive'] == 'archive'){
        $archive = 1;
    }
    
    if(isset($_GET['type']) && isset($_GET['action']))
    {
        $type = $_GET['type'];
        switch($_GET['type'])
        {
            case 'collaborateur_interne' :
            case 'collaborateur_externe' :
                $act = $_GET['action'];
                $GLOBALS['retour_page'] = "chx_type_collaborateur.php?action=$act";
                break;
            case 'mission':
                $act = $_GET['action'];
//                $GLOBALS['retour_page'] =  "chx_type_collaborateur.php?action=$act";
        }
    }
    
    if (isset($_GET['type']) && isset($GLOBALS['liste_categorie'][$_GET['type']])) {
        if (isset($_GET['action']) && in_array($_GET['action'], $action)) {
            $type = $_GET['type'];
            $nom = $GLOBALS['titre_fichier'][$_GET['type']];

            if (strpos($type, 'collaborateur')!== false) {
                $dossier = 'collaborateur';
            } else {
                $dossier = $type;
            }
            $prefixe = $GLOBALS['liste_categorie'][$_GET['type']];

            $page['contenu'] = select('Sélectionnez ' . $nom, 'recherche', donner_liste($type, $prefixe, $archive), 4, 3);

            $lien = $_GET['action'] . '.php?type=' . $type;

            // Si Documents, redirection vers la sélection du chapitre du document.
            if($type == 'libdocument'){
                $redir = $_GET['action'] . '.php?type=' . $type;
                $page['action'] = 'rechercheLibDoc.php?type='.$type.'&redir='.$redir;
                $page['titre'] .= $nom;               
            }
            // pour tous les autres (sélection unique)
            else
            {    
                $page['action'] = $_GET['action'] . '.php?type=' . $type;
                $page['titre'] .= $nom;
            }

        }
        else {
            $page['message'] = 'Action désirée manquante';
        }
    }
    else {
        $page['message'] = 'Type de recherche manquant';
    }
    include 'inc/page_recherche.php';
?>
