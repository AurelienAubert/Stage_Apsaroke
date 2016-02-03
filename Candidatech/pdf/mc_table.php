<?php
require('pdf/fpdf.php');

class PDF_MC_Table extends FPDF
{
    
// En-tête  
function Header()
{
    // Logo
    $this->Image('image/LogoApsa.jpg',34,6,70);
    // Saut de ligne
    $this->Ln(15);
    // Police Arial gras 15
    $this->SetFont('Helvetica','B',9);
    // Décalage à droite
    $this->Cell(38);
    // Titre
    $this->Cell(10,10,''.$_POST['titrepdf'].' - '.$_POST['mois'].' '.$_POST['annee'].'',0,0,'C');
    // Saut de ligne
    $this->Ln(6);
    // Décalage à droite
    $this->Cell(34);
    $this->Cell(10,10,''.$_POST['titrejo']. ' : '.$_POST['joursouvres'],0,0,'C');
    // Saut de ligne
    $this->Ln(10);
}

// Pied de page
function Footer()
{
    $this->SetX(48);
    $this->SetFont('Arial','BU',10);
    //recuperation du commentaire general
    $this->Cell(10,10, $_POST['titre'],0,0,'C');
    $this->Ln(8);
    $this->SetX(52);
    $this->SetFont('Arial','',9);
    $this->Cell(10,10, $_POST['commentaire'],0,0,'C');
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'',0,0,'C');
}

var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data, $bordure)
{
    $this->SetX(34);
    // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(161,200,239);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    //Calculate the height of the row
	$nb=0;
        $height=0;
	for($i=0;$i<count($data);$i++)
        {
           $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]['texte']));
        }
         $h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
           $height = ($data[$i]['height']);
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
                $style="D";
                if ($data[$i]['couleur']!='transparent') {
                    $this->SetFillColor(substr($data[$i]['couleur'], 4, 3), substr($data[$i]['couleur'], 9, 3), substr($data[$i]['couleur'], 14, 3));
                    $style .= 'F';
                }
                
                if ($bordure==false && $data[$i]['couleur']!='transparent') {
                    $this->SetDrawColor(substr($data[$i]['couleur'], 4, 3), substr($data[$i]['couleur'], 9, 3), substr($data[$i]['couleur'], 14, 3));
                }
                elseif ($bordure==false) {
                    $this->SetDrawColor(255);
                }
                
                $this->Rect($x,$y,$w,$h, $style);
		//Print the text
		$this->MultiCell($w,5,$data[$i]['texte'],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
                $this->SetDrawColor(0,0,0);
	}
	//Go to the next line
	$this->Ln($h);
        
            // Restauration des couleurs et de la police
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetFont('');
    
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
