<?php

    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_client($recherche) {
        $query = "SELECT * FROM CLIENT WHERE CLI_NO=".$recherche;
        $results = $GLOBALS['connexion']->query ($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            $_POST[substr($key, 4)] = $result;
        }
    }
    
    function update_client($recherche) {
        $champs = array(
            'CODE'        => 1,
            'NOM'         => 1,
            'ADRCOM_1'    => 1,
            'ADRCOM_2'    => 0,
            'CPCOM'       => 1,
            'VILLECOM'    => 1,
            'DTCREATION'  => 1,
            'NOMFAC'      => 0,
            'ADRFAC_1'    => 0,
            'ADRFAC_2'    => 0,
            'CPFAC'       => 0,
            'VILLEFAC'    => 0,
            'PAYS'        => 0,
            'CODE_FOUR'   => 0,
        );

        $vars = verif_champs($champs, 'CLI_');

        if (is_array($vars)) {
            $vars['CLI_LOGO'] = 'client/images_clients/' . $vars['CLI_LOGO'];
            $query = creer_update($vars, 'CLIENT', "CLI_NO=" . $recherche);
            $GLOBALS['connexion']->query($query);
        }
        else {
            return $vars;
        }
        return '';
    }
?>
