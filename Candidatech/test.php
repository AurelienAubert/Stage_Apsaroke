<?php

include 'calendrier/fonction_nbjoursMois.php';
include 'calendrier/fonction_dimanche_samedi.php';


//$date = $_POST['annee'].'-'.$_POST['mois'];
//Boucle tant que le dernier jour du mois est un samedi ou un dimanche
$nbJour = nbjoursMois(01, 2015);
$date = '2015-01-';
$t = 0;
do{
    $t = check_jour(substr($date,5,2), $nbJour, substr($date,0,4));
    if($t == 0 || $t == 6)
        $nbJour--;
}while($t == 0 || $t== 6);

echo $date.$nbJour;