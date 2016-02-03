<?php
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-cache, must-revalidate'); 
header('Pragma: no-cache');

include_once 'inc/connection.php';
include_once ('calendrier/fonction_nomMois.php');

if(isset($_GET['FAC_MT_AVOIR']) && isset($_GET['FAC_NO'])){
    $qfac = 'SELECT * FROM FACTURE WHERE FAC_NO=' . $_GET['FAC_NO'];
    $rfac = $GLOBALS['connexion']->query($qfac)->fetch_assoc();
    $facture = substr($rfac['FAC_DATE'], 2, 2) . MnemoMois(substr($rfac['FAC_DATE'], 5, 2));
    
    $FAC_NUM_QUERY = $GLOBALS['connexion']->query('SELECT PAR_VALEUR FROM PARAMETRE WHERE PAR_LIBELLE = "FAC_NUM"')->fetch_assoc();
    $facture = strtoupper($facture);
    $facture .= $FAC_NUM_QUERY['PAR_VALEUR'];
    
    $MT = (int)$_GET['FAC_MT_AVOIR'];
    $NO = (int)$_GET['FAC_NO'];
    $COM = $_GET['FAC_AVO_COM'];
    
    $query = 'UPDATE FACTURE SET FAC_AVO="'.$facture.'", FAC_MT_AVOIR="'.$MT.'", FAC_AVO_COM="'.$COM.'" WHERE FAC_NO="'.$NO.'"';
    $GLOBALS['connexion']->query($query);
    
    $FAC_NUM_QUERY['PAR_VALEUR'] ++;
    $GLOBALS['connexion']->query('UPDATE PARAMETRE SET PAR_VALEUR =' . $FAC_NUM_QUERY['PAR_VALEUR'] . ' WHERE PAR_LIBELLE = "FAC_NUM"');
    
    //TODO : Mise à jour numéro facture (table parametre), numéro de facture complète (manque par exemple : 15JR)
    //Page visu_proforma : Maj bouton facture-avoir

}
else if(isset($_GET['FAC_NO'])){
    $NO = (int)$_GET['FAC_NO'];
    $query = 'SELECT FAC_TOTAL_HT FROM FACTURE WHERE FAC_NO="'.$NO.'"';
    $stmt = $GLOBALS['connexion']->query($query)->fetch_assoc();

    echo $stmt['FAC_TOTAL_HT'];
    echo '&shy';
    echo $NO;
}
