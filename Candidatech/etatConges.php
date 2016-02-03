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
        <title>Etat des conges</title>
        <style>
            .mesCol{
                font-size: 20px;
                color: rgba(34,139,34,0.9) !important;
            }
            .mesCol2{
                font-size: 20px;
                color: rgba(178,34,34,0.9) !important;
            }
            .blue > td{
                color: rgba(28,83,140,0.9) !important;
            }
            .grey{
                background-color: rgba(184,187,189,0.9) !important;
            }
            .green > td{
                color: rgba(34,139,34,0.9) !important;
            }
            .red > td{
                color: rgba(178,34,34,0.9) !important;
            }
        </style>
    </head>

<?php
//Barre de menu
include ("menu/menu_global.php");

echo '<body onload="calcul()"><div class="container-fluid" style="width:95%;" align="center">';

echo '<input type="hidden" id="LE_COL_NO" value="'.$collaborateur['COL_NO'].'"/>';
echo '<input type="hidden" id="LA_TABLE" value="'.$type.'"/>';

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

////On veut r�cup�rer l'ensemble des cong�s sans solde pris par le collaborateur
//$queryCPSS = 'select ABS_ANNEE, ABS_MOIS, count(ABS_JOUR) "NB_JOUR" '
//            . 'from absence '
//            . 'where COL_NO = '.$col_no.' '
//            . 'AND TYA_NO = 6 '
//            . 'GROUP BY ABS_ANNEE, ABS_MOIS '
//            . 'ORDER BY ABS_ANNEE, ABS_MOIS, ABS_JOUR';
//
//$CPSSs = $GLOBALS['connexion']->query($queryCPSS);

//$tabCPSS = array();
//while($CPSS = $CPSSs->fetch_assoc())
//{
//    echo '</br>';
//    if($CPSS['ABS_MOIS'] > 5)
//        $tabCPSS[$CPSS['ABS_ANNEE']][$CPSS['ABS_MOIS']-5] = $CPSS;
//    else
//        $tabCPSS[$CPSS['ABS_ANNEE']][$CPSS['ABS_MOIS']+7] = $CPSS;
//        
//}

//On veut r�cup�rer l'ensemble des absences du type "cong�s pay�s" pour le collaborateur choisi
$queryConge = 'select ABS_ANNEE, ABS_MOIS, count(ABS_JOUR) "NB_JOUR" '
            . 'from ABSENCE '
            . 'where COL_NO = '.$col_no.' '
            . 'AND TYA_NO = 2 '
            . 'GROUP BY ABS_ANNEE, ABS_MOIS '
            . 'ORDER BY ABS_ANNEE, ABS_MOIS, ABS_JOUR';
$conges = $GLOBALS['connexion']->query($queryConge);

/*
 * On rempli le tableau $tabConges avec le r�sultat de la requ�te $queryConge
 * On regroupe les cong�s pay�s par ann�e puis par mois. C'est-�-dire que toutes les ann�es
 * apparaissant dans les cong�s pay�s servent en tant que 1er indice pour naviguer dans le tableau
 * Ex : $tabConges[2015]. On se positionne sur l'ensemble des cong�s de l'ann�e 2015
 * Puis, on range l'ensemble des cong�s pay�s par mois.
 * Ex : $tabConges[2015][3]. On se positionne � l'ann�e 2015, au mois de Mars(3), et l'on peut
 * acc�der � l'ensemble des cong�s pay�s du mois de mars en 2015
 * 
 * NB : L'indice -5 et +7 permettent de faire concorder les num�ros des mois avec le tableau
 * de mois (plus bas dans le code) respectant l'ordre du suivi des cong�s pay�s (de Juin � Mai)
 */
$tabConges = array();
while($conge = $conges->fetch_assoc())
{
    if($conge['ABS_MOIS'] > 5)
        $tabConges[$conge['ABS_ANNEE']][$conge['ABS_MOIS']-5] = $conge['NB_JOUR'];
    else
        $tabConges[$conge['ABS_ANNEE']][$conge['ABS_MOIS']+7] = $conge['NB_JOUR'];
}

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
    $tabRTTDetail[$RTTDetail['ABS_ANNEE']+1][$RTTDetail['ABS_MOIS']] = $RTTDetail;
}

$queryRTT = 'SELECT * FROM RTT WHERE COL_NO ='.$col_no;
$RTTs = $GLOBALS['connexion']->query($queryRTT);

$tabRTT = array();
while($RTT = $RTTs->fetch_assoc()){
    $tabRTT[$RTT['RTT_ANNEE']+1][$RTT['RTT_MOIS']] = $RTT['RTT_VAL_JOUR'];
}


//On r�cup�re la date d'entr�e dans l'entreprise du collaborateur
if($type == 'INTERNE')
    $dateEntree = $GLOBALS['connexion']->query('SELECT INT_DTENTREE FROM '.$type.' WHERE COL_NO='.$col_no)->fetch_assoc();
//On r�cup�re l'ensemble des informations pour le collaborateur choisi
$collaborateur = $GLOBALS['connexion']->query('SELECT * FROM COLLABORATEUR WHERE COL_NO='.$col_no)->fetch_assoc();

$table = '<table id="EtatConges" border="1" class="table-bordered table-condensed" width="90%">
            <thead>
            <tr name="head">
                <th colspan="4">'.$collaborateur['COL_NOM'].' '.$collaborateur['COL_PRENOM'].'</th>
            </tr>
            <tr name="head">
                <th colspan="4">Date d\'entr�e :'.$dateEntree['INT_DTENTREE'].'</th>
            </tr>
            <tr name="head">
                <th colspan="4">RECAPITULATIF CONGES PAYES</th>
            </tr>';
            
$tableCol = $table;
$tableCol = str_replace('EtatConges', 'EtatCongesCol', $tableCol);
$tableCol = str_replace('border="1', 'border="0"', $tableCol);
$tableCol = str_replace('table-bordered', '', $tableCol);

$table .= '<tr name="head">
                    <td></td>
                    <td>CP Acquis</td> 
                    <td>CP consommables</td>
                    <td>CP consomm�s</td>
            </tr>';

/*
 * Tableau contenant les mois de l'ann�e
 * Ordre : De Juin de l'ann�e N-1 � Mai de l'ann�e N
 * Int�r�t : Respecter l'ordre suivi pour les cong�s pay�s
 */

$tabMois = array('1' => 'Juin',
                '2' => 'Juillet',
                '3' => 'Ao&ucirc;t',
                '4' => 'Septembre',
                '5' => 'Octobre',
                '6' => 'Novembre',
                '7' => 'D&eacute;cembre',
                '8' => 'Janvier',
                '9' => 'F&eacute;vrier',
                '10' => 'Mars',
                '11' => 'Avril',
                '12' => 'Mai',
    );

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
$idCPAcquis = 'CPAcquis';
$idCPConsommables = 'CPConsommables';
$idCPConsommes = 'CPConsommes';
$idRTTAcquis = 'RTTAcquis';
$idCumul = 'RTTCumul';
$idRTTPris = 'RTTPris';
//$idCPSS = 'CPSansSolde';
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

//$anneeFlux fera office de compteur afin d'obtenir toutes les ann�es n�cessaire au r�capitulatif
$anneeFlux = $anneeEntree;
//$anneeCourante est la borne qui permet � la boucle while de s'arr�ter
$anneeCourante = date('Y');

$queryPar = 'SELECT * FROM PARAMETRE WHERE PAR_LIBELLE="ANNEE_DEPART_SOLDE"';
$par = $GLOBALS['connexion']->query($queryPar)->fetch_assoc();

$tdSoldeDepart = '';

if($par['PAR_VALEUR'] !== NULL)
{
    //Si l'ann�e d'entr�e se situe avant l'ann�e par d�faut, les ann�es pr�c�dentes ne seront pas affich�es
    if($anneeFlux < $par['PAR_VALEUR'])
    {
        $anneeFlux = $par['PAR_VALEUR'];
        $table .= '<tbody id="First'.$anneeFlux.'">';
        $champ = $type == 'INTERNE' ? 'INT_SOLDE_CP' : 'EXT_SOLDE_CP';
        $tdSoldeDepart = '<td class="blue";">Solde Juin '.substr($anneeFlux-2,2,2).' - Mai '.substr($anneeFlux-1,2,2).'</td>'
        . '<td></td>'
        . '<td id="Solde1">'.$intOuExt[$champ].'</td>'
        . '<td></td>';
        
        $table .= '<tr id="SD'.$anneeFlux.'">'.$tdSoldeDepart.'</tr>';
    }
    else
    {
        $table .= '<tbody id="First'.$anneeFlux.'">';
        $tdSoldeDepart = '<td style="background-color: #3399FF">Solde Juin '.substr($anneeFlux-2,2,2).' - Mai '.substr($anneeFlux-1,2,2).'</td>'
        . '<td></td>'
        . '<td id="Solde1">0</td>'
        . '<td></td>';
        
        $table .= '<tr id="SD'.$anneeFlux.'">'.$tdSoldeDepart.'</tr>';
    }
}

$table .= '</tbody>';
//$moisNumero permettra de parcourir le tableau de mois ($tabMois) afin de cr�er autant de ligne qu'il y a de mois dans une ann�e
$moisNumero=1;
//$saveMoisEntree servira pour la fonction nbJourMois
$saveMoisEntree = $moisEntree;
$saveAnneeFlux = $anneeFlux;

if($moisEntree > 5)
    $moisEntree-=5;
else
    $moisEntree+=7;

if($moisCourant > 5)
{
    $anneeCourante++;
    $moisCourant-=4;
}
else
    $moisCourant+=7;

////////////////////////////////////////////////////////////
//////////////////////CODE A FACTORISER/////////////////////
////////////////////////////////////////////////////////////

//$anneeFlux modifiable via javascript ?
//$anneeFlux = 2014;
//isset sur une variable cr�e via le javascript
/*
 * Boucle autant de fois qu'il y a d'ann�e depuis la cr�ation du collaborateur
 * ou depuis la date fix� dans la table Parametre (selon si la base de donn�es est compl�te
 * ou si on doit partir d'une ann�e donn�e pour avoir toutes les infos n�cessaires
 */

while($anneeFlux <= $anneeCourante)
{
    /*
     * Boucle autant de fois qu'il y a de mois dans une ann�e
     */
    $table .= '<tbody id="'.$anneeFlux.'">';
    while($moisNumero < 13)
    {
        $table .= '<tr name="TR'.$anneeFlux.'">'; 
        /*
         * CAS : Les mois entre Janvier et Mai compris
         */
        if($moisNumero > 7)
        {
            $table .= '<td>'.$tabMois[$moisNumero].'-'.substr($anneeFlux,2,2).'</td>';
            /* 
             * CAS : 1�re ann�e dans l'entreprise
             */
            if($anneeEntree == $anneeFlux)
            {
                /*
                 * CAS : Mois de la 1�re ann�e sup�rieur au mois d'entr�e
                 */
                if($moisNumero >= $moisEntree){
                    $cpAcquis = 0;
                    if($moisNumero == $moisEntree)
                        $cpAcquis = round(2.083/joursouvres($saveMoisEntree, $anneeEntree) * (joursouvres($saveMoisEntree, $anneeEntree) - $jourEntree),2);
                    else
                        $cpAcquis = 2.083;
                    $table .= '<td id="'.$idCPAcquis.$id.'">'.(string)$cpAcquis.'</td>'
                     . '<td id="'.$idCPConsommables.$id.'">0</td>'
                     . '<td id="'.$idCPConsommes.$id.'">';
                    $temp = isset($tabConges[$anneeFlux][$moisNumero]) ? $tabConges[$anneeFlux][$moisNumero] : '0';
                    $table .= $temp. '</td>';
                }
            }
            /* 
             * CAS : Apr�s la 1�re ann�e
             */
            else
            {
                /*
                 * CAS : Ann�e en cours
                 */
                if($anneeCourante == $anneeFlux)
                {
                    /*
                     * CAS : Les mois pr�c�dant le mois en cours
                     */
                    if($moisNumero <= $moisCourant)
                    {
                        
                        if($moisNumero == $moisCourant)
                            $table .= '<td style="visibility: hidden" id="'.$idCPAcquis.$id.'">0</td>';
                        else
                            $table .= '<td id="'.$idCPAcquis.$id.'">2.083</td>';
                        
                        $table .= '<td id="'.$idCPConsommables.$id.'">0</td>'
                        . '<td id="'.$idCPConsommes.$id.'">';
                        $temp =  isset($tabConges[$anneeFlux][$moisNumero]) ? $tabConges[$anneeFlux][$moisNumero] : '0';
                        $table .= $temp.'</td>';
                    }
                    /*
                     * CAS : Mois suivant le mois en cours
                     * Int�r�t : Calcul du solde de l'ann�e en cours
                     */
                    else
                    {
                        $table .= '<td style="visibility:hidden" id="'.$idCPAcquis.$id.'">0</td>'
                        . '<td style="visibility:hidden" id="'.$idCPConsommables.$id.'">0</td>'
                        . '<td style="visibility:hidden" id="'.$idCPConsommes.$id.'">0</td>';
//                        . '<td style="visibility:hidden"></td>';
                    }
                }
                /*
                 * CAS : Avant l'ann�e en cours
                 */
                else
                {
                    $table .= '<td id="'.$idCPAcquis.$id.'">2.083</td>'
                    . '<td id="'.$idCPConsommables.$id.'">0</td>'
                    . '<td id="'.$idCPConsommes.$id.'">';
                    $temp = isset($tabConges[$anneeFlux][$moisNumero]) ? $tabConges[$anneeFlux][$moisNumero] : '0';
                    $table .= $temp . '</td>';
                }
            }
            
        }
        /*
         * CAS : Les mois entre Juin et D�cembre compris
         * NB : $anneeFlux-1 -> S�paration des mois. De Juin � D�cembre --> N-1
         */
        else
        {
            $table .= '<td>'.$tabMois[$moisNumero].'-'.substr($anneeFlux-1,2,2).'</td>';
            /* 
             * CAS : 1�re ann�e dans l'entreprise
             */
            if($anneeEntree == $anneeFlux)
            {
                /*
                 * CAS : Mois de la 1�re ann�e sup�rieur au mois d'entr�e
                 */
                if($moisNumero >= $moisEntree){
                    $cpAcquis = 0;
                    if($moisNumero == $moisEntree)
                        $cpAcquis = round(2.083/joursouvres($saveMoisEntree, $anneeEntree) * (joursouvres($saveMoisEntree, $anneeEntree) - $jourEntree),2);
                    else
                        $cpAcquis = 2.083;
                    $table .= '<td id="'.$idCPAcquis.$id.'">'.(string)$cpAcquis.'</td>'
                    . '<td id="'.$idCPConsommables.$id.'">0</td>'
                    . '<td id="'.$idCPConsommes.$id.'">';
                    $temp = isset($tabConges[$anneeFlux-1][$moisNumero]) ? $tabConges[$anneeFlux-1][$moisNumero] : '0';
                    $table .= $temp . '</td>';
                }
                /*
                 * CAS : Les mois pr�c�dants le mois d'entr�e dans l'entreprise du collaborateur
                 */
                else
                {
                    $table .= '<td id="'.$idCPAcquis.$id.'">0</td>'
                    . '<td id="'.$idCPConsommables.$id.'">0</td>'
                    . '<td id="'.$idCPConsommes.$id.'">0</td>';
                }
            }
            /*
             * CAS : Ann�es suivant la 1�re ann�e dans l'entreprise
             */
            else
            {
                /*
                 * CAS : Ann�e en cours �gale l'ann�eFlux
                 * Ann�eFlux : Variable compteur
                 */
                if($anneeFlux == $anneeCourante)
                {
                    //Si l'ann�e test� est �gale � l'ann�e courante 
                    //ET
                    //Si l'on a pas encore atteint le mois en cours, on �crit les lignes n�cessaires
                    if($moisNumero <= $moisCourant)
                    {
                        $table .= '<td id="'.$idCPAcquis.$id.'">2.083</td>'
                        . '<td id="'.$idCPConsommables.$id.'">0</td>'
                        .  '<td id="'.$idCPConsommes.$id.'">';
                        $temp = isset($tabConges[$anneeFlux-1][$moisNumero]) ? $tabConges[$anneeFlux-1][$moisNumero] : '0';
                        $table .= $temp .'</td>';
                    }
                    else
                    {
                        $table .= '<td style="visibility:hidden" id="'.$idCPAcquis.$id.'">0</td>'
                        . '<td style="visibility:hidden" id="'.$idCPConsommables.$id.'">0</td>'
                        . '<td style="visibility:hidden" id="'.$idCPConsommes.$id.'">0</td>';
//                        . '<td style="visibility:hidden"></td>';
                    }
                }
                /*
                 * CAS : Ann�e pr�c�dent l'ann�e en cours
                 */
                else
                {
                    $table .= '<td id="'.$idCPAcquis.$id.'">2.083</td>'
                    . '<td id="'.$idCPConsommables.$id.'">0</td>'
                    . '<td id="'.$idCPConsommes.$id.'">';
                    $temp = isset($tabConges[$anneeFlux-1][$moisNumero]) ? $tabConges[$anneeFlux-1][$moisNumero] : '0';
                    $table .= $temp.'</td>';
                }
            }
        }
        unset($tdRTT);
        $id++;
        $table .= '</tr>';
        if($moisNumero == 12)
        {
           $table .= '<tr name="Total'.$anneeFlux.'">'
            . '<td>TOTAL  Juin '.substr($anneeFlux-1,2,2).' - Mai '.substr($anneeFlux,2,2).'</td>'
            . '<td id="TotAn'.$id.'"></td>'
            . '<td></td>'
            . '<td id="TotConsommes'.$id.'"></td></tr>';
           
           $table .= '<tr name="Dif'.$anneeFlux.'">'
            . '<td>Arrondi au ></td>'
            . '<td id="UpAn'.$id.'"></td>'
            . '<td id="Dif'.$id.'"></td>'
            . '<td></td>'
            . '</tr>';
           $table .= '<tr name="Solde'.$anneeFlux.'">'
           . '<td>Solde</td>'
            . '<td></td>'
            . '<td id="Solde'.$id.'"></td>'
            . '<td></td></tr>';
        }
        $moisNumero++;
    }
    $anneeFlux++;
    //R�initialisation du num�ro de mois
    $moisNumero=1;
    $table .= '</tbody>';
}

    $table .= '</table>';
    
    echo $table;
    echo $tableCol.'<tr><td id="SoldeCol">Solde au '.date('d-m-Y').' - </td></tr>';
    
    echo '</div></body>';
?>
    
    <script language="javascript">
        var accred = <?php echo $_SESSION['accreditation']; ?>;
        
        $(document).ready(function(){
            if(accred > 3){
                $('#EtatConges').css('display', 'none'); 
            }
            else{
                $('#EtatCongesCol').css('display','none');
            }
        });
        
        var annee = <?php echo $anneeCourante; ?>;
        var mois = <?php echo $moisCourant; ?>;
        
        $('thead').css("background-color", "");
        
        var len = $('tbody').length;
        
        //Par ligne obligatoire (Ligne tbody pr�c�dent hide sinon
        $('tr').each(function(){
            if(String($(this).attr("name")).indexOf('Total') > -1 || String($(this).attr("name")).indexOf('Dif') > -1 || String($(this).attr("name")).indexOf('Solde') > -1 || String($(this).attr("id")).indexOf('SD') > -1){
               $(this).children().addClass('grey');
            }
            if(String($(this).attr("name")).indexOf('Solde') > -1 && String($(this).attr("name")).indexOf(annee) > -1){
                $(this).children().first().append(' Acquis');
            }
            else if(String($(this).attr("name")).indexOf('Solde') > -1 && String($(this).attr("name")).indexOf(annee-1) > -1){
                $(this).children().first().append(' Consommables');
            }
            if(String($(this).attr("name")).indexOf(annee) > -1){
                $(this).addClass('red');
            }
            if(String($(this).attr("name")).indexOf(annee-1) > -1){
                $(this).addClass('green');
            }
            if(String($(this).attr("name")).indexOf(annee) > -1 || String($(this).attr("name")).indexOf(annee-1) > -1 || String($(this).attr("name")).indexOf(annee-2) > -1){
                if(String($(this).attr("name")).indexOf("TR"+(annee-2)) > -1){
                    $(this).hide();
                }
                else {
                    if(String($(this).attr("name")).indexOf("Total"+(annee-2)) > -1 || String($(this).attr("name")).indexOf("Dif"+(annee-2)) > -1 || String($(this).attr("name")).indexOf("Solde"+(annee-2)) > -1){
                        $(this).addClass('blue');
                    }
                }
            }
            else{
                if(String($(this).attr('id')).indexOf('SD'+(annee)) > -1 || String($(this).attr("name")).indexOf("head") > -1){
                    
                }
                else{
                    $(this).hide();
                }
            }
        });
        
        
        function calcul(){
            var i = 1;
            
            var lg = document.getElementById('EtatConges').rows.length;
            
            var nbTbody = 0;
            $('tbody').each(function(){
                nbTbody++;
            });
            
            nbTbody--;
            
            /*
             * TODO : Calcul ligne
             */
            
            lg -= 3*nbTbody; 
            
            lg -=5;
            
            var ca = 0; //Conges acquis
            var cc = 0; //Conges consommes
            
            while(i <= lg){
                //Si i % 12 vaut 1, on est dans le r�cap de l'ann�e. Exception i == 1, d�but du tableau donc exclu
                if(i % 12 == 1 && i != 1)
                {
                    document.getElementById('TotAn'+i).innerHTML = ca.toPrecision(5);   //Cellule : Total ann�e
                    document.getElementById('UpAn'+i).innerHTML = Math.ceil(ca);        //Cellule : Arrondi sup�rieur
                    document.getElementById('TotConsommes'+i).innerHTML = cc;           //Cellule : Total cong�s consomm�s
                    /*
                     * On utiise l'id "Solde" concat�n� � la valeur de i-12 pour r�cup�rer le solde de l'ann�e pr�c�dente
                     * S'il n'y a pas d'ann�e pr�c�dente, on r�cup�re le solde par d�faut (champ pr�sent dans la table
                     * interne pour chaque collaborateur
                     */
                    document.getElementById('Dif'+i).innerHTML = parseFloat(document.getElementById('Solde'+(i-12)).innerHTML) - cc;
                    //R�cup�rer longueur total 
//                    var p = document.getElementById('Dif'+i).innerHTML;
//                    document.getElementById('SoldeCol').innerHTML += p;
                    /*
                     * Le solde de l'ann�e x rep�r� gr�ce � la valeur de i prend la valeur du total arrondi ajout�
                     * � la valeur des CP restant (qui peut �tre n�gative)
                     */
                    document.getElementById('Solde'+i).innerHTML = parseFloat(document.getElementById('UpAn'+i).innerHTML) + parseFloat(document.getElementById('Dif'+i).innerHTML);         //Cellule : Solde fin d'ann�e    
                    document.getElementById('Solde'+i).style.fontSize = "25px";
                    /*
                     * Le premier montant des CP consommables correspond au solde pr�c�dent
                     */
                    document.getElementById('CPConsommables'+i).innerHTML = parseFloat(document.getElementById('Solde'+i).innerHTML);

                    //On r�initialise les variables ca, cc et RTTCumul afin de commencer les totaux de l'ann�e suivante
                    ca = 0;
                    cc = 0;
                }
                //On rempli les lignes du tableau(de Juillet � Mai)
                else
                {   
                    if(i != 1)
                    {
                        document.getElementById('CPConsommables'+i).innerHTML = parseFloat(document.getElementById('CPConsommables'+(i-1)).innerHTML) - parseFloat(document.getElementById('CPConsommes'+(i-1)).innerHTML);
                    }
                    //Si on est au d�but du tableau, on reprend le solde par d�faut pr�sent dans la table interne pour chaque collaborateur
                    if(i == 1)
                    {
                        document.getElementById('CPConsommables'+i).innerHTML = parseFloat(document.getElementById('Solde'+i).innerHTML);
                        document.getElementById('Solde'+i).style.fontSize = "25px";
                        document.getElementById('Solde'+i).style.color = "rgba(28,83,140,0.9)";
                        document.getElementById('Solde'+i).style.display = '!important';
                    }
                }
                
                //Test permettant de savoir si oui ou non un nombre figure dans une cellule
                if(!isNaN(parseFloat(document.getElementById('CPAcquis'+i).innerHTML))){
                    ca += parseFloat(document.getElementById('CPAcquis'+i).innerHTML);
                }
                
                if(!isNaN(parseFloat(document.getElementById('CPConsommes'+i).innerHTML))){
                    cc += parseFloat(document.getElementById('CPConsommes'+i).innerHTML);
                }
                 
                i++;
            }
            document.getElementById('TotAn'+i).innerHTML = ca.toPrecision(5);   //Cellule : Total ann�e
            document.getElementById('UpAn'+i).innerHTML = Math.ceil(ca);        //Cellule : Arrondi sup�rieur
            document.getElementById('TotConsommes'+i).innerHTML = cc;           //Cellule : Total cong�s consomm�s
            document.getElementById('Dif'+i).innerHTML = parseFloat(document.getElementById('Solde'+(i-12)).innerHTML) - cc;
            document.getElementById('Solde'+i).innerHTML = parseFloat(document.getElementById('UpAn'+i).innerHTML) + parseFloat(document.getElementById('Dif'+i).innerHTML);         //Cellule : Solde fin d'ann�e    
            
            document.getElementById('Solde'+i).style.fontSize = "25px";
            document.getElementById('Solde'+i).style.color = "rgba(28,83,140,0.9)";
            document.getElementById('Solde'+i).style.display = '!important';
            
            //Ajoute les �l�ments au tableau pour les collaborateurs
            tabCol(i);
        }
        
        function tabCol(i){
            var laDate=new Date();
            var nbCP = document.getElementById('Dif'+i).innerHTML;
            
            var msg1 = 'Il vous reste '+nbCP+' cong�s � prendre jusqu\'au 31 Mai '+laDate.getFullYear();
            var msg2 = 'Vous avez acquis '+document.getElementById('UpAn'+i).innerHTML+' jours depuis le 1er Juin '+(laDate.getFullYear()-1)+' que vous pourrez prendre � partir du 1er Juin '+laDate.getFullYear();

            
            $('#EtatCongesCol').append('<tr><td class="mesCol">'+msg1+'</td></tr>');
            $('#EtatCongesCol').append('<tr><td class="mesCol2">'+msg2+'</td></tr>');
        }
    </script>
</html>