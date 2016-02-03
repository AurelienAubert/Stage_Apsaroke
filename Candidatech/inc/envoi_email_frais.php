<?php

include_once 'calendrier/fonction_nomMois.php';
include_once 'connection.php';

//pour aller chercher $mail dans la base de données
$rq_mail = "SELECT `PAR_VALEUR` FROM `PARAMETRE` WHERE `PAR_LIBELLE`='email_destinataire';";
$res_mail = $GLOBALS['connexion']->query($rq_mail);
//=======    
$ligne = mysqli_fetch_assoc($res_mail);

// To
$to = $ligne['PAR_VALEUR'];

// Subject
$subject = 'Demande de frais ' . $type_frais . ' ' . $_SESSION['nom'] . '.';

// clé aléatoire de limite
$boundary = md5(uniqid(microtime(), TRUE));

// Headers
$headers = 'From: NoReply Apsaroke <noreply@apsaroke.fr>' . "\r\n";
$headers .= 'Mime-Version: 1.0' . "\r\n";
$headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
$headers .= "\r\n";

// Message
$msg = 'Demande de frais ' . $type_frais . ' ' . $_SESSION['nom'] . '.' . "\r\n\r\n";

// Message HTML
$msg .= '--' . $boundary . "\r\n";
$msg .= 'Content-type: text/html; charset=\"ISO-8859-1\"' . "\r\n\r\n";
$msg .= '
         <html>
      <head>
       <title>' . $subject . '</title>
      </head>
      <body>';

      foreach($LigneMail as $val){
          $msg .= $val;
      }

$msg .= '  <p>Vous pouvez modifier l\'état de la demande sur l\'application <a href="http://chiricahuas.crows-it.com/">Apsaroke</a>.</p>';
$msg .= '  <p>Cordialement</p>
      </body>
     </html>' . "\r\n";

// Fin
$msg .= '--' . $boundary . "\r\n";

// Function mail()
mail($to, $subject, $msg, $headers);
?>