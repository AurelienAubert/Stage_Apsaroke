<?php

include_once "inc/connection.php";

/**
 * retourne la liste des noms et codes d'une table
 * @param string $table
 * @param string $prefixe le préfixe en 3 lettres pour les noms de colonne
 * @return array
 */
function donner_liste($table, $prefixe, $archive = 0, $id = 0) {
    try {
        $table = strtoupper($table);
        if ($table == 'LIBDOCUMENT') {
            $table = 'DOCUMENT';
        }
        $where = '';  
        
        if ($table == 'PROJET' || $table == 'CLIENT' || $table == 'FOURNISSEUR' || 
            $table == 'CONTACT_CLIENT'  || $table == 'CONTACT_FOURNISSEUR' || strpos($table, 'COLLABORATEUR') !== false){
            $where = $archive == 1 ? '' : ' WHERE ' . $prefixe . '_ARCHIVE=0';
        }
        if ($table == 'TYPE_AUTORISATION') {
            $nom = 'TAU_LIBELLE';
            $no = 'TAU_NO';
            $query = "SELECT TAU_NO, TAU_LIBELLE FROM TYPE_AUTORISATION ";
        } else if ($table == 'COMMERCIAL') {
            $nom = 'COL_NOM';
            $no = 'COL_NO';
            $query = "SELECT COL_NO, COL_NOM FROM COLLABORATEUR WHERE TAU_NO<4";
        } else if ($table == 'MODEREGLEMENT') {
            $nom = 'MOR_CODE';
            $no = 'MOR_NO';
            $query = "SELECT MOR_NO, MOR_CODE FROM MODEREGLEMENT ";
        } else if ($table == 'MISSION') {
            $nom = 'MIS_NOM';
            $no = 'MIS_NO';
            $where2 = $id > 0 ? 'WHERE PRO_NO IN (SELECT PRO_NO FROM MISSION WHERE MIS_NO=' . $id . ')' : '';
            $query = "SELECT MIS_NO, MIS_NOM FROM MISSION " . $where2;
        } else if (strpos($table, 'COLLABORATEUR') !== false && $table != 'COLLABORATEUR') {
            $table = strtoupper(substr($table, 14));
            $nom = 'COL_NOM';
            $no = 'COL_NO';
            $where = $archive == 1 ? '' : ' AND C.COL_ARCHIVE=0';
            $query = "SELECT C.COL_NO, C.COL_NOM FROM COLLABORATEUR C, " . $table . " A WHERE A.COL_NO=C.COL_NO" . $where;
        } else {
            $nom = $prefixe . '_NOM';
            $no = $prefixe . '_NO';
            $query = "SELECT " . $nom . ", " . $no . " FROM " . $table . $where;
        }
        $query .= " ORDER BY " . $nom;

        $result = $GLOBALS['connexion']->query($query);
        $retour = array();
        while ($row = $result->fetch_assoc()) {
            $retour[$row[$no]] = $row[$nom];
        }
        return $retour;
    } catch (Exception $e) {
// message en cas d"erreur 
        die('Erreur : ' . $e->getMessage());
    }
}

function donner_liste_conges($table_conges) {
    $table_conges = strtoupper($table_conges);
    $nom_conges = 'COL_NOM';
    $no_conges = 'COL_NO';
    $query_conges = "SELECT C.COL_NO, C.COL_NOM FROM COLLABORATEUR C, " . $table_conges . " A WHERE C.COL_NO != 0";

    $query_conges .= " ORDER BY " . $nom_conges;
    $result = $GLOBALS['connexion']->query($query_conges);
    $retour = array();
    while ($row = $result->fetch_assoc()) {
        $retour[$row[$no_conges]] = $row[$nom_conges];
    }
    return $retour;
}

?>
