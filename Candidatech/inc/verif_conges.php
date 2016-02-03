<?php

include_once 'inc/connection.php';

/*
 * récupération de la liste des types d'absence
 */
$query = "SELECT ID_TYABS, NOM_TYABS FROM type_abs";
$result = $GLOBALS['connexion']->query($query);
$types = array();
while ($row = $result->fetch_assoc()) {
    $types[$row['NOM_TYABS']] = $row['ID_TYABS'];
}

$nbJours = date("t", mktime(0, 0, 0, $_SESSION['mois'], 1, $_SESSION['annee']));
$i=1;

$valeurs = array(
    'ID_COL'    => $_SESSION['col_id'],
    'MOIS_DEM'  => $_SESSION['mois'],
    'ANNEE_DEM' => $_SESSION['annee'],
);

foreach($_POST as $jour=>$type) {
    if (substr($type, 0, -4) == '-0.5') {
        $valeur['ID_TYABS'] = substr($type, 0, 4);
        $valeurs['NBH_DEM'] = 0.5;
    }
    else {
        $valeur['ID_TYABS'] = $type;
        $valeurs['NBH_DEM'] = 1;
    }
    $valeurs['JOUR_DEM'] = $jour;
    $query = "INSERT INTO demande_abs ('" . implode("', '", array_keys($valeurs)) . "')"
            . " VALUES ('"  . implode("', '", $valeurs) . "')";
    $GLOBALS['connexion']->query($query);
}
?>
