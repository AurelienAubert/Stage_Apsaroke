<?php
require('../pdf/fpdfPlus.php');

define ('EURO', chr (128));
define ('EURO_VAL', 6.55957);

// Paul-andré Mulcey 2014
// Version 1.00
//

//////////////////////////////////////
// fonctions à utiliser (publiques) //
//////////////////////////////////////
//  function addEntete( $num )
//  function page2( $titre1, $parag1, $t2, $p2, $t3, $p3, $t4, $p4 ) les titres et articles de la page
//  function page3( $titre1, $parag1, $t2, $p2, $t3, $p3 )
//  function page4( $titre1, $parag1, $t2, $p2, $t3, $p3 )
//  function page5( $titre1, $parag1, $t2, $p2, $t3, $p3, $t4, $p4 )
//  function page6( $titre1, $parag1, $t2, $p2 )
//  function page7( $titre1, $parag1, $t2, $p2, $t3, $p3, $t4, $p4, $txtfin )
//  function addPieddepage($date)
//  function addPieddepage($date)
//  function addPieddepage($date)
//  function addFinDocument($date)

class PDF_CDI extends FPDF2
{
// variables privées
    var $posY;
    var $police = 'times';
    var $ecart = 20;

// Cette fonction affiche en haut, a gauche,
// le logo de la societe  et l'entête du contrat avec son No
    function addEntete ($nomcol, $natcol, $adrcol, $nsscol)
    {
        // Logo
        $file = "../image/LogoApsa.jpg";
        $this->Image($file, 8, 10, 130, 26);
        //Positionnement titre
        $x1 = 10;
        $y1 = 45;
        $this->SetXY ($x1, $y1);
        $this->SetFont ($this->police, 'B', 16);
        $this->SetFillColor(15, 5, 107);
        $this->SetTextColor(255, 255, 255);
        $this->Cell (190, 14, 'CONTRAT DE TRAVAIL A DUREE', 0, 0, 'C', true);
        $y1 = 55;
        $this->SetXY ($x1, $y1);
        $this->Cell (190, 14, 'INDETERMINEE', 0, 0, 'C', true);

        // Pavé APSAROKE
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont ($this->police, '', 11);
        $y1 = 90;
        $this->Text($x1, $y1, 'Entre les soussignés');
        $y1 = 110;
//        $this->Text($x1, $y1, 'La société');
//        $this->SetFont ($this->police, 'BI', 11);
//        $this->Text($x1 + 18, $y1, 'APSAROKE');
//        $this->SetFont ($this->police, '', 11);
//        $this->Text($x1 + 41, $y1, 'société par actions simplifiées au capital de 39 000 ' . EURO . ',');

        $this->SetXY ($x1 - 1, $y1 - 3.5);
        $html = 'La société <b><i>APSAROKE</i></b> société par actions simplifiées au capital de 39 000 ' . EURO . ',';
        $this->WriteHTML($html);
        
        $y1 += 5;
        $this->Text($x1, $y1, 'Immatriculée au registre du commerce et des sociétés de LYON sous le n° 435 379 284.');
        $y1 += 5;
        $this->Text($x1, $y1, 'Dont le siège social est sis à 8 rue Victor Lagrange   69007 LYON,');
        $y1 += 5;
        $this->Text($x1, $y1, 'Représentée par');
        $this->SetFont ($this->police, 'BI', 11);
        $this->Text($x1 + 27, $y1, 'Monsieur Bernard PEYRIN');
        $this->SetFont ($this->police, '', 11);
        $this->Text($x1 + 74, $y1, 'en qualité de Président directeur Général, dûment habilité aux');
        $y1 += 5;
        $this->Text($x1, $y1, 'fins de signature du présent.');
        $y1 += 20;
        $this->Text(170, $y1, 'D\'une part,');

        // Pavé collaborateur
        $y1 += 20;
        $this->Text($x1, $y1, 'Et');
        $y1 += 12;
//        $this->SetFont ($this->police, 'B', 11);
//        $this->Text($x1, $y1, $nomcol);
//        $x2 = $this->getX() + 1;
//        $this->SetFont ($this->police, '', 11);
//        $this->Text($x2, $y1, $natcol . $x2);

        $this->SetFont ($this->police, '', 11);
        $this->SetXY ($x1 - 1, $y1 - 3.5);
        $html = '<b><i>' . $nomcol . '</i></b>' . $natcol;
        $this->WriteHTML($html);

        $y1 += 5;
        $this->Text($x1, $y1, $adrcol);
        $y1 += 5;
        $this->Text($x1, $y1, 'Dont le numéro de sécurité sociale est le ' . $nsscol);
        $y1 += 20;
        $this->Text(170, $y1, 'D\'autre part.');

        // Raison sociale APSAROKE
        $file = "../image/ApsarokeRS.jpg";
        $this->Image($file, 70, 240, 70, 20);
        // Pied de page
        $this->addPieddepage();
    }

    function page2 ($t1, $p1, $t2, $p2, $t3, $p3, $t4, $p4)
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont ($this->police, 'B', 13);
        $x1 = 10;
        $y1 = 25;
        $this->Text($x1, $y1, $t1);
        //$y1 += 3;
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p1, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p1));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
        $this->Text($x1, $y1, $t2);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p2, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p2));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
        $this->Text($x1, $y1, $t3);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p3, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p3));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
        $this->Text($x1, $y1, $t4);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p4, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p4));

        // Pied de page
        $this->addPieddepage();
    }

    function page3 ($t1, $p1, $t2, $p2, $t3, $p3)
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont ($this->police, 'B', 13);
        $x1 = 10;
        $y1 = 20;
        $this->Text($x1, $y1, $t1);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p1, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p1));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
        //$y1 = 85;
        $this->Text($x1, $y1, $t2);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p2, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p2));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 202;
        $this->Text($x1, $y1, $t3);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p3, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p3));

        // Pied de page
        $this->addPieddepage();
    }

    function page4 ($t1, $p1, $t2, $p2, $t3, $p3)
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont ($this->police, 'B', 13);
        $x1 = 10;
        $y1 = 20;
        $this->Text($x1, $y1, $t1);
//        $y1 += 3;
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p1, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p1));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 65;
        $this->Text($x1, $y1, $t2);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p2, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p2));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 200;
        $this->Text($x1, $y1, $t3);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p3, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p3));

        // Pied de page
        $this->addPieddepage();
    }

    function page5 ($t1, $p1, $t2, $p2, $t3, $p3, $t4, $p4)
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont ($this->police, 'B', 13);
        $x1 = 10;
        $y1 = 20;
        $this->Text($x1, $y1, $t1);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p1, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p1));

        // Tableau coûts de formation si fin de contrat
        $this->addTableauCoutForm();
        
        $y1 = $this->GetY() + $this->ecart + 5;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 95;
        $this->Text($x1, $y1, $t2);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p2, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p2));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 130;
        $this->Text($x1, $y1, $t3);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p3, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p3));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 185;
        $this->Text($x1, $y1, $t4);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p4, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p4));

        // Pied de page
        $this->addPieddepage();
    }

    function page6 ($t1, $p1, $t2, $p2)
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont ($this->police, 'B', 13);
        $x1 = 10;
        $y1 = 25;
        $this->Text($x1, $y1, $t1);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p1, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p1));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 105;
        $this->Text($x1, $y1, $t2);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p2, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p2));

        // Pied de page
        $this->addPieddepage();
    }

    function page7 ($t1, $p1, $t2, $p2, $t3, $p3, $t4, $p4, $txtfin, $nomcol)
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont ($this->police, 'B', 13);
        $x1 = 10;
        $y1 = 20;
        $this->Text($x1, $y1, $t1);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p1, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p1));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 45;
        $this->Text($x1, $y1, $t2);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p2, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p2));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 115;
        $this->Text($x1, $y1, $t3);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p3, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p3));

        $y1 = $this->GetY() + $this->ecart;
        $this->SetFont ($this->police, 'B', 13);
//        $y1 = 145;
        $this->Text($x1, $y1, $t4);
        $y1 += 1;
        $this->Line($x1, $y1, 195, $y1);
        $y1 += 2;
        $this->SetFont ($this->police, '', 10);
        $this->SetXY ($x1, $y1);
        //$this->MultiCell(185, 5, $p4, $border=0, $align='', $fill=false);
        $this->WriteHTML(htmlspecialchars_decode($p4));

        // Fin de document
        $this->addFinDocument($datejour, $txtfin, $nomcol);

        // Pied de page
        $this->addPieddepage();
    }

    function addPieddepage ()
    {
        $x1 = 10;
        $y1 = 270;
        $this->SetTextColor(0, 176, 240);
        $this->SetFont ($this->police, '', 10);
        $this->Text($x1, $y1, 'Paraphe APSAROKE');
        $this->Text(160, $y1, 'Paraphe');
        $y1 += 8;
        $this->Text(80, $y1, 'Contrat de travail APSAROKE');
        $y1 += 5;
        $this->Text(95, $y1, 'Page ' . $this->PageNo() . ' sur 7');
        $file = "../image/LogoPiedPage.jpg";
        $this->Image($file, 180, 273, 15, 15);
    }

    function addTableauCoutForm(){
        $x1 = 35;
        $y1 = $this->GetY() + 8;
        //$y1 = 50;
        $h1 = 6;
        $this->SetXY ($x1, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, '  Jusqu\'à 500' . EURO . ' HT', 1);
        $this->SetXY ($x1 + 80, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, ' 6 mois à partir de la fin de la formation', 1);

        $y1 += $h1;
        $this->SetXY ($x1, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, '  Entre 501' . EURO . ' HT et 1000' . EURO . ' HT', 1);
        $this->SetXY ($x1 + 80, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, ' 12 mois à partir de la fin de la formation', 1);

        $y1 += $h1;
        $this->SetXY ($x1, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, '  Entre 1001' . EURO . ' HT et 2000' . EURO . ' HT', 1);
        $this->SetXY ($x1 + 80, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, ' 24 mois à partir de la fin de la formation', 1);

        $y1 += $h1;
        $this->SetXY ($x1, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, '  Supérieur à 2000' . EURO . ' HT', 1);
        $this->SetXY ($x1 + 80, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (80, $h1, ' 36 mois à partir de la fin de la formation', 1);

    }

    function addFinDocument ($datejour, $txtfin, $nomcol)
    {
        $x1 = 140;
        $y1 = 190;
        $this->SetXY ($x1, $y1);
        $this->SetFont ($this->police, '', 11);
        $this->Cell (60, 6, 'Fait à LYON le '. $datejour, 0);
        $y1 += 6;
        $this->SetFont ($this->police, 'I', 10);
        $this->SetXY ($x1 - 15, $y1);
        $this->Cell (80, 6, '(en deux exemplaires, un pour chacune des parties)', 0);
        $this->SetFont ($this->police, '', 10);

        $this->Line(102, 205, 102, 240);

        $x1 = 10;
        $y1 = 245;
        $this->SetXY ($x1, $y1);
        $this->MultiCell(185, 5, $txtfin, $border=0, $align='', $fill=false);

        // Identification entreprise
        $x1 = 20;
        $y1 = 220;
        $this->SetFont ('mistral', '', 15);
        $this->SetTextColor(0, 176, 240);
        $this->Text($x1, $y1, 'Monsieur Bernard PEYRIN');
        $this->Text($x1 + 102, $y1, $nomcol);
        $this->Text($x1, $y1 + 10, 'Président directeur Général');
    }

}
?>
