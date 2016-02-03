<?php

include_once ('connection.php');

if ($_FILES['LOGO_APSAROKE']['error']==0 && $_FILES['LOGO_CHIRICAHUAS']['error']==0) {
     $images = glob('../image/*');
     foreach ($images as $image) {
         unlink($image);
     }
     
    move_uploaded_file($_FILES['LOGO_APSAROKE']['tmp_name'], '../image/' . $_FILES['LOGO_APSAROKE']['name']);
    move_uploaded_file($_FILES['LOGO_CHIRICAHUAS']['tmp_name'], '../image/' . $_FILES['LOGO_CHIRICAHUAS']['name']);
    
    $logo_apsaroke = 'image/' . $_FILES['LOGO_APSAROKE']['name'];
    $logo_chiricahuas = 'image/' . $_FILES['LOGO_CHIRICAHUAS']['name'];
    
    $rq_logo_apsaroke = "UPDATE PARAMETRE SET PARAM_VAL = '".$logo_apsaroke."' WHERE PARAM_LIB = 'logo_apsaroke';";
    $rq_logo_chiricahuas = "UPDATE PARAMETRE SET PARAM_VAL = '".$logo_chiricahuas."' WHERE PARAM_LIB = 'logo_chiricahuas';";
    
    $GLOBALS['connexion']->query($rq_logo_apsaroke);
    $GLOBALS['connexion']->query($rq_logo_chiricahuas);
 }

header('Location:../page_enregistre.php');
?>
