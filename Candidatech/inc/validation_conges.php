<?php include_once 'connection.php'; ?>
<?php include_once 'calendrier/fonction_nbjoursMois.php'; ?>
<?php include_once 'calendrier/fonction_annee_bissextile.php'; ?>
<?php

    /*
     * récupération de la liste des types d'absences
     */
    $query = "SELECT ID_TYABS, NOM_TYABS FROM type_abs";
    $result = $GLOBALS['connexion']->query($query);

    $types = array();
    while ($row = $result->fetch_assoc()) {
        $types[str_replace(' ', '', $row['NOM_TYABS'])] = $row['ID_TYABS'];
    }
    
    /*
     * suppression des anciens enregistremets
     */
    $query = "DELETE FROM demande_abs WHERE ANNEE_DEM = '" . $_POST['annee'] . "' AND MOIS_DEM = '" . $_POST['mois'] . "' AND ID_COL = '" . $_SESSION['col_id'] . "'";
    $GLOBALS['connexion']->query($query);
    /*
     * ajout des enregistrements
     */
    $query = "INSERT INTO demande_abs (ID_COL, ID_TYABS, JOUR_DEM, MOIS_DEM, ANNEE_DEM, NBH_DEM) VALUES ";
    $vars = array(
        'ID_COL'    => $_SESSION['col_id'],
        'ID_TYABS'  => '',
        'JOUR_DEM'  => '',
        'MOIS_DEM'  => $_POST['mois'],
        'ANNEE_DEM' => $_POST['annee'],
        'NBH_DEM'   => '',
    );
    $nbJours = nbjoursMois($_POST['mois'], $_POST['annee']);
    for ($i=1 ; $i<=$nbJours ; $i++) {
        if (isset($_POST[$i])) {
            $vars['JOUR_DEM'] = $i;
            $vars['NBH_DEM'] = 1;
            
            $classe = explode('-', $_POST[$i]);
            if (count($classe)==2) {
                $vars['NBH_DEM'] = 0.5;
            }
            $vars['ID_TYABS'] = $types[$classe[0]];
            $query .= "('" . implode("', '", $vars) . "'), ";
        }
    }
    $GLOBALS['connexion']->query(substr($query, 0, -2));
    
    echo "Votre demande a été prise en compte";
?>