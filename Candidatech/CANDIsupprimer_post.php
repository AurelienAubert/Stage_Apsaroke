<?php
$numcandidat=$_GET['numcandidat'];
// Conexion a la BD
require_once 'CANDIbdd.inc';
$bdd = getBdd();
$requete ="SELET * FROM 'candi_candidat' WHERE cdt_numcandidat='$numcandidat'";
$prep = $bdd->prepare($requete);
// Exécution de la requete 
$prep->execute(array($numcandidat));
$ligne = $prep -> fetchObject();

$requete = "DELETE * FROM `candi_candidat` WHERE cdt_numcandidat='$numcandidat'";
// Pre paration de la requete SQL
$prep = $bdd->prepare($requete);
// Exécution de la requete 
$prep->execute(array($numcandidat));


?>

<html>
    <head>
        <meta charset = "UTF-8" />
        <title>Suppression Candidats</title>
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
                        <legend>Suppression Candidat</legend>

                        <br><br>
<?php
$nom = $ligne['CDT_NOMCANDIDAT'];
$prenom = $ligne['CDT_PRENOMCANDIDAT'];
echo "Vous vennez de supprimer le candidat numero $numcandidat de la base de donnee"
?>


                        <br><br>
                        <a href="CANDIindex.php" style="color : #B2293B;">Retour Index</a>
                    </div></div></div>
        </div>
    </body>
</html>