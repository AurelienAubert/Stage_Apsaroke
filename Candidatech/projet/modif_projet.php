<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_projet($recherche, $idmission = 0) {
        $query = "SELECT * FROM PROJET WHERE PRO_NO=" . $recherche;
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            switch ($key) {
                case 'CLI_NO':
                    $_POST['CLIENT'] = $result;
                    break;
                case 'CTF_NO':
                    $_POST['CTF'] = $result;
                    break;
                case 'CTC_NO':
                    $_POST['CTC'] = $result;
                    break;
                case 'COL_NO':
                    $_POST['COL'] = $result;
                    break;
                default:
                    $_POST[substr($key, 4)] = $result;
                    break;
            }
        }
        if (!isset($idmission) || $idmission == 0){
            $query = "SELECT MIS_NO FROM MISSION WHERE PRO_NO=" . $_POST['NO'] . " AND MIS_ORDRE IN (SELECT MAX(MIS_ORDRE) FROM MISSION WHERE PRO_NO=" . $_POST['NO'] .")";
            $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
            $idmission = $results['MIS_NO'];
        }
        $query = "SELECT * FROM MISSION WHERE MIS_NO=" . $idmission;
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();

        foreach($results as $key => $result) {
            switch ($key) {
                case 'PRO_NO':
                    $_POST['PRONO'] = $result;
                    break;
                default:
                    $_POST[substr($key, 0, 3) . substr($key, 4)] = $result;
                    break;
            }
        }

        // Test si nouvelle mission demandée
        if (isset($_GET['action'])){
            $action = $_GET['action'];
        }
        if ($action == 'creer'){
            $idmission = 0;
            $_POST['MISORDRE'] = $_POST['MISORDRE'] + 1;
            $_POST['MISNO'] = 0;
            $_POST['MISDTDEBUT'] = 0;
            $_POST['MISDTFIN'] = 0;
            $_POST['MISNSEQUENTIEL'] = '';
            $_POST['MISCOMMENTAIRE'] = '';
            $_POST['idmission'] = 0;
            $_POST['action'] = $action;
        }else{
        }
    }

    function update_projet($recherche) {
        $champs = array(
            'CLIENT'        => 1,
            'NOM'           => 1,
            'CTF'           => 0,
            'CTC'           => 0,
            'DTDEBUT'       => 1,
            'DTFINPREVUE'   => 0,
            'NUMCMDE'       => 1,
            'NBJOURS'       => 0,
            'COL'           => 0,
            'DETAIL'        => 1,
            'SUIVIPAR'      => 1,
            'MODALITE'      => 0,
            'ARCHIVE'       => 0,
            'DTCLOTURE'     => 0,
            'NO'            => 0,
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
            'MISNO'         => 0,
            'MISCOMMENTAIRE'   => 0,
            'MISORDRE'      => 0,
            'PRONO'         => 0,
        );

        $vars = verif_champs($champs, 'PRO_');
        if (is_array($vars)) {
            $vars['CLI_NO'] = $vars['PRO_CLIENT'];
            $vars['CTF_NO'] = $vars['PRO_CTF'];
            $vars['CTC_NO'] = $vars['PRO_CTC'];
            $vars['COL_NO'] = $vars['PRO_COL'];
            unset($vars['PRO_CLIENT'], $vars['PRO_CTF'], $vars['PRO_CTC'], $vars['PRO_COL']);
            $query = creer_update($vars, 'PROJET', 'PRO_NO=' . $recherche);
            $GLOBALS['connexion']->query($query);
            
            $var2 = verif_champs($champ2, '');
            if (is_array($var2)) {
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
                $var2['PRO_NO'] = $var2['PRONO'];
                $var2['MIS_ORDRE'] = $var2['MISORDRE'];
                $idmission = $var2['MISNO'];
                unset($var2['MISNO'], $var2['PRONO'], $var2['MISORDRE'], $var2['MISNOM'], $var2['MISNUMCMDE'], $var2['MISDATECMDE'], $var2['MISDTDEBUT'], $var2['MISDTFIN'], $var2['MISNBJOURS']);
                unset($var2['MISSUIVIPAR'], $var2['MISFORFAIT'], $var2['MISMONTFORFAIT'], $var2['MISTJM'], $var2['MISPA'], $var2['MISCOMMENTAIRE']);
                if ($idmission > 0){
                    $query = creer_update($var2, 'MISSION', "MIS_NO=" . $idmission);
                }else{
                    $var2['PRO_NO'] = $recherche;
                    $query = creer_insert($var2, 'MISSION');
                }
                $GLOBALS['connexion']->query($query);

                $url = str_replace("modification", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $recherche . "&message=MAJOK";
                unset($_POST);
                $_POST['recherche'] = $recherche;
                
            }
            else {
                return $var2;
            }
        }
        else {
            return $vars;
        }
        
        //header('Location:' . $url);
?>
<script>
document.location.href = '<?php echo $url; ?>';
</script>
<?php
    }
?>
