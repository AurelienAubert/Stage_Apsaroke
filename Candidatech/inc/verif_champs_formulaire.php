<?php

    include_once('inc/regex.php');
    /**
     * effectue la vérifications des champs transmis en POST
     * 
     * @param   array       $champs         le tableau contenant les champs sous la forme champs=>obligatoire
     * @param   string      $prefixe_champ  un prefixe à ajouter aux noms des champs
     * @return  string|array                si champs corrects : tableau champs=>valeur
     *                                      sinon chaîne de message pour toutes les erreurs
     */
    function verif_champs($champs, $prefixe_champ="") {
        $vars = array();
        $erreur = '';
		
        foreach ($champs as $champ => $requis) {
            $bon = isset($_POST[$champ]) && !($requis && empty($_POST[$champ]));
            
            if ($bon) {
                $val = htmlspecialchars(addslashes(trim($_POST[$champ])), ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
                
                if (isset($GLOBALS['regex_champs'][$champ])) {
                    $bon &= preg_match($GLOBALS['regex_array'][$GLOBALS['regex_champs'][$champ]], $val);
                }
                
                $bon |= !($requis && empty($_POST[$champ]));
                
                if ($bon) {
				
                    $vars[$prefixe_champ . $champ] = $val;
                }
            }
            if (!$bon) {
                $erreur .= $champ . ", ";
                $bon = false;
            }
        }
        if (empty($erreur)) {
            return $vars;
        }
        else {
            return 'Les champs ' . substr($erreur, 0, -2) . " ont été mal remplis";
        }
    }
    
    /**
     * Génère une requête d'insertion pour la table spécificiée avec les valeurs données
     * 
     * @param array $vars
     * @param string $table
     * @return string
     */
    function creer_insert($vars, $table) {
        $noms_colonnes = ' (';
        $valeurs = '(';
        foreach ($vars as $champ=>$var) {
            $noms_colonnes .= $champ . ', ';
            if ($var == 'NULL') {
                $valeurs .= 'NULL, ';
            }
            else {
                $valeurs .= "'" . $var . "', ";
            }
        }
        return "INSERT INTO " . $table . substr($noms_colonnes,0, -2) . ') VALUES ' . substr($valeurs,0,-2) . ')';
    }
    
    /**
     * Génère une requête d'update pour une table donnée
     * @param array $vars
     * @param string $table
     * @param string $where
     * @return string
     */
    function creer_update($vars, $table, $where) {
        $retour = "UPDATE " . $table . " SET ";
        foreach ($vars as $champ => $var) {
            if ($var == 'NULL') {
                $retour .= $champ . "=" . $var . ", ";
            }
            else {
                $retour .= $champ . "='" . $var . "', ";
            }
        }
        return substr($retour, 0, -2) . " WHERE " . $where;
    }
    
?>
