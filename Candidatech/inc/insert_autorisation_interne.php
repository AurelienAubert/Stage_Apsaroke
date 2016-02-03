<?php

//Page qui permet l'insertion d'un login et d'un mot de passe crypté pour un collaborateur interne dans la base de donnée

try
{
    //connexion au serveur 
    include ("connection.php");
    //Si tous les champs du formulaire d'ajout d'une autorisation pour un collab interne existent et ne sont pas vides
    if (isset ($_POST["collab_interne"]) && isset ($_POST["type_aut_interne"]) && isset ($_POST["pwd_interne"]) && isset ($_POST["pwd_interne_key"]))
    {
        if (!empty ($_POST["collab_interne"]) && !empty ($_POST["type_aut_interne"]) && !empty ($_POST["pwd_interne"]) && !empty ($_POST["pwd_interne_key"]))
        {
            // récupération sécurisée des champs     
            $collab = htmlspecialchars (addslashes (trim ($_POST['collab_interne'])));
            $type_aut_int = htmlspecialchars (addslashes (trim ($_POST['type_aut_interne'])));
            $pwd_int = htmlspecialchars (addslashes (trim (md5 ($_POST['pwd_interne']))));
            $pwd_key = htmlspecialchars (addslashes (trim (md5 ($_POST['pwd_interne_key']))));
            $query1 = "SELECT PASSWORD FROM collaborateur WHERE ID_COL=1";
            $result1 = $connexion->query ($query1);
            $row = $result1->fetch_assoc ();
            if ($pwd_key == $row['PASSWORD'])
            {
                //Requête SQL d'insertion des valeurs postées
                $query = "UPDATE `collaborateur` SET `ID_TYPE`='" . $type_aut_int . "',`PASSWORD`='" . $pwd_int . "',`PASSWORD_ALL`='" . $pwd_key . "' WHERE `ID_COL`='" . $collab . "'";
                //Exeécution de la requête
                $result = $connexion->query ($query);
                echo "L'autorisation du collaborateur interne à bien été enregistrée.";
            }
            else
            {
                echo "Le mot de passe clé est incorrect";
            }
        }
    }
    else
    {
        echo "Vous n'avez pas renseigné les champs correctement. L'ajout de l'autorisation à échoué";
    }
} catch (Exception $e)
{
// message en cas d'erreur 
    die ('Erreur : ' . $e->getMessage ());
}
?>