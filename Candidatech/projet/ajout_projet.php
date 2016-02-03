<?php

    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    function ajout_projet() {
        $champs = array(
            'CLIENT'        => 1,
            'NOM'           => 1,
            'CTF'           => 0,
            'CTC'           => 0,
            'DTDEBUT'       => 1,
            'DTFINPREVUE'   => 0,
            'NUMCMDE'       => 1,
            'NBJOURS'       => 0,
            'COL'           => 1,
            'DETAIL'        => 1,
            'SUIVIPAR'      => 1,
            'MODALITE'      => 0,
        );
        $champ2 = array(
            'MISNOM'        => 1,
            'MISNUMCMDE'    => 1,
            'MISDATECMDE'   => 1,
            'MISDTDEBUT'    => 1,
            'MISDTFIN'      => 1,
            'MISNBJOURS'    => 0,
            'MISFORFAIT'    => 0,
            'MISMONTFORFAIT'    => 0,
            'MISTJM'        => 0,
            'MISPA'         => 0,
            'MISCOMMENTAIRE'   => 0,
        );

        $vars = verif_champs($champs, 'PRO_');
        $var2 = verif_champs($champ2, '');
        if (is_array($vars)) {
            $vars['CLI_NO'] = $vars['PRO_CLIENT'];
            $vars['CTF_NO'] = $vars['PRO_CTF'];
            $vars['CTC_NO'] = $vars['PRO_CTC'];
            $vars['COL_NO'] = $vars['PRO_COL'];
            unset($vars['PRO_CLIENT'], $vars['PRO_CTF'], $vars['PRO_CTC'], $vars['PRO_COL']);
            $query = creer_insert($vars, 'PROJET');
            $GLOBALS['connexion']->query($query);
            $id = $GLOBALS['connexion']->insert_id;
            
            if ($id != null && $id > 0){
                $var2['PRO_NO'] = $id;
                $var2['MIS_ORDRE'] = 1;
                $var2['MIS_NOM'] = $var2['MISNOM'];
                $var2['MIS_NUMCMDE'] = $var2['MISNUMCMDE'];
                $var2['MIS_DATECMDE'] = $var2['MISDATECMDE'];
                $var2['MIS_DTDEBUT'] = $var2['MISDTDEBUT'];
                $var2['MIS_DTFIN'] = $var2['MISDTFIN'];
                $var2['MIS_NBJOURS'] = $var2['MISNBJOURS'];
                $var2['MIS_SUIVIPAR'] = $var2['MISSUIVIPAR'];
                $var2['MIS_FORFAIT'] = $var2['MISFORFAIT'];
                $var2['MIS_MONTFORFAIT'] = $var2['MISMONTFORFAIT'];
                $var2['MIS_TJM'] = $var2['MISTJM'];
                $var2['MIS_PA'] = $var2['MISPA'];
                $var2['MIS_COMMENTAIRE'] = $var2['MISCOMMENTAIRE'];
                unset($var2['MISNOM'], $var2['MISNUMCMDE'], $var2['MISDATECMDE'], $var2['MISDTDEBUT'], $var2['MISDTFIN'], $var2['MISNBJOURS']);
                unset($var2['MISSUIVIPAR'], $var2['MISFORFAIT'], $var2['MISMONTFORFAIT'], $var2['MISTJM'], $var2['MISPA'], $var2['MISCOMMENTAIRE']);
                $query = creer_insert($var2, 'MISSION');
                $GLOBALS['connexion']->query($query);
            }

            $url = str_replace("ajout", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $id . "&message=MAJOK";
            unset($_POST);
            $_POST['recherche'] = $id;
        }
        else {
            return $vars;
        }

        header('Location:' . $url);
    }
?>
