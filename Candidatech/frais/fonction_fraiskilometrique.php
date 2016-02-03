<?php

function nbchevaux ()
{
    $select = '<select name="nbchevaux">';
        
    $query = "SELECT TKM_NO, TKM_NBCHEVAUX FROM TAUX_KILOMETRIQUE;";
    $result = $GLOBALS['connexion']->query($query);
    
    while($row = mysqli_fetch_assoc($result))
    {
        $select .= '<option value=' . $row['TKM_NO'] . ' id=' . $row['TKM_NO'] . '>' . $row['TKM_NBCHEVAUX'] . '</option>';
    }
    
    $select .= '</select>';

    return $select;
}
?>