<?php
// (c) Paul-Andr� MULCEY
// Impression projet : Ordre de mission

include_once "../inc/connection.php";
require('ordre_mission.php');

if (isset($_GET['iddoc']) ){
    $iddoc = $_GET['iddoc'];
    // Lecture du document avant impression
    $query = "SELECT * FROM HISTDOC WHERE HID_NO=" . $iddoc;
    $row = $GLOBALS['connexion']->query($query)->fetch_assoc();
    $cont = $row['HID_CONTENU'];

    $arrdoc = explode('��', $cont);
    foreach ($arrdoc as $texte){
        $arrlig = explode('##', $texte);
        $a = $arrlig[0];
        ${$a} = $arrlig[1];
        //echo $a . "-" . ${$a}.'<br>';
    }
    $nomfic = "impressions/" . $numdoc . ".pdf";
    //echo $nomfic;
    $numdoc = 'No : ' . $numdoc;
    $image = "../image/Sign_BP_Pdg.jpg";

    $pdf = new PDF_Mission( 'P', 'mm', 'A4' );
    $pdf->AddPage();

    $pdf->addEntete( $numdoc , $article, $periode);
    $pdf->addCollaborateur( $nomcol );
    $pdf->addClient( $nomcli, $adrcli );
    $pdf->addReference ($nompro, $nomctc, $nomctf);
    $pdf->addDetail( $nbjours, $prodet, $promod );
    $pdf->addPieddepage( $djour, $image );

    // Si r�-�dition (si le fichier a d�ja �t� g�n�r�) pour le moment, on n'�crase pas.
    //  permet en cas de suppr�ssion de le r�g�n�rer : ATTENTION pour les factures
    if(file_exists ($nomfic)){
        $pdf->Output();
    }else{
        $pdf->Output($nomfic, 'IF');
    }

    
}else{
    
}
?>
