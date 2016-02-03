<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_libdocument($recherche) {
        $query = "SELECT L.*, D.DOC_NOM FROM LIBDOCUMENT L, DOCUMENT D WHERE L.DOC_NO=D.DOC_NO AND L.LDO_NO=".$recherche;
        $results = $GLOBALS['connexion']->query ($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            switch ($key) {
                case 'DOC_NO':
                    $_POST['DOCNO'] = $result;
                    break;
                case 'DOC_NOM':
                    $_POST['DOCNOM'] = $result;
                    break;
                case 'LDO_NOM':
                    $_POST['NOMD'] = $result;
                    break;
                case 'COL_NO':
                    $_POST['COL'] = $result;
                    break;
                default:
                    $_POST[substr($key, 4)] = $result;
                    break;
            }
        }
    }
    function update_libdocument($recherche) {
        $champs = array(
            'DOCNO'      => 1,
            'ORDRE'      => 1,
            'NOMD'       => 1,
            'CONTENU'    => 1,
            'COL'        => 0,
        );

        $vars = verif_champs($champs, 'LDO_');

        if (is_array($vars)) {
            $vars['DOC_NO'] = $vars['LDO_DOCNO'];
            $vars['LDO_NOM'] = $vars['LDO_NOMD'];
            $vars['COL_NO'] = $vars['LDO_COL'];
            unset($vars['LDO_DOCNO'], $vars['LDO_COL'], $vars['LDO_NOMD']);
            $query = creer_update($vars, 'LIBDOCUMENT', "LDO_NO=" . $recherche);
            $GLOBALS['connexion']->query($query);
            $url = str_replace("modifier", "recherche", $_POST['urlRetourMAJ']) . "?iddoc=" . $vars['DOC_NO'] . "&message=MAJOK";
            unset($_POST);
            $_POST['recherche'] = $recherche;
        }
        else {
            return $vars;
        }
        header('Location:' . $url);
    }
?>
