<?php

include_once ('calendrier/fonction_mois.php');
include_once ('calendrier/fonction_nbjoursMois.php');
include_once ('calendrier/jours_feries.php');
include_once ('calendrier/fonction_dimanche_samedi.php');
include_once ('calendrier/fonction_nomMois.php');

function joursouvres($mois, $annee) {
    $date_depart = strtotime($annee . "-" . $mois . "-01");
    $date_arrive = strtotime($annee . "-" . $mois . "-31");

    $samedi = 0;
    $dimanche = 0;
    $ferie1 = 0;

    $nbjoursMois = nbjoursMois($mois, $annee); //connaitre le nb de jours dans le mois
    $jour = jour_semaine($mois, 1, $annee);
    $feries = getFeries($date_depart, $date_arrive);

    for ($i = 1; $i <= $nbjoursMois; $i++) {
        switch (true) {
            case in_array(mktime(0, 0, 0, $mois, $i, $annee), $feries):
                $ferie1++;
                break;
            case ($jour == 6):
                $samedi++;
                break;
            case ($jour == 7):
                $dimanche++;
                break;
        }
        if ($jour == 7) {
            $jour = 0;
        }
        $jour++;
    }

    $jourouvre = $nbjoursMois - $ferie1 - $dimanche - $samedi;
    
    return $jourouvre;
}

?>