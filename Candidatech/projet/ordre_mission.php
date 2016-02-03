<?php

require('../pdf/fpdf.php');
define ('EURO', chr (128));
define ('EURO_VAL', 6.55957);

// Paul-andré Mulcey 2014
// Version 1.00
//

//////////////////////////////////////
// fonctions à utiliser (publiques) //
//////////////////////////////////////
//  function addEntete( $num )
//  function addCollaborateur( $nomcol )
//  function addClient( $nomcli, $adrcli )
//  function addReference( $nompro, $nomctc, $nomctf )
//  function addDetail( $detail )
//  function addPieddepage($date)

class PDF_Mission extends FPDF
{
// variables privées
    var $posY;

// Cette fonction affiche en haut, a gauche,
// le logo de la societe  et l'entête de d'ordre de mission avec son No
    function addEntete ($num, $lib, $periode)
    {
        // Logo
        $file = "../image/LogoApsa.jpg";
        $this->Image($file, 8, 10, 65, 13);
        //Positionnement titre
        $x1 = 7;
        $y1 = 25;
        $this->SetXY ($x1, $y1);
        $this->SetFont ('Arial', 'B', 16);
        $this->Cell (149, 10, 'ORDRE DE MISSION    ' . $periode, 1);
        
        $x1 = 156;
        $this->SetXY ($x1, $y1);
        $this->SetFont ('Arial', '', 13);
        $this->Cell (46, 10, $num, 1, 0, 'C');

        $this->SetFont ('Times', '', 12);
        $this->SetXY (7, 38);
        $this->MultiCell(195, 5, $lib, $border=1, $align='J', $fill=false);
        $this->posY = $this->GetY();
    }

    function addCollaborateur ($nomcol)
    {
        $this->SetFont ('Arial', 'B', 12);
        $x1 = 7;
        $y1 = $this->posY + 3;
        $this->SetXY ($x1, $y1);
        $this->Cell (96, 15, 'Collaborateur : ' . $nomcol, 1);
    }

    function addClient ($nomcli, $adrcli)
    {
        $this->SetFont ('Arial', 'B', 12);
        $x1 = 7;
        $y1 = $this->posY + 3;
        $this->SetXY ($x1 + 98, $y1);
        $this->Cell (97, 15, 'Client : ' . $nomcli, 1);
        $this->SetFont ('Arial', '', 11);
        $y1 = $this->posY + 21;
        $this->SetXY ($x1, $y1);
        $this->Cell (195, 9, 'Lieu / Adresse de la prestation : ' . $adrcli, 1);
        $this->posY = $y1 + 9;
    }
    
    function addReference ($nompro, $nomctc, $nomctf)
    {
        $this->SetFont ('Arial', '', 11);
        $x1 = 7;
        //$y1 = 132;
        $y1 = $this->posY + 3;
        $this->SetXY ($x1, $y1);
        $this->Cell (195, 34, '', 1);
        $this->underline = true;
        $this->Text (8, $y1 + 5, 'Référence de la mission : ');
        $this->underline = false;
        $this->Text (8, $y1 + 11, 'Projet : ' . $nompro);
        $this->SetFont ('Arial', 'B', 11);
        if ($nomctc != '') $nomctc = 'Contact client      : ' . $nomctc;
        if ($nomctf != '') $nomctf = 'Contact fournisseur : ' . $nomctf;
        $this->Text (8, $y1 + 20, $nomctc);
        $this->Text (8, $y1 + 28, $nomctf);
        $this->SetFont ('Arial', '', 11);
        $this->posY = $y1 + 34;
    }

    function addDetail ($nbjours, $detail, $frais)
    {
        $x1 = 7;
        //$y1 = 181;
        $y1 = $this->posY + 3;
        $this->SetXY ($x1, $y1);
        $this->Cell (195, 64, '', 1);
        
        // Mise en place du détail.
        $this->SetFont ('Arial', 'B', 12);
        $this->underline = true;
        $this->Text (8, $y1 + 5, 'Détail de la mission : ');
        $this->underline = false;
        $this->Text (90, $y1 + 5, $nbjours);
        $this->SetFont ('Arial', '', 11);
        $s = str_replace("\r","",$detail);
        $tablig = explode("\n", $s);
        $z1 = $y1 + 11;
        foreach ($tablig as $lig){
            $this->Text (9, $z1, $lig);
            $z1 += 6;
        }
        $y1 += 32;
        // Mise en place du détail.
        $this->SetFont ('Arial', 'B', 12);
        $this->underline = true;
        $this->Text (8, $y1 + 5, 'Modalités : ');
        $this->SetFont ('Arial', '', 12);
        $this->underline = false;
        $this->SetFont ('Arial', '', 11);
        $s = str_replace("\r","",$frais);
        $tablig = explode("\n", $s);
        $z1 = $y1 + 11;
        foreach ($tablig as $lig){
            $this->Text (9, $z1, $lig);
            $z1 += 6;
        }
        $y1 += 37;
        $this->SetXY ($x1, $y1);
        $this->posY = $this->GetY();
    }
    
    function addPieddepage ($date, $file)
    {
        $x1 = 9;
        //$y1 = 230;
        $y1 = $this->posY + 3;
        $this->SetXY ($x1, $y1);
        $this->Text (8, $y1, 'A Lyon, le ' . $date);
        $this->SetFont ('helvetica', 'BI', 12);
        $this->Image($file, 150, $y1 - 3 , 35, 14);
    }

}
?>
