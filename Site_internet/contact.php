<?php // Codage PHP du formulaire

		// Utilisation de PHPMailer

	require 'PHPMailer/class.phpmailer.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer();
$mail->Host = 'smtp.outlook.com';
$mail->SMTPAuth   = false;
$mail->Port = 25; // Par défaut
	// Code properly the charset
	$mail->CharSet = 'UTF-8';
// Expéditeur
$mail->SetFrom('expediteur@example.com', 'Nom Prénom');
// Destinataire
$mail->AddAddress('destinataire@example.com', 'Nom Prénom');
// Objet
$mail->Subject = 'Objet du message';
 
// Votre message
$mail->MsgHTML('Contenu du message en HTML');
// Ajouter une pièce jointe
$mail->AddAttachment('images/phpmailer-mini.gif');
 
// Envoi du mail avec gestion des erreurs
if(!$mail->Send()) {
  echo 'Erreur : ' . $mail->ErrorInfo;
} else {
  echo 'Message envoyé !';
} 
 
?>
