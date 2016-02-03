<?php
    include 'inc/connection.php';
    include 'inc/verif_parametres.php';
    include 'calendrier/fonction_dimanche_samedi.php';
    include 'calendrier/fonction_nbjoursMois.php';
    
    
    function cmp($a, $b) {
        $retour = strcmp($a['COL_NOM'], $b['COL_NOM']);
        if ($retour == 0) {
            return strcmp($a['COL_PRENOM'], $b['COL_PRENOM']);
        }
        else {
            return $retour;
        }
    }
    
    $nbJours = nbjoursMois($_POST['mois'], $_POST['annee']);
    $weekend = array();
    for ($i=1 ; $i<=$nbJours ; $i++) {
        $jour = check_jour($_POST['mois'], $i, $_POST['annee']);
        if ($jour==6 || $jour==0) {
            $weekend[] = $i;
        }
    }
    
    $query = "SELECT CO.COL_NO, CO.COL_MNEMONIC, COL_NOM, COL_PRENOM, CLI_NOM, PRO_NOM, SUM(RAM_NBH) AS NBH, M.MIS_TJM, P.PRO_NO
        FROM COLLABORATEUR CO, PROJET P, RAM R, CLIENT CL, MISSION M 
        WHERE CO.COL_NO = R.COL_NO
        AND R.PRO_NO = P.PRO_NO
        AND P.PRO_NO = M.PRO_NO AND M.MIS_ARCHIVE = 0 
        AND P.CLI_NO = CL.CLI_NO
        AND R.RAM_MOIS='" . $_POST['mois'] . "'
        AND R.RAM_ANNEE='" . $_POST['annee'] . "'
        AND R.RAM_JOUR NOT IN ('" . implode("', '", $weekend) . "')
        GROUP BY P.PRO_NO, CO.COL_NO
        ORDER BY COL_NO, PRO_NO";
    
    $query_we = "SELECT SUM(RAM_NBH) AS NBH_WE, PRO_NO, COL_NO
        FROM RAM
        WHERE RAM_MOIS='" . $_POST['mois'] . "'
        AND RAM_ANNEE='" . $_POST['annee'] . "'
        AND RAM_JOUR IN ('" . implode("', '", $weekend) . "')
        GROUP BY PRO_NO, COL_NO
        ORDER BY COL_NO, PRO_NO";
    
    $result = $GLOBALS['connexion']->query($query) or die(mysqli_error($GLOBALS['connexion']));
    $result_we = $GLOBALS['connexion']->query($query_we) or die(mysqli_error($GLOBALS['connexion']));
    $tableau = array(
        "Trigramme",
        "Collaborateur",
        "Client",
        "Projet",
        "J W",
        "WE W",
        "Taux journalier moyen",
        "Montant TTC"
    );
    $corps = array();
    $nbh_weekend = $result_we->fetch_assoc();
    
    while ($row = $result->fetch_assoc()) {
        while($nbh_weekend && ($nbh_weekend['COL_NO']<$row['COL_NO'] || ($nbh_weekend['COL_NO']==$row['COL_NO'] && $nbh_weekend['PRO_NO']<$row['PRO_NO']))) {
            $nbh_weekend = $result_we->fetch_assoc();
        }
        if ($nbh_weekend && ($nbh_weekend['COL_NO']==$row['COL_NO'] && $nbh_weekend['PRO_NO']==$row['PRO_NO'])) {
            $row['NBH_WE'] = $nbh_weekend['NBH_WE'];
        }
        else {
            $row['NBH_WE'] = 0;
        }
        $corps[] = array(
            'COL_MNEMONIC'  => $row['COL_MNEMONIC'],
            'COL_NOM'       => $row['COL_NOM'],
            'COL_PRENOM'    => $row['COL_PRENOM'],
            'CLI_NOM'       => $row['CLI_NOM'],
            'PRO_NOM'       => $row['PRO_NOM'],
            'NBH'           => $row['NBH'],
            'NBH_WE'        => $row['NBH_WE'],
            'MIS_TJM'       => $row['MIS_TJM'],
            'MONTANT'       => ($row['NBH'] * $row['MIS_TJM']),
        );
        $row['total'] = $row['NBH'] * $row['MIS_TJM'];
    }
    
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=export.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    echo '"' . implode('";"', $tableau) . '"' . "\n";
    usort($corps, 'cmp');
    foreach($corps as $ligne) {
        unset($ligne['COL_PRENOM']);
        echo '"' . implode('";"', $ligne) . '"' . "\n";
    }
?>