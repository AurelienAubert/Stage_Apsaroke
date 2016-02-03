<?php

require "inc/verif_session.php";
include 'inc/creer_input.php';
include 'inc/liste.php';
include 'inc/verif_champs_formulaire.php';
include 'inc/suppression_donnees.php';
include 'inc/liste_categorie.php';

$page = array(
    'titre' => 'Supprimer ',
    'message' => ''
);

if (isset($_GET['type']) && isset($GLOBALS['liste_categorie'][$_GET['type']])) {
    if (!empty($_POST)) {
        $var = verif_champs(array('ID' => 1));
        $type = $_GET['type'];
        if (is_array($var)) {
            call_user_func('supprimer_' . $type, $var['ID']);
            unset($_POST);
        } else {
            $page['message'] = $var;
        }
    }
    $nom = $GLOBALS['titre_fichier'][$_GET['type']];
    $prefixe = $GLOBALS['liste_categorie'][$_GET['type']];
    $page['titre'] .= $nom;
    $page['contenu'] = select('Sélectionnez un ' . $nom, 'ID', donner_liste($_GET['type'], $prefixe), 2, 2);
} else {
    $page['contenu'] = '';
    $page['message'] = 'Type de suppression manquant';
}
include 'inc/page_suppression.php';
?>