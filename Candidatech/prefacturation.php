<?php
function bouton($texte, $classe, $valeur, $classeCouleur) {
    return '<input type="button" id="'.$valeur.'" class="btn ' . $classeCouleur . ' ' . $classe . '" value="' . $texte . '" onclick="facture_pro_format('.$valeur.')"/>';
}

/*Prefacturation
 * 1    |   CREPU   | 23:06/2014    | Modification des colonnes
 * 
 * 
 * 
 * 
 * 
 * 
 */

require_once 'inc/verif_session.php';
include ('calendrier/fonction_dimanche_samedi.php');
include ('calendrier/fonction_nomMois.php');


if($_GET['type'] == 'prefacturation'){
    $GLOBALS['retour_page'] = 'prefacturation.php?mois=' . $_POST['mois'] . '&annee=' . $_POST['annee'];
    include 'facture/modification_facture.php';
}
else{

include 'calendrier/fonction_mois.php';
include ('calendrier/fonction_nbjoursMois.php');
include ('calendrier/jours_feries.php');
include ('inc/connection.php');

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php' ?>
        <title>Préfacturation</title>   
        <style>
            td span
            {
                display: none;
                font-size: 10px;
            }
            td:active span
            {
                font-size: 10px;
                display: inline;
                position: absolute;
                text-decoration: none;
                background-color: #ffffff;
                border-radius: 6px;
            }
        </style>
    </head>
    <?php 
        echo '<body>';
        $mois = $_POST['mois'];
        $annee = $_POST['annee'];
        if (!isset($_POST['mois']) && $_GET['mois']){
            $mois = $_GET['mois'];
        }
        if (!isset($_POST['annee']) && $_GET['annee']){
            $annee = $_GET['annee'];
        }
        $date_depart = strtotime($annee . "-" . $mois . "-01");
        $date_arrive = strtotime($annee . "-" . $mois . "-31");

        $samedi = 0;
        $dimanche = 0;
        $ferie1 = 0;
        $tab_samedi = array();
        $tab_dimanche = array();
        $tab_ferie = array();
        
        $tab_we = array();
        $nontrav = array();

        $nbjoursMois = nbjoursMois($mois, $annee); //connaitre le nb de jours dans le mois
        $jour = jour_semaine($mois, 1, $annee);
        $feries = getFeries($date_depart, $date_arrive);

        for ($i = 1; $i <= $nbjoursMois; $i++) {
            switch (true) {
                case in_array(mktime(0, 0, 0, $mois, $i, $annee), $feries):
                    $ferie1++;
                    $tab_ferie[] = $i;
                    array_push($tab_we, $i);
                    array_push($nontrav, $i);
                    break;
                case ($jour == 6):
                    $samedi++;
                    $tab_samedi [] = $i;
                    array_push($tab_we, $i);
                    array_push($nontrav, $i);
                    break;
                case ($jour == 7):
                    $dimanche++;
                    $tab_dimanche [] = $i;
                    array_push($tab_we, $i);
                    array_push($nontrav, $i);
                    break;
            }
            if ($jour == 7) {
                $jour = 0;
            }
            $jour++;
        }

        $jourouvre = $nbjoursMois - $ferie1 - $dimanche - $samedi;

        $GLOBALS['titre_page'] = '<div class="adm">Pré Facturation ' . nomMois($mois) . ' ' . $annee . '</div>';
        $GLOBALS['retour_page'] = 'chx_date.php?type=prefacturation';
        include ("menu/menu_global.php");
        ?>
        <div style = 'margin-left:2em; text-align: center;' class=''>
            <?php
            echo '<br>';
            echo '<b>';
            echo 'Pré Facturation - ';
            echo nomMois($mois);
            echo ' ';
            echo $annee;
            echo '</b>';
            echo '<br>';
            echo 'Jours ouvr&eacute;s : ' . $jourouvre . '.';
            echo '<br>';
            echo '<br>';

            $totalTJM_int = '0';
            $totalprefacturation_int = '0';

            $query = "SELECT P.PRO_NO, C.COL_NO, C.COL_MNEMONIC, C.COL_NOM, C.COL_PRENOM, I.INT_FACTURABLE, SUM(R.RAM_NBH) AS JW, R.RAM_CLIENT, P.PRO_NOM, M.MIS_TJM 
                            FROM RAM R, COLLABORATEUR C, PROJET P, INTERNE I, MISSION M 
                            WHERE I.COL_NO = C.COL_NO
                            AND C.COL_NO = R.COL_NO
                            AND R.PRO_NO = P.PRO_NO
                            AND P.PRO_NO = M.PRO_NO AND M.MIS_ARCHIVE = 0 
                            AND R.RAM_MOIS = '" . $mois . "'
                            AND R.RAM_ANNEE = '" . $annee . "'
                            AND P.PRO_NO NOT IN 
                                (SELECT PRO_NO FROM FACTURE 
                                    WHERE FAC_MOIS='" . $mois . "' AND FAC_ANNEE='" . $annee . "' 
                                    AND COL_NO=C.COL_NO AND CLI_NO=P.CLI_NO)
                            GROUP BY C.COL_NO, P.PRO_NO 
                            ORDER BY C.COL_NOM, C.COL_PRENOM";

            $rq_we = "SELECT SUM(RAM_NBH) AS WE, PRO_NO
                            FROM RAM 
                            WHERE RAM_MOIS = '" . $mois . "'
                            AND RAM_ANNEE = '" . $annee . "'";

            $rq_abs = "SELECT SUM(ABS_NBH) AS ABS
                            FROM ABSENCE
                            WHERE ABS_MOIS = '" . $mois . "'
                            AND ABS_ANNEE = '" . $annee . "'";

            $result = $GLOBALS['connexion']->query($query);

            $tableau = array();
            
            $numrow = 3;
            
            while ($row = $result->fetch_assoc()) {
                $totalTJM_int += $row['MIS_TJM'];

                $query_we = $rq_we . ' AND COL_NO=' . $row['COL_NO'] . ' AND PRO_NO =' . $row['PRO_NO'] . ' AND RAM_JOUR IN (' . implode(', ', $tab_we) . ');';
                $query_abs = $rq_abs . ' AND COL_NO=' . $row['COL_NO'] . ';';

                
                $result_we = $GLOBALS['connexion']->query($query_we);
                
                $row_we = mysqli_fetch_assoc($result_we);

                $result_abs = $GLOBALS['connexion']->query($query_abs);
                $row_abs = mysqli_fetch_assoc($result_abs);

                if ($row_we['WE'] == null) {
                    $row_we['WE'] = 0;
                }

                if ($row_abs['ABS'] == null) {
                    $row_abs['ABS'] = 0;
                }
                
                $query_working = 'SELECT RAM_JOUR, RAM_NBH FROM RAM WHERE COL_NO =  '.$row['COL_NO'].' AND RAM_MOIS = '.$mois.' AND RAM_ANNEE = '.$annee.' AND PRO_NO = '.$row['PRO_NO'].' AND RAM_JOUR IN (';
                
                ///////////////////////////////////////////////////////////////////////
                //Dimanche travaillé
                //Requête récupérant les jours travaillés le dimanche
                
                
                $query_working_sunday = $query_working.implode(', ', $tab_dimanche) . ');';
                
                $stmtDimanche = $GLOBALS['connexion']->query($query_working_sunday);
                $dimanche_travaille = array();
                
                while($ligne = $stmtDimanche->fetch_assoc())
                {
                    //Cas : Demi-journée
                    if($ligne['RAM_NBH'] == 0.5)
                    {
                        $dimanche_travaille [] = '<b><i>&shy ' . $ligne['RAM_JOUR'] . 'AM</b></i>';
                    }
                    //Journée pleine
                    else
                    {
                        $dimanche_travaille [] = $ligne['RAM_JOUR'];
                    }
                }
               //$dimanche_travaille = substr($dimanche_travaille,0,-1);
                
                ///////////////////////////////////////////////////////////////////////
                //Samedi travaillé
                
                $query_working_saturday = $query_working.implode(', ', $tab_samedi) . ');';
               
                
                $stmtSamedi = $GLOBALS['connexion']->query($query_working_saturday);
                $samedi_travaille = array();
                
                while($ligne = $stmtSamedi->fetch_assoc())
                {
                    //Cas : Demi-journée
                    if($ligne['RAM_NBH'] == 0.5)
                    {
                        $samedi_travaille [] = '<b><i>&shy ' . $ligne['RAM_JOUR'] . 'AM &shy</b></i>';
                    }
                    //Journée pleine
                    else
                    {
                        $samedi_travaille [] = $ligne['RAM_JOUR'];
                    }
                }
                
                ///////////////////////////////////////////////////////////////////////
                //Jour férié travaillé
                //Requête récupérant les jours travaillés les jours fériés
                $ferie = '';
                if(!empty($tab_ferie))
                {
                    $query_working_public_holiday = $query_working.implode(', ',$tab_ferie).');';
                    
                    $stmtFerie = $GLOBALS['connexion']->query($query_working_public_holiday);
                    $ferie_travaille = array();
                    
                    while($ligne = $stmtFerie->fetch_assoc())
                    {
                        //Cas : Demi-journée
                        if($ligne['RAM_NBH'] == 0.5)
                        {
                            $ferie_travaille [] = '<b><i>&shy ' . $ligne['RAM_JOUR'] . 'AM &shy</b></i>';
                        }
                        //Journée pleine
                        else
                        {
                            $ferie_travaille [] = $ligne['RAM_JOUR'];
                        }
                    }
                }
               
                
                $nbDimanche =0;
                
                if(!empty($dimanche_travaille))
                {
                    foreach($dimanche_travaille as $dimanche)
                    {
                        if(strpos($dimanche, 'AM'))
                        {
                            $nbDimanche += 0.5;
                        }
                        else
                        {
                            $nbDimanche++;
                        }
                    }
                }
                
                $nbSamedi =0;
                if(!empty($samedi_travaille))
                {
                    foreach($samedi_travaille as $samedi)
                    {
                        if(strpos($samedi, 'AM'))
                        {
                            $nbSamedi += 0.5;
                        }
                        else
                        {
                            $nbSamedi++;
                        }
                    }
                }
                
                $nbFerie =0;
                if(!empty($ferie_travaille))
                {
                    foreach($ferie_travaille as $ferie)
                    {
                        if(strpos($ferie, 'AM'))
                        {
                            $nbFerie += 0.5;
                        }
                        else
                        {
                            $nbFerie++;
                        }
                    }
                }
                
                $prefacturation =  $row['MIS_TJM'] * ($row['JW'] - $row_we['WE']);
                $prefacturation += $nbSamedi * $row['MIS_TJM'] * 1.5;
                $prefacturation += ($nbDimanche + $nbFerie) * $row['MIS_TJM'] * 2; 
                
                
                $totalprefacturation_int += $prefacturation;
                
                $valeur = $row['COL_NO'] . '.' . $row['PRO_NO'];
                $t = 'tableau1';
                
                $tableau[] = array(
                    'COL_NO' => $valeur,
                    'COL_NOM' => $row['COL_NOM'] . ' ' . $row['COL_PRENOM'].' &shy',
                    'INT_FACTURABLE' => ($row['INT_FACTURABLE'] == 1 ? 'Oui &shy' : 'Non &shy'),
                    'COL_JW' => $row['JW'] . ' &shy',
                    'COL_ABS' => $row_abs['ABS'] . ' &shy',
                    'COL_WE' => $row_we['WE'] . ' &shy',
                    'RAM_SAMEDI' => !empty($samedi_travaille) ? implode($samedi_travaille, ',') : ' &shy',
                    'RAM_DIMANCHE' => !empty($dimanche_travaille) ? implode($dimanche_travaille, ',') : ' &shy',
                    'RAM_FERIE' => !empty($ferie_travaille) ? implode($ferie_travaille, ',') : ' &shy',
                    'RAM_CLIENT' => $row['RAM_CLIENT'].' &shy',
                    'PRO_NOM' => $row['PRO_NOM'] . ' &shy',
                    'MIS_TJM' => $row['MIS_TJM'] . ' &shy',
                    'PRO_PREFAC' => $prefacturation,
                    'BOUTON' => bouton('Générer', 'valider', $valeur, 'btn-primary'),
                    'MNEMONIC' => $row['COL_MNEMONIC']
                );
                
                $numrow++;
            }
                
            
            $table = '<table id="tableau1" border="1" class="table-bordered table-condensed" width="90%" align="center">
                        <tr>
                            <td colspan="10"></td>
                            <td colspan="2">EURO HT</td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <th>Collaborateurs internes</th>
                            <th>Facturable</th>
                            <th>JW</th>
                            <th>ABS</th>
                            <th>WE</th>
                            <th>Samedi</th>
                            <th>Dimanche</th>
                            <th>Férié(s)</th>
                            <th>Clients</th>
                            <th>Projets</th>
                            <th>TJM</th>
                            <th>Préfacturation</th>
                            <th>Générer la facture pro-format</th>
                        </tr>';
            echo $table;
            //On remplace le mot "internes" par externes pour le deuxième tableau
            $table = str_replace('internes', 'externes', $table);
            //On change l'id
            $table = str_replace('tableau1', 'tableau2', $table);
            
            
            foreach ($tableau as $ligne) {
                echo '<tr id=' . $ligne['COL_NO'] . '>';
                echo '<td id="COL_NOM' . $ligne['COL_NO'] . '">' . $ligne['COL_NOM'] . '</td>';
                echo '<td id="INT_FACTURABLE' . $ligne['COL_NO'] . '">' . $ligne['INT_FACTURABLE'] . '</td>';
                echo '<td id="COL_JW' . $ligne['COL_NO'] . '">' . $ligne['COL_JW'] . '</td>';
                echo '<td id="COL_ABS' . $ligne['COL_NO'] . '">' . $ligne['COL_ABS'] . '</td>';
                echo '<td id="COL_WE' . $ligne['COL_NO'] . '">' . $ligne['COL_WE'] . '</td>';
                echo '<td id="RAM_SAMEDI' . $ligne['COL_NO'] . '">' . $ligne['RAM_SAMEDI'] . '</td>';
                echo '<td id="RAM_DIMANCHE' . $ligne['COL_NO'] . '">' . $ligne['RAM_DIMANCHE'] . '</td>';
                echo '<td id="RAM_FERIE' . $ligne['COL_NO'] . '">' . $ligne['RAM_FERIE'].'</td>';
                echo '<td id="RAM_CLIENT' . $ligne['COL_NO'] . '">' . $ligne['RAM_CLIENT'] . '</td>';
                echo '<td id="PRO_NOM' . $ligne['COL_NO'] . '">' . $ligne['PRO_NOM'] . '</td>';
                echo '<td id="MIS_TJM' . $ligne['COL_NO'] . '">' . $ligne['MIS_TJM'] . '</td>';
                echo '<td id="PRO_PREFAC' . $ligne['COL_NO'] . '">' . $ligne['PRO_PREFAC'] . '</td>';
                echo '<td colspan="3" id="BOUTON' . $ligne['COL_NO'] . '">' . $ligne['BOUTON'] . '';
                echo '<input id="MNEMONIC' . $ligne['COL_NO'] . '" type="hidden" value="' . $ligne['MNEMONIC'] . '" />';
                echo '<input id="COL_NO' . $ligne['COL_NO'] . '" type="hidden" value="' . $ligne['COL_NO'] . '" />';
                echo '</tr>';
            }

            echo '<tr>
                     <td colspan = "10"><font color = "red">TOTAL/JOUR &euro;.HT</font</td>
                     <td>' . $totalTJM_int . '<span>Total TJM</span></td>
                     <td>' . $totalprefacturation_int . '<span>Total des préfacturations </span></td>
                     <td></td>
                  </tr></table>';
            echo '<br><br><br><br>';

            $totalTJM_ext = '0';
            $totalprefacturation_ext = '0';
            $totalPA_ext = '0';

            $query_ext = "SELECT P.PRO_NO, C.COL_NO, C.COL_MNEMONIC, C.COL_NOM, C.COL_PRENOM, SUM(R.RAM_NBH) AS JW, R.RAM_CLIENT, P.PRO_NOM, M.MIS_TJM, M.MIS_PA
                            FROM RAM R, COLLABORATEUR C, PROJET P, EXTERNE E, MISSION M 
                            WHERE E.COL_NO = C.COL_NO
                            AND C.COL_NO = R.COL_NO
                            AND R.PRO_NO = P.PRO_NO
                            AND P.PRO_NO = M.PRO_NO AND M.MIS_ARCHIVE = 0 
                            AND P.PRO_NO NOT IN 
                                (SELECT PRO_NO FROM FACTURE 
                                    WHERE FAC_MOIS='" . $mois . "' AND FAC_ANNEE='" . $annee . "' 
                                    AND COL_NO=C.COL_NO AND CLI_NO=P.CLI_NO)
                            AND R.RAM_MOIS = '" . $mois . "'
                            AND R.RAM_ANNEE = '" . $annee . "'
                            GROUP BY C.COL_NO, P.PRO_NO 
                            ORDER BY C.COL_NOM, C.COL_PRENOM";

            $rq_we_ext = "SELECT SUM(RAM_NBH) AS WE, PRO_NO
                            FROM RAM 
                            WHERE RAM_MOIS = '" . $mois . "' AND RAM_ANNEE = '" . $annee . "'";

            $rq_abs_ext = "SELECT SUM(ABS_NBH) AS ABS
                              FROM ABSENCE
                              WHERE ABS_MOIS = '" . $mois . "' AND ABS_ANNEE = '" . $annee . "'";

            $result_ext = $GLOBALS['connexion']->query($query_ext);

            $tableau_ext = array();

            while ($row_ext = $result_ext->fetch_assoc()) {
                $totalTJM_ext += $row_ext['MIS_TJM'];
                $totalprefacturation_ext += ($row_ext['JW'] * $row_ext['MIS_TJM']);
                $totalPA_ext += $row_ext['MIS_PA'];

                $query_we_ext = $rq_we_ext . ' AND COL_NO=' . $row_ext['COL_NO'] . ' AND PRO_NO =' . $row_ext['PRO_NO'] . ' AND RAM_JOUR IN (' . implode(', ', $tab_we) . ');';
                $query_abs_ext = $rq_abs_ext . ' AND COL_NO=' . $row_ext['COL_NO'] . ';';

                $result_we_ext = $GLOBALS['connexion']->query($query_we_ext);
                $row_we_ext = mysqli_fetch_assoc($result_we_ext);

                $result_abs_ext = $GLOBALS['connexion']->query($query_abs_ext);
                $row_abs_ext = mysqli_fetch_assoc($result_abs_ext);

                if ($row_we_ext['WE'] == null) {
                    $row_we_ext['WE'] = 0;
                }

                if ($row_abs_ext['ABS'] == null) {
                    $row_abs_ext['ABS'] = 0;
                }
                
                $prefacturation_ext =  $row_ext['MIS_TJM'] * ($row_ext['JW'] - $row_we_ext['WE']);
                $prefacturation_ext += $nbSamedi * $row_ext['MIS_TJM'] * 1.5;
                $prefacturation_ext += ($nbDimanche+$nbFerie) * $row_ext['MIS_TJM'] * 2; 
                
                $valeur_ext = $row_ext['COL_NO'] . '.' . $row_ext['PRO_NO'];
                
                $tableau_ext[] = array(
                    'COL_NO' => $valeur_ext,
                    'COL_NOM' => $row_ext['COL_NOM'] . ' ' . $row_ext['COL_PRENOM'].' &shy',
                    'INT_FACTURABLE' => ('Oui'),
                    'COL_JW' => $row_ext['JW'],
                    'COL_ABS' => $row_abs_ext['ABS'],
                    'COL_WE' => $row_we_ext['WE'],
                    'RAM_SAMEDI' => !empty($samedi_travaille) ? implode($samedi_travaille,',') : ' &shy',
                    'RAM_DIMANCHE' => !empty($dimanche_travaille) ? implode($dimanche_travaille,',') : ' &shy',
                    'RAM_FERIE' => !empty($ferie_travaille) ? implode($ferie_travaille,',') : ' &shy',
                    'RAM_CLIENT' => $row_ext['RAM_CLIENT'],
                    'PRO_NOM' => $row_ext['PRO_NOM'],
                    'MIS_TJM' => $row_ext['MIS_TJM'],
                    'PRO_PREFAC' => $row_ext['JW'] * $row_ext['MIS_TJM'],
                    'BOUTON' => bouton('Générer', 'valider', $valeur_ext, 'btn-primary'),
                    'MNEMONIC' => $row_ext['COL_MNEMONIC']);
            }

            echo $table;

            foreach ($tableau_ext as $ligne_ext) {
                echo '<tr id=' . $ligne_ext['COL_NO'] . '>';
                echo '<td id="COL_NOM' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['COL_NOM'] . '</td>';
                echo '<td id="INT_FACTURABLE' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['INT_FACTURABLE'] . '</td>';
                echo '<td id="COL_JW' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['COL_JW'] . '</td>';
                echo '<td id="COL_ABS' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['COL_ABS'] . '</td>';
                echo '<td id="COL_WE' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['COL_WE'] . '</td>';
                echo '<td id="RAM_SAMEDI' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['RAM_SAMEDI'] . '</td>';
                echo '<td id="RAM_DIMANCHE' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['RAM_DIMANCHE'] . '</td>';
                echo '<td id="RAM_FERIE' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['RAM_FERIE'] . '</td>';
                echo '<td id="RAM_CLIENT' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['RAM_CLIENT'] . '</td>';
                echo '<td id="PRO_NOM' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['PRO_NOM'] . '</td>';
                echo '<td id="MIS_TJM' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['MIS_TJM'] . '</td>';
                echo '<td id="PRO_PREFAC' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['PRO_PREFAC'] . '</td>';
                echo '<td colspan="3" id="BOUTON' . $ligne_ext['COL_NO'] . '">' . $ligne_ext['BOUTON'] . '';
                echo '<input id="MNEMONIC' . $ligne_ext['COL_NO'] . '" type="hidden" value="' . $ligne_ext['MNEMONIC'] . '" />';
                echo '<input id="COL_NO' . $ligne_ext['COL_NO'] . '" type="hidden" value="' . $ligne_ext['COL_NO'] . '" />';
                echo '</tr>';
            }
            echo '<tr>
                    <td colspan = "10"><font color = "red">TOTAL/JOUR &euro;.HT</font</td>
                    <td>' . $totalTJM_ext . '<span>Total TJM</span></td>
                    <td>' . $totalprefacturation_ext . '<span>Total des préfacturations </span></td>
                    <td></td>
                  </tr></table>';
            //echo '<br><br><br><br>';
?>
        </div>
        <script type="text/javascript">
            function facture_pro_format(id)
            {
                var col_nom = document.getElementById('COL_NOM'+id).textContent;
                var int_fact = document.getElementById('INT_FACTURABLE'+id).textContent;
                var col_jw = document.getElementById('COL_JW'+id).textContent;
                var col_abs = document.getElementById('COL_ABS'+id).textContent;
                var col_we = document.getElementById('COL_WE'+id).textContent;
                var ram_sam = document.getElementById('RAM_SAMEDI'+id).textContent;
                var ram_dim = document.getElementById('RAM_DIMANCHE'+id).textContent;
                var ram_fer = document.getElementById('RAM_FERIE'+id).textContent;
                var ram_cli = document.getElementById('RAM_CLIENT'+id).textContent;
                var pro_nom = document.getElementById('PRO_NOM'+id).textContent;
                var pro_fac = document.getElementById('PRO_PREFAC'+id).textContent;
                var mnemo = document.getElementById('MNEMONIC'+id).value;
                var col_no = document.getElementById('COL_NO'+id).value;
                var post = new Array();

                post['COL_NOM'] = col_nom;
                post['INT_FACT'] = int_fact;
                post['COL_JW'] = col_jw;
                post['COL_ABS'] = col_abs;
                post['COL_WE'] = col_we;
                post['PRO_NOM'] = pro_nom;
                post['PRO_PREFAC'] = pro_fac;
                post['MNEMO'] = mnemo;
                post['COL_NO'] = col_no;
                post['RAM_SAM'] = ram_sam;
                post['RAM_DIM'] = ram_dim;
                post['RAM_FER'] = ram_fer;
                post['RAM_CLI'] = ram_cli;
                post['annee'] = '<?php echo $annee; ?>';
                post['mois'] = '<?php echo $mois; ?>';
                
                var page = 'prefacturation.php?type=prefacturation';
                openWithPostData(page, post);
                
               function openWithPostData(page,data)
                {
                    var form = document.createElement('form');
                    
                    form.setAttribute('action', page);
                    form.setAttribute('method', 'post');
                    
                    for (var n in data)
                    {
                      var inputvar = document.createElement('input');
                        inputvar.setAttribute('type', 'hidden');
                        inputvar.setAttribute('name', n);
                        inputvar.setAttribute('value', data[n]);
                         form.appendChild(inputvar);
                    }
                    document.body.appendChild(form);
                    form.submit();
               }
            }
            $(document).ready(function () {
                $("tr:odd").each(function() {
                    $(this).children().css("background-color", "#bbbbff");
                });
            });
        </script>
    </body>
</html>
<?php
}
?>