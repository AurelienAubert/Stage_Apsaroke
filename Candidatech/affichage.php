<?php
    include 'inc/liste_categorie.php';
    
    $page = array(
        'titre'     => 'Afficher ',
        'contenu'   => '',
        'action'    => '#',
        'recherche' => '',
        'message'   => ''
    );

    // ID à rechercher
    $idrech = '';
    if (isset($_POST['recherche'])) {
        $idrech = $_POST['recherche'];
    }else if (isset($_GET['recherche'])){
        $idrech = $_GET['recherche'];
    }
    // Test si message
    if (isset($_GET['message'])){
        if ($_GET['message'] == 'MAJOK'){
            $page['message'] = 'Vos données ont été enregistrées.';
        }else{
            $page['message'] = $_GET['message'];
        }
    }
    if ($idrech != '') {
        $page['recherche'] = htmlspecialchars (addslashes (trim (strtoupper ($idrech))));
        if (isset($_GET['type']) && isset($GLOBALS['liste_categorie'][$_GET['type']])) {
            $type = $_GET['type'];
            $nom = $GLOBALS['titre_fichier'][$type];

            if (strpos($type, 'collaborateur')!== false) {
                $dossier = 'collaborateur';
//            }else if($type == 'proforma'){
//                $dossier = $type = 'facture';
            }else{
                $dossier = $type;
            }
            include $dossier . '/affichage_' . $type . '.php';
            
            if($type == 'projet' && isset($_POST['idmission'])){
                $page['contenu'] = call_user_func('afficher_' . $type, $page['recherche'], $_POST['idmission']);
            }else{
                $page['contenu'] = call_user_func('afficher_' . $type, $page['recherche']);
            }
            $page['action'] = 'modification.php?type=' . $type;
            $page['titre'] .= $nom;
        }else {
            $page['contenu'] = '';
            $page['message'] = 'Type d\'affichage manquant';
        }
    }
    else {
        $page['message'] = 'Aucune recherche demandée';
    }
    include 'inc/page_affichage.php';
?>
