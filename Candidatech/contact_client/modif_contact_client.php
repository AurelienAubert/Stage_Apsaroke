<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_contact_client($recherche) {
        $query = "SELECT * FROM CONTACT_CLIENT WHERE CTC_NO='" . $recherche . "'";
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            switch ($key) {
                case 'CLI_NO':
                    $_POST['CLIENT'] = $result;
                    break;
                case 'FCT_NO':
                    $_POST['FONCTION'] = $result;
                    break;
                default:
                    $_POST[substr($key, 4)] = $result;
                    break;
            }
        }
    }
    
    function update_contact_client($recherche) {
        $champs = array(
            'CLIENT'        => 1,
            'NOM'           => 1,
            'PRENOM'        => 1,
            'EMAIL'         => 1,
            'PRT'           => 1,
            'FONCTION'      => 1,
            'COMMENTAIRE'   => 0,
            'ARCHIVE'       => 0,
        );

        $vars = verif_champs($champs, 'CTC_');
        if (is_array($vars)) {
            $vars['CLI_NO'] = $vars['CTC_CLIENT'];
            $vars['FCT_NO'] = $vars['CTC_FONCTION'];
            unset ($vars['CTC_CLIENT'], $vars['CTC_FONCTION']);
            $query = creer_update($vars, 'CONTACT_CLIENT', "CTC_NO=" . $recherche);
            $GLOBALS['connexion']->query($query);
            $url = str_replace("modification", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $recherche . "&message=MAJOK";
            unset($_POST);
            $_POST['recherche'] = $recherche;
        }
        else {
            return $vars;
        }
        header('Location:' . $url);
    }
?>