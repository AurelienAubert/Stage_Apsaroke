<?php

//Fonction qui prends en param�tre un mois(date("n")1-12 sans les z�ros), un jour(date("j") 1-31), une ann�e(date("Y") sur 4 chiffres)
//Elle retourne le num�ro du jour (0-6 dimanche � samedi)

function check_jour($mois,$jour,$annee) {    
    $timestamp=mktime(0,0,0,$mois,$jour,$annee);
    $date=date('w',$timestamp);         
        return $date;          
    }    

?>