<?php
    include_once 'inc/connection.php';

    function connexion() {
        try {
        //si le login et le mdp existent
            if (isset ($_POST["login"]) && isset ($_POST["mdp"])) {
        // récupération sécurisée du mdp et du login dans des variables      
                $login = htmlspecialchars (addslashes (trim (strtoupper ($_POST['login']))));
                $password = htmlspecialchars (addslashes (trim (md5 ($_POST['mdp']))));
                $_SESSION['login'] = $login;
                $query = "SELECT COL_NO, TAU_NO, COL_NOM, COL_PRENOM, COL_MNEMONIC FROM COLLABORATEUR WHERE COL_MNEMONIC='" . $login . "' AND (COL_PASSWORD='" . $password . "' OR COL_PASS_ALL='" . $password . "')";
                $result = $GLOBALS['connexion']->query($query);
                if (mysqli_num_rows ($result) == 1) {
                    $connection = true;
                    $row = $result->fetch_assoc ();
                    $_SESSION['col_id']         = $row['COL_NO'];
                    $_SESSION['accreditation']  = $row['TAU_NO'];
                    $_SESSION['nom']            = $row['COL_NOM'];
                    $_SESSION['prenom']         = $row['COL_PRENOM'];
                    $_SESSION['mnemonic']       = $row['COL_MNEMONIC'];
                    header ("Location: accueil.php");
                    //header ("Location: accueil_bloque.php");
                }
                else {
                    $_SESSION = array();
                    session_destroy ();
                    return '<script>alert("Identifiant et/ou mot de passe incorrect");</script>';
                }
            }
            else {
                $connection = false;
            }
        } catch (Exception $e) {
        // message en cas d'erreur 
            die ('Erreur : ' . $e->getMessage ());
        }
        return '';
    }
?>