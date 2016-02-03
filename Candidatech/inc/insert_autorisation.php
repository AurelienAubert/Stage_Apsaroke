<?php

//Page qui permet l'insertion d'un login et d'un mot de passe crypt� pour un collaborateur interne dans la base de donn�e

try {
    //connexion au serveur 
    include_once "connection.php";
    //Si tous les champs du formulaire d'ajout d'une autorisation pour un collab interne existent et ne sont pas vides
    if (isset ($_POST["collab"]) && isset ($_POST["type_aut"]) && isset ($_POST["pwd"]) && isset ($_POST["pwd_key"])) {
        if (!empty ($_POST["collab"]) && !empty ($_POST["type_aut"]) && !empty ($_POST["pwd"]) && !empty ($_POST["pwd_key"])) {
            // r�cup�ration s�curis�e des champs     
            $collab     = htmlspecialchars (addslashes (trim ($_POST['collab'])));
            $type_aut   = htmlspecialchars (addslashes (trim ($_POST['type_aut'])));
            $pwd        = htmlspecialchars (addslashes (trim (md5 ($_POST['pwd']))));
            $pwd_key    = htmlspecialchars (addslashes (trim (md5 ($_POST['pwd_key']))));
            $query = "SELECT COL_PASSWORD FROM COLLABORATEUR WHERE COL_NO=1";
            $result = $GLOBALS['connexion']->query($query);
            $row = $result->fetch_assoc ();
            if ($pwd_key == $row['COL_PASSWORD']) {
                //Requ�te SQL d'insertion des valeurs post�es
                $query = "UPDATE COLLABORATEUR SET TAU_NO = '" . $type_aut . "', COL_PASSWORD = '" . $pwd . "', COL_PASS_ALL = '" . $pwd_key . "' WHERE COL_NO = '" . $collab . "'";
                //Exe�cution de la requ�te
                $result = $GLOBALS['connexion']->query($query);
                echo "L'autorisation du collaborateur interne � bien �t� enregistr�e.";
            }
            else {
                echo "Le mot de passe cl� est incorrect";
            }
        }
    }
    else {
        echo "Vous n'avez pas renseign� les champs correctement. L'ajout de l'autorisation � �chou�";
    }
} catch (Exception $e) {
// message en cas d'erreur 
    die ('Erreur : ' . $e->getMessage ());
}
?>