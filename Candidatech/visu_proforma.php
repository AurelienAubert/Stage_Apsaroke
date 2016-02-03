<?php
function bouton($texte, $classe, $valeur, $classeCouleur) {
    return '<input type="button" class="btn ' . $classeCouleur . ' ' . $classe . '" value="' . $texte . '" onclick="' .$valeur. '"/>';
}
function bouton2($texte, $classe, $val1, $val2, $classeCouleur) {
    return '<input type="button" class="btn ' . $classeCouleur . ' ' . $classe . '" value="' . $texte . '" onclick="imprimer('.$val1.', \''.$val2.'\');"/>';
}
function bouton3($texte, $classe, $valeur, $classeCouleur) {
    return '<input type="button" class="btn ' . $classeCouleur . ' ' . $classe . '" value="' . $texte . '" onclick="avoirer(' .$valeur.');"/>';
}

/* visu_proformpa.php
 * Liste des proformas généré pour une période de facturation
 * 1    |  MULCEY  | 03/12/2014    | Création
 * 
 * 
 */

require 'inc/verif_session.php';
include 'calendrier/fonction_mois.php';
include ('calendrier/fonction_nbjoursMois.php');
include ('calendrier/jours_feries.php');
include ('calendrier/fonction_dimanche_samedi.php');
include ('calendrier/fonction_nomMois.php');
include ('inc/connection.php');

if ($_GET['valideFacture'] == 'valideFacture' || $_GET['valideFacture'] == 'avoirFacture'){
    $qfac = 'SELECT * FROM FACTURE WHERE FAC_NO=' . $_GET['recherche'];
    $rfac = $GLOBALS['connexion']->query($qfac)->fetch_assoc();
    $facture = substr($rfac['FAC_DATE'], 2, 2) . MnemoMois(substr($rfac['FAC_DATE'], 5, 2));

    $FAC_NUM_QUERY = $GLOBALS['connexion']->query('SELECT PAR_VALEUR FROM PARAMETRE WHERE PAR_LIBELLE = "FAC_NUM"')->fetch_assoc();
    $facture = strtoupper($facture);
    $facture .= $FAC_NUM_QUERY['PAR_VALEUR'];
    $FAC_NUM_QUERY['PAR_VALEUR'] ++;
    
    //Met à jour le numéro de la facture
    $GLOBALS['connexion']->query('UPDATE PARAMETRE SET PAR_VALEUR =' . $FAC_NUM_QUERY['PAR_VALEUR'] . ' WHERE PAR_LIBELLE = "FAC_NUM"');

    if($_GET['valideFacture'] == 'valideFacture'){
    //Met à jour la facture
        $GLOBALS['connexion']->query('UPDATE FACTURE SET FAC_NUM = "' . $facture . '" WHERE FAC_NO = "' . $_GET['recherche'] . '"');
    }
//    if($_GET['valideFacture'] == 'avoirFacture'){
//    //Met à jour la facture
//        $GLOBALS['connexion']->query('UPDATE FACTURE SET FAC_AVO = "' . $facture . '" WHERE FAC_NO = "' . $_GET['recherche'] . '"');
//    }
    //On vide valideFacture afin d'éviter de refaire le script au dessus si l'utilisateur recharge la page
    $_GET['valideFacture'] = '';
}




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

        $GLOBALS['titre_page'] = '<div class="adm">Pré Facturation ' . nomMois($mois) . ' ' . $annee . '</div>';
        $GLOBALS['retour_page'] = 'chx_date.php?type=proforma';
        include ("menu/menu_global.php");
        ?>
        <form name="visuproforma" action="visu_proforma.php" method="post">
            <input type="hidden" name="mois" value="<?php echo $mois; ?>" />
            <input type="hidden" name="annee" value="<?php echo $annee; ?>" />
        <div style = 'margin-left:2em; text-align: center;' class=''>
            </br>
            <div class="nav pull-right" >
                <a href="./prefacturation.php?mois=<?php echo $mois; ?>&annee=<?php echo $annee; ?>"><button id="btprefacturation" class="btn btn-primary" type="button" > Retour pré-facturation<i class="icon-arrow-left"></i> </button></a>
            </div>
            <div style="text-align: center; width: 100%;"><b>Liste des Proforma</b></div>
            <div style="text-align: center; width: 100%;">Période du 1er <?php echo nomMois($mois); ?> au <?php echo nbjoursMois($mois, $annee); ?> <?php echo nomMois($mois); ?> <?php echo $annee; ?></div>
            </br>
            <?php
            $query = "SELECT F.FAC_NO, F.FAC_DEV, F.FAC_NUM, F.FAC_AVO, C.COL_NOM, C.COL_PRENOM, I.INT_FACTURABLE, F.FAC_NOMCLI, F.FAC_NOMPRO, F.COL_NO, F.PRO_NO
                            FROM FACTURE F, COLLABORATEUR C, INTERNE I 
                            WHERE I.COL_NO = C.COL_NO
                            AND F.COL_NO = C.COL_NO
                            AND F.FAC_MOIS = '" . $mois . "'
                            AND F.FAC_ANNEE = '" . $annee . "'
                            GROUP BY F.COL_NO, F.PRO_NO 
                            ORDER BY C.COL_NOM, C.COL_PRENOM";
            
            $result = $GLOBALS['connexion']->query($query);

            $tableau = array();

            while ($row = $result->fetch_assoc()) {
                $valeur = $row['COL_NO'].'.'.$row['PRO_NO'];
                $valmod = '';
                $valpro = '';
                $valfac = '';
                $valavo = '';
                
                // TODO : Laisse la facture affichée
                if($row['FAC_AVO'] != ''){
                    $valfac = bouton2($row['FAC_NUM'], 'valider', $row['FAC_NO'], 'facture', 'btn-primary');
                    $valavo = bouton2($row['FAC_AVO'], 'valider',$row['FAC_NO'], 'avoir', 'btn-primary');
                }
                else{
                    if($row['FAC_NUM'] != ''){
                        $valfac = bouton2($row['FAC_NUM'], 'valider', $row['FAC_NO'], 'facture', 'btn-primary');
                        $valavo = bouton3('Créer une facture d\'avoir', 'valider', $row['FAC_NO'], 'btn-primary');
                    }
                    else{
                        $valmod = bouton('Modifier', 'valider', 'modif('.$row['FAC_NO'].');', 'btn-primary');
                        $valpro = bouton2($row['FAC_DEV'], 'valider', $row['FAC_NO'], 'proforma', 'btn-primary');
                        $valfac = bouton('Facturer', 'valider', 'facturer('.$row['FAC_NO'].');', 'btn-primary');
                    }
                }
                
                $tableau[] = array(
                    'COL_NO' => $valeur,
                    'COL_NOM' => $row['COL_NOM'] . ' ' . $row['COL_PRENOM'],
                    'INT_FACTURABLE' => ($row['INT_FACTURABLE'] == 1 ? 'Oui' : 'Non'),
                    'CLI_NOM' => $row['FAC_NOMCLI'],
                    'PRO_NOM' => $row['FAC_NOMPRO'],
                    'MODIFIER' => $valmod,
                    'PROFORMA' => $valpro,
                    'FACTURE' => $valfac,
                    'AVOIR' => $valavo
                );
            }
                
            $table = '<table id="tableau1" border="1" class="table-bordered table-condensed" width="85%" align="center">
                        <thead>
                        <tr>
                        <th>Collaborateurs<br>internes</th>
                        <th>Facturable</th>
                        <th>Clients</th>
                        <th>Projets</th>
                        <th>Modifier</th>
                        <th>Proforma</th>
                        <th>Facture</th>
                        <th>Avoir</th>
                        </tr>
                        </thead><tbody>';
            echo $table;

            foreach ($tableau as $ligne) {
                echo '<tr id='.$ligne['COL_NO'].'>';
                echo '<td id="COL_NOM'.$ligne['COL_NO'].'">' . $ligne['COL_NOM'] . '</td>';
                echo '<td id="INT_FACTURABLE'.$ligne['COL_NO'].'">' . $ligne['INT_FACTURABLE'] . '</td>';
                echo '<td id="CLI_NOM'.$ligne['COL_NO'].'">' . $ligne['CLI_NOM'] . '</td>';
                echo '<td id="PRO_NOM'.$ligne['COL_NO'].'">' . $ligne['PRO_NOM'] . '</td>';
                echo '<td id="MODIFIER'.$ligne['COL_NO'].'">' . $ligne['MODIFIER'] . '</td>';
                echo '<td id="PROFORMA'.$ligne['COL_NO'].'">' . $ligne['PROFORMA'] . '</td>';
                echo '<td id="FACTURE'.$ligne['COL_NO'].'">' . $ligne['FACTURE'] . '</td>';
                echo '<td id="AVOIR'.$ligne['COL_NO'].'">' . $ligne['AVOIR'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table><br><br><br><br>';

            $query_ext = "SELECT F.FAC_NO, F.FAC_DEV, F.FAC_NUM, C.COL_NOM, C.COL_PRENOM, 1 AS INT_FACTURABLE, F.FAC_NOMCLI, F.FAC_NOMPRO, F.COL_NO, F.PRO_NO
                            FROM FACTURE F, COLLABORATEUR C, EXTERNE E 
                            WHERE E.COL_NO = C.COL_NO
                            AND F.COL_NO = C.COL_NO
                            AND F.FAC_MOIS = '" . $mois . "'
                            AND F.FAC_ANNEE = '" . $annee . "'
                            GROUP BY F.COL_NO, F.PRO_NO 
                            ORDER BY C.COL_NOM, C.COL_PRENOM";
            $result_ext = $GLOBALS['connexion']->query($query_ext);

            $tableau_ext = array();

            while ($row_ext = $result_ext->fetch_assoc()) {

                $valeur_ext = $row_ext['COL_NO'].'.'.$row_ext['PRO_NO'];
                $valmod = $row_ext['FAC_NUM'] != ''
                    ? '' : bouton('Modifier', 'valider', 'modif('.$row_ext['FAC_NO'].');', 'btn-primary');
                $valpro = $row_ext['FAC_NUM'] != ''
                    ? '' : bouton2($row_ext['FAC_DEV'], 'valider', $row_ext['FAC_NO'], 'proforma', 'btn-primary');
                $valfac = '';
                if($row_ext['FAC_NUM'] == '' && $row_ext['FAC_AVO'] == ''){
                    $valfac = bouton('Facturer', 'valider', 'facturer('.$row_ext['FAC_NO'].');', 'btn-primary');
                }
                else{
                    if($row_ext['FAC_NUM'] != '' && $row_ext['FAC_AVO'] == ''){
                        $valfac = bouton2($row_ext['FAC_NUM'], 'valider', $row_ext['FAC_NO'], 'facture', 'btn-primary');
                    }
                }
                
                $tableau_ext[] = array(
                    'COL_NO' => $valeur_ext,
                    'COL_NOM' => $row_ext['COL_NOM'] . ' ' . $row_ext['COL_PRENOM'],
                    'INT_FACTURABLE' => ($row_ext['INT_FACTURABLE'] == 1 ? 'Oui' : 'Non'),
                    'CLI_NOM' => $row_ext['FAC_NOMCLI'],
                    'PRO_NOM' => $row_ext['FAC_NOMPRO'],
                    'MODIFIER' => $valmod,
                    'PROFORMA' => $valpro,
                    'FACTURE' => $valfac
                );
            }

            //On remplace le mot "internes" par externes pour le deuxième tableau
            $table = str_replace('internes', 'externes', $table);
            //On change l'id
            $table = str_replace('tableau1', 'tableau2', $table);
            echo $table;

            foreach ($tableau_ext as $ligne_ext) {
                echo '<tr id='.$ligne_ext['COL_NO'].'>';
                echo '<td id="COL_NOM'.$ligne_ext['COL_NO'].'">' . $ligne_ext['COL_NOM'] . '</td>';
                echo '<td id="INT_FACTURABLE'.$ligne_ext['COL_NO'].'">' . $ligne_ext['INT_FACTURABLE'] . '</td>';
                echo '<td id="CLI_NOM'.$ligne_ext['COL_NO'].'">' . $ligne_ext['CLI_NOM'] . '</td>';
                echo '<td id="PRO_NOM'.$ligne_ext['COL_NO'].'">' . $ligne_ext['PRO_NOM'] . '</td>';
                echo '<td id="MODIFIER'.$ligne_ext['COL_NO'].'">' . $ligne_ext['MODIFIER'] . '</td>';
                echo '<td id="PROFORMA'.$ligne_ext['COL_NO'].'">' . $ligne_ext['PROFORMA'] . '</td>';
                echo '<td id="FACTURE'.$ligne_ext['COL_NO'].'">' . $ligne_ext['FACTURE'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table><br><br><br><br>';
            
            // Préparation des URL pour les impressions
            $url = 'facture/imprime_FAC.php?recherche=';
            ?>
        </div>
        </form>
        <script type="text/javascript">
            xhr = new XMLHttpRequest();
            // Lorsqu'un réponse est émise par le serveur
            xhr.onreadystatechange = function() {
                if (xhr.status == 200 && xhr.readyState == 4 && xhr.responseText != '') {
                    //t[0] = Valeur facture
                    //t[1] = Numéro facture
                    var t = xhr.responseText.split('&shy');
                    var mtFacture = t[0];
                    var facNo = t[1];
                    mtFacture = parseFloat(mtFacture);
                    var mtFacAvoir = -1;
                    
                    var reponse = '';
                    var stop = 'n';
                    while(mtFacAvoir < 1 || mtFacAvoir > mtFacture || isNaN(mtFacAvoir)){
                        mtFacAvoir = prompt("Le montant de la facture à avoirer est de "+mtFacture+" ¤\n Quel montant souhaitez vous avoirer ?");
                        mtFacAvoir = parseFloat(mtFacAvoir);
                        if(mtFacAvoir > 0 && mtFacAvoir <= mtFacture){
                            if(mtFacAvoir <= mtFacture){
                                while(reponse != 'n' && reponse != 'N' && reponse != 'o' && reponse != 'O'){
                                    reponse = prompt("Vous souhaitez avoirer "+ mtFacAvoir +" ¤. Êtes-vous sûr ? O/N");
                                }
                            }
                            //L'utilisateur a fait une erreur. On lui permet de saisir à nouveau un montant
                            if(reponse == 'N' || reponse == 'n'){
                                stop = 'n';
                            }
                            else{
                                //TODO : Ajout commentaire
                                stop = 'o';
                            }
                        }
                    }
                    var commentaire = '';
                    while(commentaire == ''){
                        commentaire = prompt("Veuillez saisir la raison de l'avoir.");
                    }
                    
                    
                    if(stop != 'n'){
                        avoirer(facNo, mtFacAvoir, commentaire);
                    }
                }
                else{
                    //Recharge la page au bout de 100ms. Objectif : Faire apparaître le numéro d'avoir sur la page
                    setTimeout(function(){ location.reload(); }, 100);
                }
            };
            //function facture_pro_format(id, type)
            function modif(id)
            {
                var post = new Array();
                
                post['annee'] = '<?php echo $annee; ?>';
                post['mois'] = '<?php echo $mois; ?>';
                post['recherche'] = id;
                var page = '';
                
                page = 'facturation.php';
                    
                openWithPostData(page, post);
            }
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

            function imprimer(id, type)
            {
                document.forms[0].submit();
                window.open('<?php echo $url; ?>' + id + '&type=' + type, 'imp' + type, 'height=900px; width=850px');
            }

            function facturer(id)
            {
                document.forms[0].action = 'visu_proforma.php?valideFacture=valideFacture&recherche=' + id;
                document.forms[0].submit();
            }
            
            function avoirer(id, mtFacAvoir, commentaire)
            {
                if(typeof(mtFacAvoir) === 'undefined'){
                    xhr.open('GET', 'requete.php?FAC_NO='+id);
                    xhr.send('');
                }
                else
                {
                    xhr.open('GET', 'requete.php?FAC_NO='+id+'&FAC_MT_AVOIR='+mtFacAvoir+'&FAC_AVO_COM='+commentaire);
                    xhr.send('');
                }
            }

            $("tr:odd").each(function() {
                $(this).children().css("background-color", "#bbbbff");
            });
        </script>
    </body>
</html>
