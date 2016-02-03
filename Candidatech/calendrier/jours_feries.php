<?php

function getFeries ($timestampStart) {
    return getFeriesAnnee(date ('Y', $timestampStart));
}

function getFeriesAnnee ($year)
{
    $paques = getPaques($year);
    $tabFeries = array(
        // Dates fixes
        mktime (0, 0, 0, 1, 1, $year), // 1er janvier
        mktime (0, 0, 0, 5, 1, $year), // Fête du travail
        mktime (0, 0, 0, 5, 8, $year), // Victoire des alliés
        mktime (0, 0, 0, 7, 14, $year), // Fête nationale
        mktime (0, 0, 0, 8, 15, $year), // Assomption
        mktime (0, 0, 0, 11, 1, $year), // Toussaint
        mktime (0, 0, 0, 11, 11, $year), // Armistice
        mktime (0, 0, 0, 12, 25, $year), // Noel
        mktime (0, 0, 0, date ('m', $paques), date ('d', $paques) + 1, $year), // lundi de paques 
        mktime (0, 0, 0, date ('m', $paques), date ('d', $paques) + 39, $year), // ascension 
        //mktime (0, 0, 0, date ('m', $paques), date ('d', $paques) + 50, $year), // pentecote 
    );
    return $tabFeries;
}

function jour_semaine ($mois, $jour, $year)
{
    $timestamp = mktime (0, 0, 0, $mois, $jour, $year);
    $date = date ("N", $timestamp);
    return $date;
}

function getPaques($annee) {
    $a = $annee % 4;
    $b = $annee % 7;
    $c = $annee % 19;
    $m = 24;
    $n = 5;
    $d = (19 * $c + $m ) % 30;
    $e = (2 * $a + 4 * $b + 6 * $d + $n) % 7;

    $datepaques = 22 + $d + $e;

    if ($datepaques > 31) {
        $day = $d + $e - 9;
        $month = 4;
    }
    else {
        $day = 22 + $d + $e;
        $month = 3;
    }

    if ($d == 29 && $e == 6) {
        $day = 10;
        $month = 04;
    }
    elseif ($d == 28 && $e == 6) {
        $day = 18;
        $month = 04;
    }

    return mktime(0, 0, 0, $month, $day, $annee);
}
?>