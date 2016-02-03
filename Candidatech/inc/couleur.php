<?php

include_once ('connection.php');

$couleur_ram = $_POST['COULEUR_RAM'];
$couleur_conges = $_POST['COULEUR_CONGES'];
$couleur_adm = $_POST['COULEUR_ADM'];
$couleur_frais = $_POST['COULEUR_FRAIS'];
$couleur_autre = $_POST['COULEUR_AUTRE'];

$rq_couleur_ram = "UPDATE PARAMETRE SET PAR_VALEUR = '" . $couleur_ram . "' WHERE PAR_LIBELLE = 'couleur_ram';";
$rq_couleur_conges = "UPDATE PARAMETRE SET PAR_VALEUR = '" . $couleur_conges . "' WHERE PAR_LIBELLE = 'couleur_conge';";
$rq_couleur_adm = "UPDATE PARAMETRE SET PAR_VALEUR = '" . $couleur_adm . "' WHERE PAR_LIBELLE = 'couleur_adm';";
$rq_couleur_frais = "UPDATE PARAMETRE SET PAR_VALEUR = '" . $couleur_frais . "' WHERE PAR_LIBELLE = 'couleur_frais';";
$rq_couleur_autre = "UPDATE PARAMETRE SET PAR_VALEUR = '" . $couleur_autre . "' WHERE PAR_LIBELLE = 'couleur_autre';";

$GLOBALS['connexion']->query($rq_couleur_ram);
$GLOBALS['connexion']->query($rq_couleur_conges);
$GLOBALS['connexion']->query($rq_couleur_adm);
$GLOBALS['connexion']->query($rq_couleur_frais);
$GLOBALS['connexion']->query($rq_couleur_autre);

header('Location:../page_enregistre.php?retour=couleur.php');

?>
