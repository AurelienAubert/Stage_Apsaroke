<!-- Initialisation de la session-->
<?php
session_start();
$_SESSION['connect'] = 0; //Initialise la variable 'connect'.

if (isset($_POST['login'])) {
    if ($_POST["login_admin"] != "" && $_POST["mot_de_passe_admin"] != "") {
        $login_admin = $_POST["login_admin"];
        $pass_admin = md5($_POST["mot_de_passe_admin"]);

        //connexion au serveur
        include("../inc/conec.php");

        //cr�ation de la requ�te SQL
        $sql = "SELECT * FROM $T_admin WHERE login_admin = '" . $login_admin . "' AND pass_admin = '" . $pass_admin . "'";

        //ex�cution de la requ�te SQL
        $requete = @mysql_query($sql) or die($sql . "<br>" . mysql_error());
        //on r�cup�re le r�sultat
        $result = mysql_fetch_object($requete);
        //si la requ�te s'est bien pass�e

        if (is_object($result)) {
            //enregistrement d'une variable de session, ici le login de l'utilisateur
            $_SESSION["login_admin"] = $login_admin;
            header("Location: ../index.php");
        }//fin if le login et le pass sont ok
        //sinon on retourne � la page d'inscription
        else {
            $erreur = 'login ou mot de passe incorrect';
        }
    }  //fin if quelque chose a �t� post�
    else {
        $erreur = 'login ou mot de passe incorrect';
    }
}//fin if _POST
?>