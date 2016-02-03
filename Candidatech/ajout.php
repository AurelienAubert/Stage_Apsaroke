<?php
    include "inc/verif_session.php";
    include "inc/connection.php"; 
    include 'inc/liste_categorie.php';
    
    $page = array(
        'titre'     => 'Créer ',
        'message'   => '',
        'contenu'   => '',
    );

    if (isset($_GET['type']) && isset($GLOBALS['liste_categorie'][$_GET['type']])) {
        $type = $_GET['type'];
        $nom = $GLOBALS['titre_fichier'][$_GET['type']];
        
        if($type != 'client')
        {
            if (strpos($type, 'collaborateur') !== false) {
                $dossier = 'collaborateur';
            } else {
                $dossier = $type;
            }
            include $dossier . '/ajout_formulaire_' . $type . '.php';

            if (!empty($_POST)) {
                include $dossier.'/ajout_' . $type . '.php';
                $page['message'] = call_user_func('ajout_' . $type);
            }
            $page['titre'] .= $nom;
            $page['contenu'] = afficherFormulaire();

            include 'inc/page_ajout.php';
        }
        else 
        {
            $page['titre'] .= str_replace('_', ' ', $type);
            include 'client/ajouter_client.php';
        }
    }
    else {
        $page['contenu']='';
        $page['message']='Type d\'ajout manquant';
        
        include 'inc/page_ajout.php';
    }
?>
