<!-- PARTIE DONNEES -->
<?php
// Conexion a la BD
require_once 'CANDIbdd.inc';
$bdd = getBdd();



// Fermeture de la connexion
$bdd = null;
?>

<html>
    <head>
        <meta charset = "UTF-8" />
        <title>Index Candidatech</title>
        <link rel="stylesheet" href="CANDIstyle.css" />
        <?php include 'head.php' ?>
    </head>
    <body>
        
         <?php include ("menu/menu_accueil.php"); ?>
        <br>
        
        <div class="container ">
            <div class="row">
                <div class="offset3 span6">
                    <div  div class="well" id="formulaire">
                    <fieldset>
                    <legend>Gestion des candidats</legend>
                    
                    <div class="bordure">
                    <div class="decalage3">
                        <br>
                        <legend>Recherche Candidats</legend>
                                <br>
                              
                            <form class="formulaire" action="CANDIrechercheCandidat.php" method="post">
                                <strong>Langage:</strong> <br>
                                    <input type="text" name="detail" title="Ne rien ecrire pour voir tout les candidats" style="height:25px">
                                    <br><br>
                                    <strong>Lieu:</strong> <br>
                                    <input type="text" name="lieu" style="height:25px">
                                    <br><br>
                                        <table>
                                                <th><label for="case"><strong >Independant :</strong></label></th>
                                                <th><div class="decalage3"><input type="checkbox" name="independant" id="case" style="width:18px; height:18px;"></div></th>
                                        </table>
                                    <br><br>
                                        
                                
                                    
                                    <button class="btn btn-primary" type="submit">Afficher les candidats</button>
                            </form><br>
                              

                           
                    </div>
                    </div>
                    
                    
                        <br>
                            
                            
                    <div class="bordure">
                    <div class="decalage3">
                        <br>
                        <legend>Ajout d'un Candidat</legend>
                                <br>
                              
                            
                                <a href="CANDIAjouteCandidat.php"><button class="btn btn-primary">Ajouter un candidat</button></a>
                                <br><br>
                              

                           
                    </div>
                    </div>
                      </fieldset>
                    
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>