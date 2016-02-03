<?php
require_once ('../pdf/fpdf.php');
require_once ('../inc/connection.php');
define('EURO', chr(128) );
define('EURO_VAL', 6.55957 );

// Xavier Nicolay 2004
// Version 1.02
//
// Reste à faire :
// + Multipage (gestion automatique sur plusieurs pages)
// + Ajout de logo
// 

//////////////////////////////////////
// fonctions à utiliser (publiques) //
//////////////////////////////////////
//  function sizeOfText( $texte, $larg )
//  function addSociete( $nom, $adresse )
//  function fact_dev( $libelle, $num )
//  function addproforma( $numdev )
//  function addFacture( $numfact )
//  function addDate( $date )
//  function addClient( $ref )
//  function addPageNumber( $page )
//  function addClientAdresse( $adresse )
//  function addReglement( $mode )
//  function addEcheance( $date )
//  function addNumTVA($tva)
//  function addReference($ref)
//  function addCols( $tab )
//  function addLineFormat( $tab )
//  function lineVert( $tab )
//  function addLine( $ligne, $tab )
//  function addRemarque($remarque)
//  function addCadreTVAs()
//  function addCadreEurosFrancs()
//  function addTVAs( $params, $tab_tva, $invoice )
//  function temporaire( $texte )

class PDF_Invoice extends FPDF
{
// variables privées
//var $colonnes;
//var $format;
var $angle=0;

// Cette fonction affiche en haut, a gauche,
// le nom de la societe dans la police Arial-12-Bold
// les coordonnees de la societe dans la police Arial-10
function addSociete($entreprise)
{
   // print_r($entreprise);
    $this->Image('../' . $entreprise['ENT_LOGO'], 10, 8, 100, 30);
}

// Affiche en haut, a droite le libelle
// (FACTURE, proforma, Bon de commande, etc...)
// et son numero
// La taille de la fonte est auto-adaptee au cadre
function fact_dev( $libelle, $num, $date, $lieu)
{
    $r1  = $this->w - 80;
    $r2  = $r1 + 68;
    //$y1  = 6;
    $y1  = 16;
    $y2  = $y1 + 2;
    $mid = ($r1 + $r2 ) / 2;

    
    $szfont = 12;
    $loop   = 0;
    
    $this->SetXY($r1, $y1);
    //$this->Cell($r1, 'rtttt');
    
    
    $texte = $lieu . ", le " . $date;
    
    while ( $loop == 0 )
    {
       $this->SetFont( "Arial", "B", $szfont );
       $sz = $this->GetStringWidth( $texte );
       if ( ($r1 + $sz) > $r2 )
          $szfont --;
       else
          $loop ++;
    }

    $this->SetFont ($this->police, '', 8);
    $this->Text(170, $y1, $texte);

    $texte  = $libelle  . " N° : " . $num;    
    $this->SetFont ($this->police, 'B', 11);
    $this->SetLineWidth(0.1);
    $this->SetFillColor(192);
    //$this->RoundedRect($r1, $y1 + 4, ($r2 - $r1), $y2, 2.5, 'DF');
    $this->RoundedRect($r1, $y1 + 4, ($r2 - $r1), 8, 2.5, 'DF');
    $this->SetXY( $r1 + 1, $y1 + 6);
    $this->Cell($r2 - $r1 -1, 5, $texte, 0, 0, "C" );
}

// Genere automatiquement un numero de proforma
function addproforma( $numdev )
{
	$string = sprintf("DEV%04d", $numdev);
	$this->fact_dev( "proforma", $string );
}

// Genere automatiquement un numero de facture
function addFacture( $numfact )
{
	$string = sprintf("FA%04d", $numfact);
	$this->fact_dev("Facture", $string );
}

// Affiche un cadre avec les references du client
// (en haut, a droite)
function addClient($nomClient, $adresse1, $adresse2, $cp, $ville)
{
	$r1  = $this->w - 80;
	$r2  = $r1 + 68;
	$y1  = 30;
	$y2  = 27;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
        
	$this->SetXY($r1 + 4, $y1+2 );
	$this->SetFont("Arial", "B", 13);
	$this->Cell(42, 5, $nomClient, 0, 0, "");
        $this->SetXY($r1 + 4, $y1+9);
	$this->SetFont("Arial", "", 9);
	$this->Cell(42, 5, $adresse1, 0, 0, "");
        $this->SetXY($r1 + 4, $y1+14 );
	//$this->SetFont("Arial", "", 9);
	$this->Cell(42, 5, $adresse2, 0, 0, "");
        $this->SetXY($r1 + 4, $y1+19 );
	//$this->SetFont("Arial", "", 9);
	$this->Cell(42, 5, $cp . "   " . $ville, 0, 0, "");
}

// Affiche un cadre avec un numero de page
// (en haut, a droite)
function addPageNumber( $page )
{
	$r1  = $this->w - 80;
	$r2  = $r1 + 19;
	$y1  = 17;
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,5, "PAGE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$page, 0,0, "C");
}

// Affiche une ligne avec des reference
// (en haut, a gauche)
function addReference($objet, $codeCli, $contactCli, $codeFour, $affaireSuivi, $facNumAvo=null)
{
	$this->SetFont( "Arial", "", 10);
	$length = $this->GetStringWidth( "Références : " . $objet );
	$r1  = 10;
	$r2  = $r1 + $length;
	$y1  = 40;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->Cell($length,5, "Objet : " . $objet);
	$this->SetXY( $r1 , $y1+5);
        $this->Cell($length,5, "Code client : " . $codeCli);
        $this->SetXY( $r1 , $y1+10 );
	$this->Cell($length,5, "Contact client : " . $contactCli);
	$this->SetXY( $r1 , $y1+15);
        $this->Cell($length,5, "Code fournisseur : " . $codeFour);
	$this->SetXY( $r1 , $y1+20);
        $this->Cell($length,5, "Affaire suivi par : " . $affaireSuivi);
        if(isset($facNumAvo)){
            $this->SetXY( $r1 , $y1+25);
            $this->Cell($length,5, "Numéro de facture avoirée : " . $facNumAvo);
        }
        
}

// Affiche chaque "ligne" d'un proforma / facture
/*    $ligne = array( "REFERENCE"    => $prod["ref"],
                      "DESIGNATION"  => $libelle,
                      "QUANTITE"     => sprintf( "%.2F", $prod["qte"]) ,
                      "P.U. HT"      => sprintf( "%.2F", $prod["px_unit"]),
                      "MONTANT H.T." => sprintf ( "%.2F", $prod["qte"] * $prod["px_unit"]) ,
                      "TVA"          => $prod["tva"] );
*/
function addLine($y, $line, $designation, $bottomLine, $commentaire=null)
{
    $r1  = 10;
    $r2  = $this->w - ($r1 * 2) ;
    $y1  = 70;
    //Réduire la taille du cadre principale
    $y2  = $this->h - 139 - $y1;
    $this->SetXY( $r1, $y1 );
    //$this->Rect( $r1, $y1, $r2, $y2, 'D');
    $colX = $r1;
    
    $this->Line(10, 80, 10, 170);
    $this->Line(200, 80, 200, 170);

    $this->SetFont('Arial','B',10);
    //En-tête
    while ( list( $lib, $pos ) = each ($line) )
    {
            $this->SetXY( $colX, $y1 );
            $this->Cell(55, 8, $lib, 1, 0, "C");
            
            if(strpos($pos, '&shy'))
            {
                $posElem = explode('&shy', $pos);
                $this->Cell(100, 8, "  " . $posElem[0], 1, 0, "");
                $this->Cell(35, 8, $posElem[1], 1, 0, "C");
            }
            else
            {
              $this->Cell(135, 8,"  " . $pos,1,0);
            }
            $y1+=8;
    }
    $this->SetFont('Arial','',10);

    //Element supplémentaire
    foreach($designation as $element)
    {
        $this->SetXY( $colX, $y1 );
        $this->Cell(55, 8, $element[0], 1, 0, 'C');
        $this->Cell(100, 8, "  " . $element[1], 1, 0, '');
        if (floatval($element[2]) > 0){
            $this->Cell(35, 8, number_format($element[2], 2, ',', ' ') . ' ' . EURO . '   ', 1, 0, 'R', false);
        }else{
            $this->Cell(35, 8, '', 1, 0, 'R', false);
        }
        $y1+=8;
    }
    $y1 = 146;
    $this->SetFont('Arial','B',10);
    //Pied de page
    while(list($firstElem, $secondElem) = each($bottomLine))
    {
        $this->SetXY( $colX, $y1 );
            $this->Cell(55, 8, $firstElem, 1, 0, "C");
            
            if(strpos($secondElem, '&shy'))
            {
                $posElem = explode('&shy', $secondElem);
                $this->Cell(100, 8, "  " . $posElem[0], 1, 0, "");
                $this->Cell(35, 8, number_format($posElem[1], 2, ',', ' ') . ' ' . EURO . '   ', 1, 0, "R", false);
            }
            else
            {
              $this->Cell(135, 8, $secondElem, 1, 0);
            }
            $y1+=8;
    }
    if($commentaire !== null){
        $this->SetXY( $r1, $y1 );
        $this->Cell(10, 10, 'Raison de l\'avoir : '.$commentaire);
    }
}

// Ajoute une remarque (en bas, a gauche)
function addRemarque($remarque)
{
	$this->SetFont( "Arial", "", 10);
	$length = $this->GetStringWidth( "Remarque : " . $remarque );
	$r1  = 10;
	$r2  = $r1 + $length;
	$y1  = $this->h - 45.5;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->Cell($length,4, "Remarque : " . $remarque);
}

// Permet de rajouter un commentaire (proforma temporaire, REGLE, DUPLICATA, ...)
// en sous-impression
// ATTENTION: APPELER CETTE FONCTION EN PREMIER
function temporaire($texte)
{
	$this->Rotate(-40,0,0);
	$this->SetFont('Arial','I',110);
	$this->SetTextColor(203,203,203);
        $this->SetXY(60,40);
        $this->Cell(5,4, $texte);
	$this->Text(55,190,'');
	$this->SetTextColor(0,0,0);
	$this->Rotate(0,0,0);
}
function compteur($nom){
    // En cas de facture, exemplaire imprimé (client ou société), duplicata si + de 2
    $this->SetFont('arial', 'BU', 11);
    $this->SetXY(125, 218,'');
    $this->Cell(5, 5, 'Imprimée en 2 exemplaires originaux');
    $this->SetFont('arial', 'B', 9);
    $this->SetXY(125, 224, '');
    $this->Cell(70, 5, $nom, 0, 0, 'C');
}

function conditionGeneral($logopied, $entreprise, $titre1=null, $condition=null, $titre2=null, $penalite=null, $contact=null, $banque=null, $reg=null)
{
    // Conditions de vente
    if($titre1 != null){
        $this->SetFont('arial', 'BU', 10);
        $this->SetXY(10, 174,'');
        $this->Cell(5, 4, $titre1 . ' :');
        $this->SetFont('arial','',10);
    }
    
    if($condition != null){
        $this->SetFont ('arial', '', 9);
        $this->SetXY (10, 179);
        $this->MultiCell(185, 3, $condition, $border=0, $align='J', $fill=false);
        $this->posY = $this->GetY();
    }
    
    // Pénalités
    if($titre2 != null){
        $this->SetFont('arial', 'BU', 10);
        $this->SetXY(10, 184,'');
        $this->Cell( 5, 4, $titre2 . ' :');
        $this->SetFont('arial','',9);
    }
    if($penalite != null){
        $this->SetXY (10, 189);
        $this->MultiCell(185, 3, $penalite, $border=0, $align='J', $fill=false);
        $this->posY = $this->GetY();
    }

    // Mode de règlement
    if($condition != null){
        $this->SetFont('arial', 'BU', 10);
        $this->SetXY(10, 216,'');
        $this->Cell(5,4, 'Mode de règlement');
        $this->SetFont('arial', 'B', 9);
        $this->SetXY(10, 220,'');
    }
    
    if($reg != null){
        // Ligne escompte
        $this->Cell(5,4, $reg);
        $this->SetXY(10, 224, '');
        $this->Cell(5,4, 'Pas d\'escompte en cas de paiement anticipé.');
    }
    
    if($banque != null){
        $this->RoundedRect(9, 232, 190, 20, 0, 'D' );
        $this->Line(9, 242, 199, 242);

        $this->SetFont('arial', 'B', '9');
        $this->SetXY(10, 233,'');
        $this->Cell( 5,4, 'Coordonnées bancaires '.$entreprise['ENT_NOM'].' '.$entreprise['ENT_STATUT']);
        $this->SetXY(10, 237,'');
        $this->Cell( 5,4, $banque['BAN_NOM']);
        $this->SetXY(30, 243);
        $this->Cell( 5,4, 'Code banque');
        $this->SetXY(70, 243);
        $this->Cell( 5,4, 'Code Guichet');
        $this->SetXY(110, 243);
        $this->Cell( 5,4, 'N° de compte');
        $this->SetXY(150, 243);
        $this->Cell( 5,4, 'Clé RIB');
        $this->SetXY(35, 247);
        $this->Cell( 5,4, $banque['BAN_CDE_BAN']);
        $this->SetXY(77, 247);
        $this->Cell( 5,4, $banque['BAN_CDE_GUI']);
        $this->SetXY(110, 247);
        $this->Cell( 5,4, $banque['BAN_NUM_CPT']);
        $this->SetXY(155, 247);
        $this->Cell( 5,4, $banque['BAN_RIB']);
    }
    
    $this->SetFont('arial', 'B', 7);
    $this->SetXY(10, 258);
    $this->Cell(180, 3, $entreprise['ENT_NOM'].' '.$entreprise['ENT_STATUT'].' au capital de '.$entreprise['ENT_CAPITAL'].'' .EURO,'','','C');
    $this->SetFont('arial', '', 7);
    $this->SetXY(10, 261);
    $this->Cell(180, 3, 'Siège social : '.$entreprise['ENT_ADRESSE'].' - '.$entreprise['ENT_VILLE'].' Tél :'.$entreprise['ENT_TEL'],'','','C');
    $this->SetXY(10, 264);
    $this->Cell(180, 3, 'Numéro de TVA Intra Communautaire : '.$entreprise['ENT_TVA_INTRA'],'','B','C');
    $this->SetXY(10, 267);
    $this->Cell(180, 3, 'RCS LYON : '.$entreprise['ENT_RCS'].' SIRET : '.$entreprise['ENT_SIRET'].' APE : '.$entreprise['ENT_APE'],'','B','C');
    $this->SetXY(10, 270);
    $this->Cell(180, 3, $entreprise['ENT_SITE_WEB'],'','B','C');
    
    $this->Image('../' . $logopied, 180, 256, 18, 20);
}

// fonctions privées
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
	$k = $this->k;
	$hp = $this->h;
	if($style=='F')
		$op='f';
	elseif($style=='FD' || $style=='DF')
		$op='B';
	else
		$op='S';
	$MyArc = 4/3 * (sqrt(2) - 1);
	$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
	$xc = $x+$w-$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

	$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
	$xc = $x+$w-$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
	$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
	$xc = $x+$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
	$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
	$xc = $x+$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
	$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
	$this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
	$h = $this->h;
	$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
						$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle, $x=-1, $y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

}