<?php

// fonction qui retourne le mois et son numéro dans un tableau.

function mois ()
{
    $select = '<select name="mois">';

    $month = array('1' => 'Janvier',
        '2' => 'F&eacute;vrier',
        '3' => 'Mars',
        '4' => 'Avril',
        '5' => 'Mai',
        '6' => 'Juin',
        '7' => 'Juillet',
        '8' => 'Ao&ucirc;t',
        '9' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'D&eacute;cembre'
    );

    foreach ($month as $k => $v)
    {
        $select .= '<option value=' . $k . ' id=' . $k . '>' . $v . '</option>';
    }

    $select .= '</select>';

    return $select;
}

?>