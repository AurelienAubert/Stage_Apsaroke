<?php
// (c) Paul-André MULCEY
// Impression collaborateur : Contrat de travail à durée indéterminée

include_once "../inc/connection.php";
require('ct_cdi.php');

if (isset($_GET['iddoc']) ){
    $iddoc = $_GET['iddoc'];
    // Lecture du document avant impression
    $query = "SELECT * FROM HISTDOC WHERE HID_NO=" . $iddoc;
    $row = $GLOBALS['connexion']->query($query)->fetch_assoc();
    $cont = $row['HID_CONTENU'];

    $arrdoc = explode('§§', $cont);
    foreach ($arrdoc as $texte){
        $arrlig = explode('##', $texte);
        $a = str_replace(']', '', str_replace('[', '', $arrlig[0]));
        ${$a} = $arrlig[1];
        //echo $a . "-" . ${$a}.'<br>';
    }
    $nomfic = "impressions/" . $numdoc . ".pdf";

    // CONTRAT DE TRAVAIL A DUREE INDETERMINEE
    $pdf = new PDF_CDI( 'P', 'mm', 'A4' );
    $pdf->AddPage();
    $pdf->AddFont('mistral');

    $pdf->addEntete($nomcol, $natcol, $adrcol, $nsscol);
    $pdf->AddPage();
    $pdf->page2($t0, $p0, $t1, $p1, $t2, $p2, $t3, $p3);
    $pdf->AddPage();
    $pdf->page3($t4, $p4, $t5, $p5, $t6, $p6);
    $pdf->AddPage();
    $pdf->page4($t7, $p7, $t8, $p8, $t9, $p9);
    $pdf->AddPage();
    $pdf->page5($t10, $p10, $t11, $p11, $t12, $p12, $t13, $p13);
    $pdf->AddPage();
    $pdf->page6($t14, $p14, $t15, $p15);
    $pdf->AddPage();
    $pdf->page7($t16, $p16, $t17, $p17, $t18, $p18, $t19, $p19, $p20, $nomcol);
    //$pdf->addPieddepage( $djour, $image );

    // Si ré-édition (si le fichier a déja été généré) pour le moment, on n'écrase pas.
    //  permet en cas de suppréssion de le régénérer : ATTENTION pour les factures
    if(file_exists ($nomfic)){
        $pdf->Output();
    }else{
        $pdf->Output($nomfic, 'IF');
    }

    
}else{
    
}
?>
