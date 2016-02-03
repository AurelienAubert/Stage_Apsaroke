<?php
session_start();
include_once 'connection.php';

//var_dump($_POST);
$query = "SELECT COL_EMAILAPSA FROM COLLABORATEUR WHERE COL_NO = '" . $_POST['to'] . "'";
$result = $GLOBALS['connexion']->query($query)->fetch_assoc();

// To
$to = $result['COL_EMAILAPSA'];

// Subject
//$subject = $_POST['objet'];
$subject = utf8_decode($_POST['objet']);
$subject = mb_encode_mimeheader($subject,"UTF-8");

// clé aléatoire de limite
$boundary = md5 (uniqid (microtime (), TRUE));

// Headers
$headers =  'From: NoReply Apsaroke <noreply@apsaroke.fr>' . "\r\n";
$headers .= 'Reply-To: Apsaroke <message@apsaroke.com>' . "\r\n";
$headers .= 'Mime-Version: 1.0' . "\r\n";
$headers .= 'Content-Type: multipart/mixed; boundary=' . $boundary . "\r\n";
$headers .= 'Content-Transfer-Encoding: 8bit' . "\r\n";
$headers .= "\r\n";

$msg = '--' . $boundary . "\r\n";
$msg .= 'Content-type: text/html; charset=UTF-8' . "\r\n\r\n";
$msg .= '<html>
        <body>
            <p>' . str_replace("\n", "\r\n", $_POST['message']) . '</p>
        </body>
    </html>' . "\r\n";

//var_dump(array($to, $subject, $msg));

// Function mail()
mail ($to, $subject, $msg, $headers);
?>