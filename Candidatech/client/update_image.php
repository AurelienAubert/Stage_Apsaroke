<?php
  include "../inc/connection.php"; 
  
   $rq = "UPDATE CLIENT SET CLI_LOGO = '' WHERE CLI_NO = '".$_POST['recherche']."';";
   $GLOBALS['connexion']->query($rq);
   echo 'logo supprimé';
   header('Location: modification_client.php?type=client');
?>

