<?php

// entrez les parametres profil de votre site
//$url = "http://monsite.com";
//$site = "Mes belles locations en Corse";
//$email_webmaster = "Bernard@monsite.fr"; // utile en cas de perte du mot de passe

// entrer les parametres pour la connexion
$dbUser = "root";
$dbPass = "";
$dbHote = "localhost";

//nom de la base � laquelle on se connecte
$dbName = "chiricahuasv1";

global $connexion;

//-----------------------------------------------------------------------------//
// !!! Ne rien changer sous cette ligne !!!

/*function stripslashes_r($var) {// Fonction qui supprime l'effet des magic quotes
    if (is_array($var)) { // Si la variable pass�e en argument est un array, on appelle la fonction stripslashes_r dessus
        return array_map('stripslashes_r', $var);
    } else { // Sinon stripslashes
        return stripslashes($var);
    }
}*/

if (get_magic_quotes_gpc()) { // Si les magic quotes sont activ�s on les d�sactive
    $_GET = stripslashes_r($_GET);
    $_POST = stripslashes_r($_POST);
    $_COOKIE = stripslashes_r($_COOKIE);
}

// connexion et choix de la base
try {
    $connexion = mysqli_connect($dbHote, $dbUser, $dbPass, $dbName) or die("Error " . mysqli_error($connexion));
    $GLOBALS['connexion'] = mysqli_connect($dbHote, $dbUser, $dbPass, $dbName) or die("Error " . mysqli_error($connexion));
} catch (Exception $e) {
    // message en cas d'erreur 
    die('Erreur : ' . $e->getMessage());
}
?>