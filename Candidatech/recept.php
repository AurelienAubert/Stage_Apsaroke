<?php
// Fonction g�n�rale de r�ception d'un fichier apr�s une <input type='file'
//  attributs disponibles : name, size, type, tmp_name et error si le transfert & �chou�
//
// champs hidden n�cessaires :
//  fsource     : nom du fichier transmis
//  rep_copy    : rpertoire de r�ception
//  urlretour   : le script de retour
//
// on pourrait tester le $ok si n�cessaire (droits sur les r�pertoires etc)

if (!$recfile) $recfile = $_FILES['fsource']['name'];

//echo $_FILES['fsource']['name'] . "</br>";
//echo $_FILES['fsource']['size'] . "</br>";
//echo $_FILES['fsource']['type'] . "</br>";
//echo $_FILES['fsource']['tmp_name'] . "</br>";
//echo $_FILES['fsource']['error'] . "</br>";
//echo "Rep : " . $rep_copy . $_FILES['userfile']['name'];

$rep_copy = $_POST['rep_copy'];
$ok = copy($_FILES['fsource']['tmp_name'], $rep_copy.$recfile);

header("location:" . $_POST['urlretour']);
?>
