<?php
session_start();
include_once '../calendrier/fonction_nomMois.php';
include_once 'connection.php';
  
// pour aller chercher le 'destinataire administrateur' dans la base de données
$rq_mail = "SELECT `PAR_VALEUR` FROM `PARAMETRE` WHERE `PAR_LIBELLE`='email_destinataire';";    
$res_mail = $GLOBALS['connexion']->query($rq_mail);

// Déclaration de l'adresse de destination.
$ligne = mysqli_fetch_assoc($res_mail);
$mail = $ligne['PAR_VALEUR'];

// On filtre les serveurs qui présentent des bogues.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
{
    $passage_ligne = "\r\n";
}
else
{
    $passage_ligne = "\n";
}

//=====Déclaration des messages au format texte et au format HTML.
$message_txt = $_POST['message'];
$message_html = "<html><head></head><body>".$_POST['message']."</body></html>";
//==========
 
//=====Lecture et mise en forme de la pièce jointe.
$fichier   = fopen($_FILES['fichier']['tmp_name'], "r");
$attachement = fread($fichier, filesize($_FILES['fichier']['tmp_name']));
$attachement = chunk_split(base64_encode($attachement));
fclose($fichier);
//==========
 
//=====Création de la boundary.
$boundary = "-----=".md5(rand());
$boundary_alt = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = $_POST['objet'];
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"".$_SESSION['nom']."\"<noreply@apsaroke.fr>".$passage_ligne;
$header.= "Reply-to: \"".$_SESSION['nom']."\" <noreply@apsaroke.fr>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========
 
//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
 
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
 
//=====Ajout du message au format HTML.
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
 
//=====On ferme la boundary alternative.
$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
//==========
 
$message.= $passage_ligne."--".$boundary.$passage_ligne;
 
//=====Ajout de la pièce jointe.
$message.= "Content-Type: image/jpg; name=\"".$_FILES['fichier']['name']."\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
$message.= "Content-Disposition: attachment; filename=\"".$_FILES['fichier']['name']."\"".$passage_ligne;
$message.= $passage_ligne.$attachement.$passage_ligne.$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne; 
//========== 

//=====Envoi de l'e-mail.
mail($mail, $sujet, $message, $header);
 
?>