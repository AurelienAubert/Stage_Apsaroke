<?php
    require "inc/verif_session.php"; 
    include 'inc/liste_categorie.php';
    
    $page = array(
        'titre'     => 'Modifier ',
        'message'   => '',
        'contenu'   => '',
    );
    
    if (isset($_POST['recherche'])) {
        $page['recherche'] = htmlspecialchars (addslashes (trim (strtoupper ($_POST['recherche']))));
        if (isset($_GET['type']) && isset($GLOBALS['liste_categorie'][$_GET['type']])) {
            $type = $_GET['type'];
            $nom = $GLOBALS['titre_fichier'][$_GET['type']];

            if($type != 'client'){
                if (strpos($type, 'collaborateur') !== false) {
                    $dossier = 'collaborateur';
                } else {
                    $dossier = $type;
                }
                
                include $dossier . '/ajout_formulaire_' . $type . '.php';
                include $dossier . '/modif_' . $type . '.php';
                
                $nbpost = 1;
                if($type == 'projet' || $type == 'fournisseur' || $type == 'contact_client' || $type == 'contact_fournisseur' || strpos($type, 'collaborateur') !== false){
                    $nbpost = 3;
                }
                if (count($_POST) > $nbpost) {
                    $page['message'] = call_user_func('update_' . $type, $page['recherche']);
                } else {
                    if($type == 'projet' && isset($_POST['idmission'])){
                        call_user_func('recuperer_' . $type, $page['recherche'], $_POST['idmission']);
                    }else{
                        call_user_func('recuperer_' . $type, $page['recherche']);
                    }
                }
                
                $page['titre'] .= $nom;
                if(isset($_POST['LOGO'])){
                    $page['contenu'] = afficherFormulaire(true, $_POST['LOGO']);
                } else {
                    $page['contenu'] = afficherFormulaire(true);
                }
                
                include 'inc/page_modification.php';
            

            } else if($type == 'client'){
                $page['titre'] .= str_replace('_', ' ', $type);
                include 'client/modification_client.php';
            }
        
        } else {
            $page['message'] = 'Type d\'ajout manquant';
            include 'inc/page_modification.php';
        }
    }
    else {
        $page['message'] = 'Aucune recherche demandée';
        include 'inc/page_modification.php';
    }
?>
