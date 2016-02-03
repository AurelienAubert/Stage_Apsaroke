<?php 
    /**
     * variable nécéssaire : $page, tableau contenant :
     * - titre
     * - action
     * - contenu
     * - message
     */
require_once ('inc/verif_session.php');
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php';?>
        <title>Paramètre : Numéro de facture</title>
    </head>
    <body>
        <?php
        include ("menu/menu_global.php");
        echo '<div class="container-fluid ">';
        
        isset($_POST['FAC_NUM']) ? $_SESSION['FAC_NUM'] = $_POST['FAC_NUM'] : '';
        //Ajouter le formatage -> Utiliser verifs avec lg max
        echo '</div>';
        ?>
        <div class="row-fluid">
            <form action="num_def.php" method="post">
                Numéro de facture de démarrage : <input type="text" name="FAC_NUM" placeholder="<?php echo isset($_SESSION['FAC_NUM']) ? $_SESSION['FAC_NUM'] : "Aucun numéro de facture par défaut"; ?>"/>
                <input type="submit" name="Valider"/>
            </form>
        </div>
    </body>
</html><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

