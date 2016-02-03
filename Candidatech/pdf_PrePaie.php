<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('pdf/mc_table.php');

// Chargement des données
$data = json_decode(utf8_decode($_POST['tab_prepaie']), true);

$pdf=new PDF_MC_Table('L', 'mm', 'A3');
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
//Table with 20 rows and 4 columns
if($_POST['mois'] == 'Novembre')
{
    $pdf->SetWidths(array(34,14,18,14,14,18,18,20,18,14,14,14,22,24,23,16,16,60));
}
else
{
    $pdf->SetWidths(array(34,14,18,14,14,18,18,20,18,14,14,14,24,23,16,16,60));
}

$bordure = false;
foreach ($data as $ligne) {
    $pdf->Row($ligne, $bordure);
    $bordure=true;
}

$pdf->Output();

?>

