<?php
//  1   |   CREPU   |   Génération d'un PDF
//  2   |  MULCEY   |   Modifications générales

//require_once ('../inc/verif_session.php');
require_once ('../inc/connection.php');
require_once ('invoice.php');
include ('../calendrier/fonction_nomMois.php');
include ('../calendrier/fonction_nbjoursMois.php');


$qHist = "SELECT * FROM HISTDOC WHERE HID_TYPE='FAC' AND HID_IDDOC =".$_POST['recherche'];
$rHist = $GLOBALS['connexion']->query($qHist);
if ($rHist != null){
    $res = $rHist->fetch_assoc();
}else{
    $row = $GLOBALS['connexion']->query('SELECT * FROM FACTURE WHERE FAC_NO="'.$_GET['recherche'].'"')->fetch_assoc();

    $query_modereg = 'SELECT * FROM MODEREGLEMENT WHERE MOR_NO ="'.$row['FAC_MODE_REG'].'"';
    $stmt_modereg = $GLOBALS['connexion']->query($query_modereg)->fetch_assoc();

    if($row['FAC_NUM'] != ""){
        $FAC_NUM = $row['FAC_NUM'];
    }else{
        $FAC_NUM = $row['FAC_DEV'];
    }
    $FAC_MOIS = $row['FAC_MOIS'];
    $FAC_ANNEE = $row['FAC_ANNEE'];
    $libmois = nomMois($FAC_MOIS);

    
    $FAC_DEV = $row['FAC_DEV'];
    $COL_NO = $row['COL_NO'];
    $CLI_NO = $row['CLI_NO'];
    $ENT_NO = $row['ENT_NO'];
    $BAN_NO = $row['BAN_NO'];
    $FAC_CODFOU = $row['FAC_CODFOU'];
    $PRO_NO = $row['PRO_NO'];
    $CTC_NO = $row['CTC_NO'];
    $FAC_ANNEE = $row['FAC_ANNEE'];
    $FAC_MOIS = $row['FAC_MOIS'];
    $FAC_PERIODE = $row['FAC_PERIODE'];
    $FAC_DATE = '';
    $FAC_COMMENTAIRE = '';
    
    if(isset($row['FAC_MT_AVOIR'])){
        $FAC_DATE = date('d-m-Y');
        $FAC_DATE = str_replace('-', '/', $FAC_DATE);
        $FAC_COMMENTAIRE = $row['FAC_AVO_COM'];
    }
    else
        $FAC_DATE = substr($row['FAC_DATE'], 8, 2) . "/" . substr($row['FAC_DATE'], 5, 2) . "/" .substr($row['FAC_DATE'], 0, 4);
    
    $FAC_SUIVIPAR = $row['FAC_SUIVIPAR'];
    $FAC_MODE_REG = $row['FAC_MODE_REG'];
    $FAC_NOMCOM = $row['FAC_NOMCOM'];
    $FAC_NOMCLI = $row['FAC_NOMCLI'];
    $FAC_CODCLI = $row['FAC_CODCLI'];
    $FAC_ADR1 = $row['FAC_ADR1'];
    $FAC_ADR2 = $row['FAC_ADR2'];
    $FAC_CP = $row['FAC_CP'];
    $FAC_VILLE = $row['FAC_VILLE'];
    $FAC_NOMCTC = $row['FAC_NOMCTC'];
    $FAC_NOMPRO = $row['FAC_NOMPRO'];
    $FAC_PRODETAIL = $row['FAC_PRODETAIL'];
    $FAC_NUMCMDE = $row['FAC_NUMCMDE'];
    $FAC_ELEM = $row['FAC_ELEM'] * 1;
    $FAC_TOTAL_HT = $row['FAC_TOTAL_HT'] * 1;
    $FAC_MONTANT_TVA = $row['FAC_MONTANT_TVA'] * 1;
    $FAC_TOTAL_TTC = $row['FAC_TOTAL_TTC'] * 1;
    $FAC_MT_AVO = '';
    if(isset($row['FAC_MT_AVOIR']))
        $FAC_MT_AVO = $row['FAC_MT_AVOIR'];
    $FAC_TAUXTVA = $row['FAC_TAUXTVA'];
    $FAC_COMPTEUR = $row['FAC_COMPTEUR'];
    
    //Mode de règlement
    $reg = $stmt_modereg['MOR_LIBELLE'];
    $tauxTVA = $FAC_TAUXTVA;
    $tauxTVA .= '%';

    $i = 1;
    if($row['FAC_AVO'] != ""){
        $designation[] = array($FAC_NOMPRO, $FAC_PRODETAIL, $FAC_MT_AVO);
    }
    else{
        $designation[] = array($FAC_NOMPRO, $FAC_PRODETAIL, $FAC_ELEM);

        while(isset($row['FAC_ELEM_SUP_' . $i]) && !empty($row['FAC_ELEM_SUP_' . $i]))
        {
            $elem = explode("&shy", $row['FAC_ELEM_SUP_' . $i]);
            //Le montant HT représentant le montant du projet principal facturé. Le montantHT étant modifié par les éventuels lignes supplémentaires
            //On retranche donc le montant des éléments supplémentaires afin d'avoir le vrai montant du projet principal
            $designation[] = array($elem[0], $elem[1], $elem[2]);
            $i++;
        }
    }

    $query_ent = 'SELECT * FROM ENTREPRISE WHERE ENT_NO = ' . $row['ENT_NO'];
    $entreprise = $GLOBALS['connexion']->query($query_ent)->fetch_assoc();

    $query_ban = 'SELECT * FROM BANQUE WHERE BAN_NO = ' . $row['BAN_NO'];
    $banque = $GLOBALS['connexion']->query($query_ban)->fetch_assoc();

    $contact = '';

    //Ligne élément facturable
    $line = array( "Période :"    => "Du 1er ".$libmois." ".$FAC_ANNEE." au ".nbjoursMois($FAC_MOIS, $FAC_ANNEE)." ".$libmois." ".$FAC_ANNEE,
                   "Désignation" => "Détails&shyMontant");
    $bottomLine = '';
    if($row['FAC_AVO'] == ""){
        $bottomLine = array("Total HT : " => ' &shy'.sprintf( "%.2F", $FAC_TOTAL_HT),
                   "TVA aux taux légal en vigueur :" => sprintf( "%.2F", $tauxTVA) . ' %&shy' . sprintf( "%.2F", $FAC_MONTANT_TVA),
                   "Total TTC :" => ' &shy' . sprintf( "%.2F", $FAC_TOTAL_TTC));
    }
    else
    {
        $bottomLine = array("Total HT : " => ' &shy'.sprintf( "%.2F", $FAC_MT_AVO),
                   "TVA aux taux légal en vigueur :" => sprintf( "%.2F", $tauxTVA) . ' %&shy' . sprintf( "%.2F", $FAC_MT_AVO*($FAC_TAUXTVA/100)),
                   "Total TTC :" => ' &shy' . sprintf( "%.2F", $FAC_MT_AVO*(1+($FAC_TAUXTVA/100))));
    }

}
// Libellé du document FACTURE
$query_libdoc = "SELECT * FROM LIBDOCUMENT L LEFT JOIN DOCUMENT D ON D.DOC_NO = L.DOC_NO WHERE D.DOC_CODE='FAC' ORDER BY LDO_ORDRE";
$result_libdoc = $GLOBALS['connexion']->query($query_libdoc);
while($lib = $result_libdoc->fetch_assoc()){
    $titre[] = $lib['LDO_NOM'];
    $pave[]  = $lib['LDO_CONTENU'];
}
//var_dump($titre);
//var_dump($pave);

// Mise en page du document
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->addSociete($entreprise);

//Si FAC_NUM, c'est une facture
$nbFac = strlen($FAC_NUM);
if($row['FAC_NUM'] == "")
{
    $pdf->fact_dev( "Proforma", $FAC_NUM, $FAC_DATE, $entreprise['ENT_VILLE']);
    $pdf->temporaire( "PROFORMA" );
    $nomfic = "impressions/" . $FAC_NUM . ".pdf";
}
else
{
    if($row['FAC_AVO'] != "")
        $pdf->fact_dev( "Avoir", $FAC_NUM, $FAC_DATE, $entreprise['ENT_VILLE']);
    else
        $pdf->fact_dev( "Facture", $FAC_NUM, $FAC_DATE, $entreprise['ENT_VILLE']);
    $nomfic = "impressions/" . $FAC_NUM . "-" . $FAC_COMPTEUR . ".pdf";
}

$pdf->addClient($FAC_NOMCLI, $FAC_ADR1, $FAC_ADR2, $FAC_CP, $FAC_VILLE);
if($row['FAC_AVO'] == "")
    $pdf->addReference($FAC_NUMCMDE, $FAC_CODCLI, $FAC_NOMCTC, $FAC_CODFOU, $FAC_NOMCOM);
else
    $pdf->addReference($FAC_NUMCMDE, $FAC_CODCLI, $FAC_NOMCTC, $FAC_CODFOU, $FAC_NOMCOM, $FAC_NUM);
    


////////////////////////////
//Ajout élément à facturer//
////////////////////////////
//
$y = 80;

if($row['FAC_AVO'] != ""){
    $size = $pdf->addLine( $y, $line, $designation, $bottomLine, $FAC_COMMENTAIRE);
}
else
{
    $size = $pdf->addLine( $y, $line, $designation, $bottomLine);
}

//$pdf->conditionGeneral($FAC_CON_VENTE.' ', $FAC_PENALITE.' ', $contact, $banque, $entreprise, $FAC_REG);
if($row['FAC_AVO'] == "")
    $pdf->conditionGeneral($entreprise['ENT_LOGOPIED'], $entreprise, $titre[0], $pave[0], $titre[1], $pave[1], $contact, $banque, $reg);
else
     $pdf->conditionGeneral($entreprise['ENT_LOGOPIED'], $entreprise);
if ($row['FAC_NUM'] != ''){
    switch ($FAC_COMPTEUR) {
        case 0 :
            $nom = 'Exemplaire client';
            break;
        case 1 :
            $nom = 'Exemplaire ' . $entreprise['ENT_NOM'];
            break;
        default :
            $nom = 'DUPLICATA';
            break;
    }
    $pdf->compteur($nom);
}

$pdf->Output($nomfic, 'IF');

// Mise à jour du compteur d'impression de facture 
if ($row['FAC_NUM'] != ''){
    // SI ON ARRIVE LA, C'EST QUE LA FACTURE EST IMPRIMEE CORRECTEMENT
    $FAC_COMPTEUR = $FAC_COMPTEUR + 1;
    $qmajfac = 'UPDATE FACTURE SET FAC_COMPTEUR="' . $FAC_COMPTEUR .'" WHERE FAC_NO='.$row['FAC_NO'];
    $rmajfac = $GLOBALS['connexion']->query($qmajfac);
}
?>