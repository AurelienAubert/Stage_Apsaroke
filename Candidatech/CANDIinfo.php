<?php

// Conexion a la BD
require_once 'CANDIbdd.inc';
$bdd = getBdd();

$requete = "SELECT * FROM `candi_candidat` WHERE cdt_numcandidat=".$_GET['num_candidat']."";
// Pre paration de la requete SQL
$prep = $bdd->prepare($requete);
// Exécution de la requete 
$prep->execute();
// Recuperation du tableau resultat requete (contenant toutes les lignes)
$candidat = $prep -> fetchObject();

$num = $_GET['num_candidat'];
?>


<html>
    <head>
        <meta charset = "UTF-8" />
        <title>Recherche Candidats</title>
        <link rel="stylesheet" href="CANDIstyle.css" />
        <?php include 'head.php' ?>
    </head>
    <body>
        <?php include ("menu/menu_accueil.php"); ?>
        
        <br><br>
               <div class="container-fluid">
            <div class="row">
                <div class="offset3 span6">
                    <div  div class="well" id="formulaire">
        <div class="sidebar"></div>
            <div class="texte"></div>

            <br><br>
            <div class="decalage3">
                
                <table border= border-radius="2px">
                    <tr> 
                        <th>Telephone 1</th>
                        <th>Telephone 2</th>
                        <th>Adresse Mail</th>
                        
                    </tr>
                    <tr>
                        <td><span class="grasvert"><?php echo $candidat->CDT_TEL1CANDIDAT ?></span></td>
                        <td><span class="grasvert"><?php echo $candidat->CDT_TEL2CANDIDAT ?></span></td>
                        <td><span class="grasvert"><?php echo $candidat->CDT_MAILCANDIDAT ?></span></td>
                    </tr>
                </table>
                <br><br>

                <a href="CANDIrechercheCandidat.php"><button>Retour vers la Recherche</button></a>
                
                <div class='offset2 span2 '>
                    <a href="CANDImodifier.php?num_candidat=<?php echo $candidat->CDT_NUMCANDIDAT ?>"><button class="btn btn-primary">Modifier Infos</button></a><br><br>
                </div>
            
                <br><br>
            </div>
            
                    </div></div></div>
        </div>
    </body>
</html>

