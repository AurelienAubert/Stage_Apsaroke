<?php // echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"/>
    <head>
        <?php @include 'head.php'; ?>
        <title>Déconnexion</title>
        <!-- Script bloquant le retour en arriere-->
        <script language="JavaScript">
            javascript:window.history.forward(1);
        </script>
    </head>

    <body>
        <!-- Barre de menu-->
        <?php include ("menu/menu_accueil.php"); ?>   
        <div class="container ">
            <div class="row">
                <?php
                // Initialisation de la session.
                // Si vous utilisez un autre nom
                // session_name("autrenom")
                @session_start();
                // Détruit toutes les variables de session
                $_SESSION = array();
                // Si vous voulez détruire complètement la session, effacez également
                // le cookie de session.
                // Note : cela détruira la session et pas seulement les données de session !
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    @setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
                    );
                }
                // Finalement, on détruit la session.
                session_destroy();
                echo "Vous êtes déconnecté.";
                ?>
                <div class="span12"><br></div>
            </div>
        </div>
    </body>
</html>

