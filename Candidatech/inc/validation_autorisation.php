<?php include ("inc/connection.php"); ?>
<?php session_start(); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 


    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Formulaire</title>
        <meta name="viewport" content="width=device-width" />
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
            <link href="style.css" rel="stylesheet" type="text/css">
                <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
                    <script type="text/javascript" src="http://code.jquery.com/jquery.js"></script>

                    <!-- icones apple -->
                    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
                        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
                            <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                                <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                    </head>

                                    <body onload="document.formulaire.submit();">
                                        <!-- Div qui contient la bannière - Haut de Page. -->
                                        <div id="header">			
                                            <!-- Logo d'Apsaroke-->
                                            <img src="image/LogoApsa.jpg" alt="logo Apsaroke" height="500" width="565"> 
                                        </div>

                                        <!-- Barre de menu-->
                                        <?php include ("menu/menu_admin.php"); ?> 
                                        <div class="container-fluid">
                                        </div>

                                        <div class="row" id='footer'>
                                            <div class="span12"><br><br>
                                                        <script src="js/jquery.min.js"></script>
                                                        
                                                        </div>
                                                        </div>                                                               


                                                        </body>
                                                        </html>