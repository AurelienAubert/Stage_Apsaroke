<?php

//Page qui permet l'insertion d'un login et d'un mot de passe crypté pour un collaborateur interne dans la base de donnée

try {
    //connexion au serveur 
    include_once "connection.php";
    //Si tous les champs du formulaire d'ajout d'une autorisation pour un collab interne existent et ne sont pas vides
    if (isset ($_POST["collab"]) && isset ($_POST["type_aut"]) && isset ($_POST["pwd"]) && isset ($_POST["pwd_key"])) {
        if (!empty ($_POST["collab"]) && !empty ($_POST["type_aut"]) && !empty ($_POST["pwd"]) && !empty ($_POST["pwd_key"])) {
            // récupération sécurisée des champs     
            $collab     = htmlspecialchars (addslashes (trim ($_POST['collab'])));
            $type_aut   = htmlspecialchars (addslashes (trim ($_POST['type_aut'])));
            $pwd        = htmlspecialchars (addslashes (trim (md5 ($_POST['pwd']))));
            $pwd_key    = htmlspecialchars (addslashes (trim (md5 ($_POST['pwd_key']))));
            $query = "SELECT COL_PASSWORD FROM COLLABORATEUR WHERE COL_NO=1";
            $result = $GLOBALS['connexion']->query($query);
            $row = $result->fetch_assoc ();
            if ($pwd_key == $row['COL_PASSWORD']) {
                //Requête SQL d'insertion des valeurs postées
                $query = "UPDATE COLLABORATEUR SET TAU_NO = '" . $type_aut . "', COL_PASSWORD = '" . $pwd . "', COL_PASS_ALL = '" . $pwd_key . "' WHERE COL_NO = '" . $collab . "'";
                //Exeécution de la requête
                $result = $GLOBALS['connexion']->query($query);
                echo "L'autorisation du collaborateur interne à bien été enregistrée.";
            }
            else {
                echo "Le mot de passe clé est incorrect";
            }
        }
    }
    else {
        echo "Vous n'avez pas renseigné les champs correctement. L'ajout de l'autorisation à échoué";
    }
} catch (Exception $e) {
// message en cas d'erreur 
    die ('Erreur : ' . $e->getMessage ());
}
?>