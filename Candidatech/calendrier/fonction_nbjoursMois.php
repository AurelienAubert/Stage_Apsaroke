<?php

//Fonction qui retourne le nombre de jours du mois en fonction de l'ann�e et du mois.

function nbjoursMois($mois, $year) {
    return date('t', mktime(1, 1, 1, $mois, 1, $year));
}

?>