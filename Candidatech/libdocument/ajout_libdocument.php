<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_libdocument($recherche) {
        $qry = "SELECT MAX(LDO_ORDRE) AS ORDRE FROM LIBDOCUMENT WHERE DOC_NO=" . $recherche;
        $rlt = $GLOBALS['connexion']->query ($qry);
        if (mysqli_num_rows ($rlt) >= 1){
            $ordre = $rlt->fetch_assoc()['ORDRE'];
        }else{
            $ordre = 0;
        }
        $query = "SELECT * FROM DOCUMENT WHERE DOC_NO=" . $recherche;
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
        //$_POST['ORDRE'] = $rlt['ORDRE'] + 1;
        $_POST['ORDRE'] = $ordre + 1;
        $_POST['COL'] = 0;
    }
    function ajout_libdocument() {
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
            $query = creer_insert($vars, 'LIBDOCUMENT');
            $GLOBALS['connexion']->query($query);
            $id = $GLOBALS['connexion']->insert_id;
            $url = str_replace("ajout", "recherche", $_POST['urlRetourMAJ']) . "?iddoc=" . $vars['DOC_NO'] . "&message=MAJOK";
            unset($_POST);
            $_POST['recherche'] = $id;
        }
        else {
            return $vars;
        }
        header('Location:' . $url);
    }
?>
