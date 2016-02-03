<?php
// Fonction générale de réception d'un fichier après une <input type='file'
//  attributs disponibles : name, size, type, tmp_name et error si le transfert & échoué
//
// champs hidden nécessaires :
//  fsource     : nom du fichier transmis
//  rep_copy    : rpertoire de réception
//  urlretour   : le script de retour
//
// on pourrait tester le $ok si nécessaire (droits sur les répertoires etc)

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
