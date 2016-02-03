<?php
require "inc/verif_session.php"; 
include_once "inc/connection.php";
require "inc/def_rep_document.php"; 

$page = array(
    'titre'     => 'Préparer une impression : ',
    'message'   => '',
    'contenu'   => '',
    'dossier'   => ''
);
print_r($_GET);
if (isset($_GET['recherche'])) {
    $page['recherche'] = htmlspecialchars (addslashes (trim (strtoupper ($_GET['recherche']))));
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
        $dossier = $repdocimp[$type];
        include $dossier . '/prepare_' . $type . '.php';

        // Lecture des types de documents.
        $q1 = "SELECT * FROM DOCUMENT WHERE DOC_CODE='" . $type . "'";
        $r1 = $GLOBALS['connexion']->query($q1)->fetch_assoc();
        $page['titre'] .= $r1['DOC_NOM'];
        $page['dossier'] = $dossier;
        call_user_func('recuperer_' . $type, $page['recherche'], $type);
        $page['contenu'] = afficherFormulaire();

        include 'inc/page_prepare.php';

    }
    else {
        $page['message']='Type d\'ajout manquant';
        include 'inc/page_prepare.php';
    }
}
else {
    $page['message']='Aucune recherche demandée';
    include 'inc/page_prepare.php';
}
?>
