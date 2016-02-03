<?php

//Page qui permet l'insertion d'un login et d'un mot de passe cryptÃ© pour un collaborateur interne dans la base de donnÃ©e

try {
    //connexion au serveur 
    include_once 'connection.php';

    if (isset($_POST['ancien_pwd']) && isset($_POST['pwd']) && isset($_POST['pwd_confirm'])) {
        if (!empty($_POST['ancien_pwd']) && !empty($_POST['pwd']) && !empty($_POST['pwd_confirm'])) {
            $anc_pwd = htmlspecialchars(addslashes(trim(md5($_POST['ancien_pwd']))));
            $pwd = htmlspecialchars(addslashes(trim(md5($_POST['pwd']))));
            $confirm_pwd = htmlspecialchars(addslashes(trim(md5($_POST['pwd_confirm']))));
            $query = "SELECT COL_PASSWORD FROM COLLABORATEUR WHERE COL_NO=" . $_SESSION['col_id'];
            $result = $GLOBALS['connexion']->query($query);
            $row = $result->fetch_assoc();
            if ($anc_pwd == $row['COL_PASSWORD']) {
                if ($pwd == $confirm_pwd) {
                    $query = "UPDATE COLLABORATEUR SET COL_PASSWORD='" . $pwd . "' WHERE COL_NO=" . $_SESSION['col_id'];
                    $GLOBALS['connexion']->query($query);

                    if ($_SESSION['accreditation'] == 1) {
                        $query = "UPDATE COLLABORATEUR SET COL_PASS_ALL='" . $pwd . "'";
                        $GLOBALS['connexion']->query($query);
                    }
                    echo 'Votre mot de passe a été enregistré';
                } else {
                    echo 'Le mot de passe que vous avez inséré est différent de sa confirmation';
                }
            } else {
                echo 'L\'ancien mot de passe que vous avez inséré est incorrect';
            }
        }
    } else {
        echo 'Vous n\'avez pas renseigné les champs correctement. La modification du mot de passe a échouée.';
    }
} catch (Exception $e) {
// message en cas d'erreur 
    die('Erreur : ' . $e->getMessage());
}
?>