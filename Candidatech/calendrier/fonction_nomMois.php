<?php
function nomMois($mois) {
    $nom = "";
    switch ($mois) {
        case "1":
            $nom = "Janvier";
            break;
        case "2":
            $nom = "Février";
            break;
        case "3":
            $nom = "Mars";
            break;
        case "4":
            $nom = "Avril";
            break;
        case "5":
            $nom = "Mai";
            break;
        case "6":
            $nom = "Juin";
            break;
        case "7":
            $nom = "Juillet";
            break;
         case "8":
            $nom = "Août";
            break;
         case "9":
            $nom = "Septembre";
            break;
         case "10":
            $nom = "Octobre";
            break;
         case "11":
            $nom = "Novembre";
            break;
         case "12":
            $nom = "Décembre";
            break;
    }
    return $nom;
}
function MnemoMois($mois) {
    $nom = "";
    switch ($mois) {
        case "1":
            $nom = "JR";
            break;
        case "2":
            $nom = "FR";
            break;
        case "3":
            $nom = "MS";
            break;
        case "4":
            $nom = "AL";
            break;
        case "5":
            $nom = "MI";
            break;
        case "6":
            $nom = "JN";
            break;
        case "7":
            $nom = "JT";
            break;
         case "8":
            $nom = "AT";
            break;
         case "9":
            $nom = "SE";
            break;
         case "10":
            $nom = "OE";
            break;
         case "11":
            $nom = "NE";
            break;
         case "12":
            $nom = "DE";
            break;
    }
    return $nom;
}
?>