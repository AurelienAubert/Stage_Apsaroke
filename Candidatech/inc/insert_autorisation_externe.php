<?php

//Page qui permet l'insertion d'un login et d'un mot de passe crypté pour un collaborateur externe dans la base de donnée

try {
    //connexion au serveur
    include ("connection.php");
    //Si tous les champs du formulaire d'ajout d'une autorisation pour un collab externe existent et ne sont pas vides
    if (isset($_POST["collab_externe"]) && isset($_POST["type_aut_externe"]) && isset($_POST["login_externe"]) && isset($_POST["pwd_externe"]) && isset($_POST["pwd_externe_key"])) {
        if (!empty($_POST["collab_externe"]) && !empty($_POST["type_aut_externe"]) && !empty($_POST["login_externe"]) && !empty($_POST["pwd_externe"]) && !empty($_POST["pwd_externe_key"])) {
            // récupération sécurisée des champs     
            $collab = htmlspecialchars(addslashes(trim($_POST['collab_externe'])));
            $type_aut_ext = htmlspecialchars(addslashes(trim($_POST['type_aut_externe'])));
            $log_ext = htmlspecialchars(addslashes(trim(strtoupper($_POST['login_externe']))));
            $pwd_ext = htmlspecialchars(addslashes(trim(md5($_POST['pwd_externe']))));
            $pwd_key = htmlspecialchars(addslashes(trim(md5($_POST['pwd_externe_key']))));
            $query1 = "SELECT AUT_NO, COL_NO, AUT_LOGIN, AUT_PASSWORD FROM autorisation INNER JOIN typeautorisation ON typeautorisation.TAU_NO=autorisation.TAU_NO WHERE autorisation.TAU_NO='1' AND typeautorisation.TAU_LIBELLE='PDG'";
            $result1 = $connexion->query($query1);
            $row = $result1->fetch_assoc();
            if ($pwd_key == $row['AUT_PASSWORD']) {
                //Requête SQL d'insertion des valeurs postées
                $query = "INSERT INTO autorisation (`TAU_NO`, `COE_NO`, `AUT_LOGIN`, `AUT_PASSWORD`,`AUT_PASS_ALL`)  VALUES ('" . $type_aut_ext . "','" . $collab . "','" . $log_ext . "', '" . $pwd_ext . "', '" . $pwd_key . "')";
                //Exeécution de la requête
                $result = $connexion->query($query);
                header("Location: ../validation_autorisation_externe.php");
            } else {
                echo "Le mot de passe clé est incorrect";
            }
        }
    } else {
        echo "Vous n'avez pas renseigné les champs correctement. L'ajout de l'autorisation à échoué";
    }
} catch (Exception $e) {
// message en cas d'erreur 
    die('Erreur : ' . $e->getMessage());
}
?>