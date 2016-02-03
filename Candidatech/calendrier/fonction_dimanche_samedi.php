<?php

//Fonction qui prends en paramètre un mois(date("n")1-12 sans les zéros), un jour(date("j") 1-31), une année(date("Y") sur 4 chiffres)
//Elle retourne le numéro du jour (0-6 dimanche à samedi)

function check_jour($mois,$jour,$annee) {    
    $timestamp=mktime(0,0,0,$mois,$jour,$annee);
    $date=date('w',$timestamp);         
        return $date;          
    }    

?>