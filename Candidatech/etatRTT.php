<?php
///////////////////////////////////////////////
///////////////////////////////////////////////
///////////////////////////////////////////////
//Faire tableau RTT (aide visuelle) � enlever//
//////////////par la suite/////////////////////
///////////////////////////////////////////////
///////////////////////////////////////////////
include 'insertUpdateRTT.php';
include 'calendrier/fonction_nbjoursMois.php';
include 'calendrier/fonction_nbjoursouvres.php';
include 'inc/connection.php';
include 'inc/verif_session.php';

//echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Etat des RTT</title>
        <style>
            .maxSize{
                width: 90%;
                margin-top: 5px;
            }
            tbody:after {
                line-height:1em;
                content:"_";
                color:white;
                display:block;
            }
        </style>
    </head>

<?php
//Barre de menu
include ("menu/menu_global.php");

$URL = $_SERVER['REQUEST_URI'];
$URL1 = parse_url($URL);
$url = $URL1['path'].'?'.$URL1['query']; 

echo '<body onload="calcul()"><div class="container-fluid" style="width:100%;" align="center">';

//On r�cup�re le num�ro de collaborateur contenu dans la variable $_POST
$col_no = '';
if(isset($_POST['recherche']))
    $col_no = $_POST['recherche'];
else
    $col_no = $_SESSION['col_id'];
$type = strtoupper(substr($_GET['type'], 14,7));
////////////////////////////////////////////////////////////
//////////////////////CODE A FACTORISER/////////////////////
/////IDEE : Annee-1 pour les mois de l'ann�e derni�re///////
/////OBEJCTIF : FACTORISER A MOOOOOOOOORT///////////////////
////////////////////////////////////////////////////////////

/*
 * RTT dispo : Suivi dans le tableau
 */

/*
 * On veut r�cup�rer l'ensemble des informations provenant de la table interne
 * ou externe. Selon la valeur que prend la variable $type
 * pour le collaborateur correspondant � col_no
 */
$queryIntExt = 'SELECT * FROM '.$type.' WHERE COL_NO ='.$col_no;
$intOuExt = $GLOBALS['connexion']->query($queryIntExt)->fetch_assoc();

$queryRTTDetail = 'select ABS_ANNEE, ABS_MOIS, count(ABS_JOUR) "NB_JOUR" '
            . 'from ABSENCE '
            . 'where COL_NO = '.$col_no.' '
            . 'AND TYA_NO = 3 '
            . 'GROUP BY ABS_ANNEE, ABS_MOIS '
            . 'ORDER BY ABS_ANNEE';


$RTTsDetails = $GLOBALS['connexion']->query($queryRTTDetail);

$tabRTTDetail = array();
while($RTTDetail = $RTTsDetails->fetch_assoc())
{
    $tabRTTDetail[$RTTDetail['ABS_ANNEE']][$RTTDetail['ABS_MOIS']] = $RTTDetail['NB_JOUR'];
}

//$anneeSolde = date('Y')-2;

$queryRTT = 'SELECT * FROM RTT WHERE COL_NO ='.$col_no;
$RTTs = $GLOBALS['connexion']->query($queryRTT);

$tabRTT = array();
while($RTT = $RTTs->fetch_assoc()){
    $tabRTT[$RTT['RTT_ANNEE']][$RTT['RTT_MOIS']] = $RTT['RTT_VAL_JOUR'];
}

$anneeSolde = date('Y')-2;

$querySolde = 'SELECT SUM(RTT_VAL_JOUR)"NbRTTAcquis" FROM RTT WHERE COL_NO = '.$col_no.' AND RTT_ANNEE = '.$anneeSolde.';';
$SoldeN2 = $GLOBALS['connexion']->query($querySolde)->fetch_assoc();
$solde = $SoldeN2['NbRTTAcquis'];

$querySolde = 'SELECT SUM(ABS_NBH)"NbRTTPris" FROM ABSENCE WHERE COL_NO = '.$col_no.' AND TYA_NO = 3 AND ABS_ANNEE='.$anneeSolde.';';
$SoldeN2 = $GLOBALS['connexion']->query($querySolde)->fetch_assoc();
$solde -= $SoldeN2['NbRTTPris'];


//On r�cup�re la date d'entr�e dans l'entreprise du collaborateur
if($type == 'INTERNE')
    $dateEntree = $GLOBALS['connexion']->query('SELECT INT_DTENTREE FROM '.$type.' WHERE COL_NO='.$col_no)->fetch_assoc();
//On r�cup�re l'ensemble des informations pour le collaborateur choisi
$collaborateur = $GLOBALS['connexion']->query('SELECT * FROM COLLABORATEUR WHERE COL_NO='.$col_no)->fetch_assoc();

$tableRTT = '<table id="RTT" border="1" class="table-bordered table-condensed" width="45%">
            <thead>
            <tr>
                <th colspan="5">'.$collaborateur['COL_NOM'].' '.$collaborateur['COL_PRENOM'].'</th>
            </tr>
            <tr>
                <th colspan="5">Date d\'entr�e :'.$dateEntree['INT_DTENTREE'].'</th>
            </tr>
            <tr>
                <th colspan="5">RECAPITULATIF RTT</th>
            </tr>
            </thead>';

echo '<input type="hidden" id="LE_COL_NO" value="'.$collaborateur['COL_NO'].'"/>';
echo '<input type="hidden" id="LA_TABLE" value="'.$type.'"/>';

$tabMoisRTT = array('1' => 'Janvier',
                '2' => 'F&eacute;vrier',
                '3' => 'Mars',
                '4' => 'Avril',
                '5' => 'Mai',
                '6' => 'Juin',
                '7' => 'Juillet',
                '8' => 'Ao&ucirc;t',
                '9' => 'Septembre',
                '10' => 'Octobre',
                '11' => 'Novembre',
                '12' => 'D&eacute;cembre',
    );

/*
 * Ensemble de variable permettant d'identifier de mani�re unique chaque cellule
 * La variable $id quant � elle permet d'identifier le num�ro de ligne
 * Int�r�t : Concat�ner les deux variables afin d'obtenir un nom unique identifiant
 * la ligne et la cellule
 * Exemple : 'CPAcquis13 -> Premi�re ligne de l'ann�e suivant l'ann�e d'entr�e, cellule : Cong� acquis
 */

$idRTTAcquis = 'RTTAcquis';
$idCumul = 'RTTCumul';
$idRTTPris = 'RTTPris';
$id = 1;

$moisCourant = date('m');
$moisCourant = (int)date('m');  //Cast en int

/*
 * On r�cup�re l'ann�e, le mois et le jour d'entr� et on les met sous la forme 'YYYY' et 'MM'
 */
$anneeEntree = substr($dateEntree['INT_DTENTREE'],0,4);
$moisEntree = substr($dateEntree['INT_DTENTREE'],5,2);
$jourEntree = substr($dateEntree['INT_DTENTREE'],8,2);
/*
 * Objectif : Comparer l'ann�e d'entr�e et le mois d'entr�e avec l'ann�e d'entr� et le mois de Juin afin de savoir
 * si oui ou non, il faut ajout� 1 � la variable $anneeEntree afin de ne pas avoir
 * le r�capitulatif de l'ann�e d'entr�e avant Juin
 */
$AMEntree = $anneeEntree.$moisEntree;
$AMEntree = (int)$AMEntree; //Cast en integer
$AMDebutCP = $anneeEntree.'05'; //05 correspond au mois de Mai
$AMDebutCP = (int)$AMDebutCP;

/*
 * CAS : Si l'employ� rentre en cours d'ann�e par exemple, en Ao�t 2009. 
 * On n'a pas besoin de voir le r�capitulatif de l'ann�e 2009 allant de Juin 2008 � Mai 2009.
 */  
if($AMEntree > $AMDebutCP)
{
    $anneeEntree++;
}

//$anneeCourante est la borne qui permet � la boucle while de s'arr�ter
$anneeCourante = date('Y')-1;

////////////////////////////////////////////////////////////
//////////////////////CODE A FACTORISER/////////////////////
////////////////////////////////////////////////////////////



$id=1;
for($i=0;$i<2;$i++){
    $tableRTT .= '<tbody id="'.$anneeCourante.'">';
    $tableRTT .= '<tr><td>R�sum� ann�e '.($anneeCourante).'</td>
                <td>RTT Acquis</td>
                <td>RTT Pris</td>
                <td>RTT Report</td>
                <td>Cumul</td></tr>';
    for($moisNumero = 1; $moisNumero < 13;$moisNumero++)
    {
        $tableRTT .= '<tr id="'.($anneeCourante).'-'.$moisNumero.'">';
        $tableRTT .= '<td>'.$tabMoisRTT[$moisNumero].'</td>';       

        $tableRTT .= '<td id="'.$idRTTAcquis.$id.'">';
        $temp = '';
        if(isset($tabRTT[$anneeCourante][$moisNumero]))
            $temp = $tabRTT[$anneeCourante][$moisNumero];
        else
            $temp = $moisNumero == 7 || $moisNumero == 8 ? 0 : 1;
        $tableRTT .= $temp.'</td><td id="'.$idRTTPris.$id.'">';
        $temp = isset($tabRTTDetail[$anneeCourante][$moisNumero]) ? $tabRTTDetail[$anneeCourante][$moisNumero] : 0;
        $tableRTT .= $temp. '</td>';
        if($i == 0 && $moisNumero == 1)
            $tableRTT .= '<td id="RTTSolde'.$id.'">'.$solde.'</td>';
        else
            $tableRTT .= '<td id="RTTSolde'.$id.'">0</td>';
        $tableRTT .= '<td id="'.$idCumul.$id.'">0</td>';

        $id++;
        $tableRTT .= '</tr>';
        if($moisNumero == 12)
        {
            $tableRTT .= '<tr id="Fin'.$id.'"><td>Total Janvier '.substr($anneeCourante,0,4).' � D�cembre '.substr($anneeCourante,0,4).'</td><td id="TotRecupDispo'.$id.'"></td>'
            . '<td id="TotRecupPris'.$id.'"></td><td id="TotCPExceptionnel'.$id.'"></td><td id="TotCumul'.$id.'"></td>'
            . '</tr>';
        }
    }
    $tableRTT .= '</tbody>';
    $anneeCourante++;
}
    $tableRTT .= '</table>';
    echo $tableRTT;
    echo '</div></body>';
    
?>
    
    <script language="javascript">
        
        
        $('tr td').each(function() {
            if(String($(this).attr("id")).indexOf('RTTCumul') > -1){
                $(this).css("background-color", "#FFD700");
            }
        });
        
        accred = <?php echo $_SESSION['accreditation']; ?>;
        //Ev�nement permettant de g�rer les doubles cliques
        //afin de modifier le contenu d'une cellule (tableau RTT uniquement)
        
        /*
         * Test via niveau session
         */
        if(accred < 3)
        {
            $( "#RTT" ).dblclick(function(e) {
                editCell(e);
            });
        }
        
        function getEventTarget(e) {
            e = e || window.event;
            return e.target || e.srcElement;
        }

        function editCell(e) {
        var target = getEventTarget(e);
            if(target.tagName.toLowerCase() === 'td') {
                var tarString = String(target.id);
                if(tarString.indexOf("RTTAcquis") != -1)
                {
                    var nb = -1;
                    while(nb != 0 && nb != 0.5 && nb != 1)
                    {
                        nb = prompt("Valeur possibles : 0, 0.5, 1");
                    }
                    //Si l'utilisateur ne saisi rien, on remplace la donn�e par 0
                    if(nb == '')
                        nb = 0;
                    target.innerHTML = nb;
                    
                    var annee = target.parentNode.id.substr(0,4);
                    var mois = target.parentNode.id.substr(5,2);
                    var col_no = document.getElementById('LE_COL_NO').value;
                    var table = target.parentNode.parentNode.parentNode.id;
                    
                    sauvegarde(nb, annee, mois, col_no, table);
                    calcul();
                }
            }
        }
        function sauvegarde(nb, annee, mois, col_no, table) {
            var formData = {VAL_JOUR: nb, ANNEE:annee, MOIS:mois, COL_NO:col_no, TABLE:table};
            $.ajax({
                url: 'insertUpdateRTT.php',
                type: 'POST',
                async: true,
                data: formData
            }).success(alert('Modification(s) sauvegard�e(s)'));
            location.reload();
        }
        
        function modif(i){
            var RTT = '';
            while(isNaN(parseFloat(RTT)))
            {
                RTT = prompt("Veuillez saisir les RTT suppl�mentaires accord�s.");
                RTT = RTT.replace(',','.');
            }
            if(i == 1){
                document.getElementById('TotRecupDispo'+i).innerHTML = parseFloat(document.getElementById('TotRecupDispo'+i).innerHTML) + parseFloat(RTT);
                document.getElementById('TotRecupDispo'+i).style.color = '#FF0000';
            }
            else{
                document.getElementById('AjoutRTT'+i).innerHTML = RTT;
                document.getElementById('AjoutRTT'+i).style.color = '#FF0000';
            }
//            calcul();
            var reponse = '';
            
            while(reponse != 'O' && reponse != 'N' && reponse != 'o' && reponse != 'n')
            {
                reponse = prompt("�tes-vous s�r de vouloir sauvegarder ces changements ? (O/N)");
            }
//            echo '<input type="hidden" id="LE_COL_NO" value="'.$collaborateur['COL_NO'].'"/>';
//            echo '<input type="hidden" id="LA_TABLE" value="'.$type.'"/>';

            var table = document.getElementById('LA_TABLE').value;
            
            if(reponse == 'O' || reponse == 'o')
            {
                sauvegarde(RTT, document.getElementById('LE_COL_NO').value, table);
            }
        }
        
        function calcul(){
            var i = 1;
            
            var nbTbody = 0;
            $('tbody').each(function(){
                nbTbody++;
            });
            var lg = document.getElementById('RTT').rows.length;
            lg -= 2*nbTbody;
            lg -=3;
            
            var RTTCumul = 0;
            var RTTAcquis = 0;
            var RTTPris = 0;
            var RTTReport = 0;

            while(i <= lg)
            {
                //Si i % 12 vaut 1, on est dans le r�cap de l'ann�e. Exception i == 1, d�but du tableau donc exclu
                if(i % 12 == 1 && i != 1)
                {
                    document.getElementById('Fin'+i).style.backgroundColor = "#6495ED";
                    document.getElementById('TotRecupDispo'+i).innerHTML = RTTAcquis;   //Cellule : Total RTT acquis
                    document.getElementById('TotRecupPris'+i).innerHTML = RTTPris;      //Cellule : Total RTT pris
                    document.getElementById('TotCPExceptionnel'+i).innerHTML = RTTReport;
                    document.getElementById('TotCumul'+i).innerHTML = RTTAcquis - RTTPris + RTTReport;
                    
                    //On r�initialise les variables RTTCumul, RTTAcquis et RTTPris afin de commencer les totaux de l'ann�e suivante
                    
                    RTTCumul = 0;
                    RTTAcquis = 0;
                    RTTPris = 0;
                    RTTReport = 0;
                    
                    
                    document.getElementById('RTTSolde'+i).innerHTML = parseFloat(document.getElementById('TotCumul'+i).innerHTML);
                    RTTReport += parseFloat(document.getElementById('RTTSolde'+i).innerHTML);
                    
                    RTTAcquis += parseFloat(document.getElementById('RTTAcquis'+i).innerHTML);
                    RTTAcquis += parseFloat(document.getElementById('RTTSolde'+i).innerHTML);
                    RTTPris += parseFloat(document.getElementById('RTTPris'+i).innerHTML);
                    RTTCumul += RTTAcquis - RTTPris;
                    document.getElementById('RTTCumul'+i).innerHTML = RTTCumul;
                }
                //On rempli les lignes du tableau(de Juillet � Mai)
                else
                {
                    RTTAcquis += parseFloat(document.getElementById('RTTAcquis'+i).innerHTML);
                    RTTReport += parseFloat(document.getElementById('RTTSolde'+i).innerHTML);
                    RTTPris += parseFloat(document.getElementById('RTTPris'+i).innerHTML);
                    RTTCumul += RTTAcquis - RTTPris;
                    document.getElementById('RTTCumul'+i).innerHTML = RTTCumul;
                    //Si i est diff�rent de 1, on fais la diff�rence entre le montant des CP consommables et le montant des CP consomm�s du mois pr�c�dent (dans les deux cas)
                    if(i != 1)
                    {
                        document.getElementById('RTTCumul'+i).innerHTML = RTTAcquis - RTTPris;
                    }
                }
                
                i++;
            }
            document.getElementById('TotCPExceptionnel'+i).innerHTML = RTTReport;
            document.getElementById('TotRecupDispo'+i).innerHTML = RTTAcquis;   //Cellule : Total RTT acquis
            document.getElementById('TotRecupPris'+i).innerHTML = RTTPris;      //Cellule : Total RTT pris
            document.getElementById('TotCumul'+i).innerHTML = RTTAcquis - RTTPris + RTTReport;
            document.getElementById('Fin'+i).style.backgroundColor = "#6495ED";
        }
    </script>
</html>