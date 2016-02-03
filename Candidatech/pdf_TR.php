<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require ('pdf/fpdf.php');
//print_r(($_POST['tab_prepaie']));
//var_dump(json_decode(utf8_decode($_POST['tab_prepaie'])));
//var_dump(json_decode('[["Collaborateurs","JW","ABS","CP","RTT","MAL","SS","WE","Nb.TR","GSM","PEE","13ème\nmois","Commission","Accompte/\nAvance/\nRégularisation\nà déduire","Prime","Commentaires"],["PEYRIN","22","0"," "," "," "," ","11/1/2014\n18/1/2014\n","0","0€","42.50€","Non","1","1","1","1"],["CACCIATORE","21","1","\n 2/1/2014\n "," "," "," ","11/1/2014\n","21","0€","42.50€","Oui","","","",""],["AUGAGNEUR","9","13","\n 2/1/2014\n3/1/2014\n17/1/2014\n "," ","\n 20/1/2014\n21/1/2014\n22/1/2014\n23/1/2014\n24/1/2014\n27/1/2014\n28/1/2014\n29/1/2014\n30/1/2014\n31/1/2014\n "," ","","9","30.00€","42.50€","Oui","","","",""],["BERTOUT","20","2"," ","\n 2/1/2014\n3/1/2014\n "," "," ","","20","30.00€","42.50€","Oui","","","",""],["CORONEL","22","0"," "," "," "," ","","22","30.00€","42.50€","Oui","","","",""],["COMBY","22","0"," "," "," "," ","","0","0€","0€","Non","","","",""],["DESVIGNES FAURE","20","2","\n 3/1/2014\n ","\n 2/1/2014\n "," "," ","","20","30.00€","42.50€","Oui","","","",""],["GELAN","22","0"," "," "," "," ","","22","30.00€","42.50€","Non","","","",""],["MOLLIARD","19","3","\n 2/1/2014\n3/1/2014\n ","\n 31/1/2014\n "," "," ","","19","30.00€","42.50€","Oui","","","",""],["MULCEY","21","1","\n 16/1/2014\n "," "," "," ","","0","30.00€","42.50€","Oui","","","",""],["PEPIN","22","0"," "," "," "," ","","0","0€","0€","Non","","","",""],["SALOMON","22","0"," "," "," "," ","","22","0€","42.50€","Oui","","","",""],["TARI","21","1","\n 2/1/2014\n "," "," "," ","25/1/2014\n26/1/2014\n","21","30.00€","42.50€","Oui","","","",""],["TRAN","18","4","\n 2/1/2014\n3/1/2014\n "," ","\n 6/1/2014\n7/1/2014\n "," ","","18","30.00€","42.50€","Oui","","","",""],["GROSS","22","0"," "," "," "," ","","22","30.00€","0€","Non","","","",""],["TEST","22","0"," "," "," "," ","","0","0€","0€","Non","","","",""],["CATHONNET","22","0"," "," "," "," ","","0","0€","0€","Non","","","",""]]'));
class PDF extends FPDF
{
// En-tête  
function Header()
{
    // Logo
    $this->Image('image/LogoApsa.jpg',10,6,40);
    // Saut de ligne
    $this->Ln(8);
    // Police Arial gras 15
    $this->SetFont('Arial','B',10);
    // Décalage à droite
    $this->Cell(36);
    // Titre
    $this->Cell(10,10,'TICKETS RESTAURANTS - '.$_POST['mois'].' '.$_POST['annee'].'',0,0,'C');
    // Saut de ligne
    $this->Ln(8);
    // Décalage à droite
    $this->Cell(20);
    $this->Cell(10,10,$_POST['titrejo'].' '.$_POST['joursouvres'],0,0,'C');
    // Saut de ligne
    $this->Ln(1);
}

// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'',0,0,'C');
}
// Tableau coloré
function FancyTable($data)
{
    $this->SetY(46);
    // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(161,200,239);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // En-tête
    $w = array(38,20,20,20,20,60);
    
    for($j=0;$j<count($data);$j++)
    {   
        $this->SetX(15);
        for($i=0; $i<count($w);$i++)
        {
          $this->Cell($w[$i],11,$data[$j][$i],1,0,'C',true);
        }
        $this->Ln();
        
    // Restauration des couleurs et de la police
    $this->SetX(15);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont(''); 
    } 
    // Trait de terminaison
    $this->MultiCell(array_sum($w),0,'','T');
}
}
$pdf = new PDF('P','mm','A4');
// Titres des colonnes
//$header = array('Collaborateurs', 'JW', 'ABS', 'CP','RTT','MAL.','SS','WE','Nb.TR','GSM','PEE','13eme mois','Commission','Avance','Prime','Commentaires');
// Chargement des données
$data = json_decode(utf8_decode($_POST['tab_tr']));

$pdf->SetFont('Courier','',11);
$pdf->AddPage();
$pdf->FancyTable($data);
$pdf->Output();
?>