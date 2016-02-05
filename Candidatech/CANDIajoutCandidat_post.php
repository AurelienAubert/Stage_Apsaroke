<?php
// Conexion a la BD
require_once 'CANDIbdd.inc';
$bdd = getBdd();

$codeTechnologie = $_POST['codeTechnologie'];
$codeEtat = $_POST['codeEtat'];
$codeSuivit = $_POST['codeSuivit'];
$codeStatut = $_POST['codeStatut'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$ville = $_POST['ville'];
$commentaire = $_POST['commentaire'];
$tel1 = $_POST['tel1'];
$tel2 = $_POST['tel2'];
$champMail = $_POST['mail'];
$date = $_POST['date'];
$mnemonic = $_POST['mnemonic'];
$cv = $_POST['cv'];
$codeDetail = $_POST['detail'];
$dossier = "null";
$requeteinsert = "INSERT INTO `candidatech`.`candi_candidat` "
        . "(`ET_CDT_CODEETAT`, `SVT_CODESUIVIT`, `STA_CODESTATUT`, `CDT_NOMCANDIDAT`, `CDT_PRENOMCANDIDAT`, `CDT_VILLECANDIDAT`, `CDT_COMMENTAIRE`, `CDT_TEL1CANDIDAT`, `CDT_TEL2CANDIDAT`, `CDT_MAILCANDIDAT`, `CDT_DATEDISPONIBILITE`, `CDT_MNEMONIC`, `CDT_PIECEJOINTE`, `TCH_CODETECHNOLOGIE`, `DET_DETAIL`, `CDT_DOSSIER`)"
        . " VALUES ('".$codeEtat."', '".$codeSuivit."', '".$codeStatut."', '".$nom."', '".$prenom."', '".$ville."', '".$commentaire."', '".$tel1."', '".$tel2."', '".$champMail."', '".$date."', '".$mnemonic."', '".$cv."', '".$codeTechnologie."', '".$detail."','".$dossier."');";

$prep = $bdd->prepare($requeteinsert);
$prep->execute(array($codeEtat, $codeSuivit, $codeStatut, $nom, $prenom, $ville, $commentaire, $tel1, $tel2, $champMail, $date, $mnemonic, $cv, $codeTechnologie, $codeDetail, $dossier));
$bdd = null;
$OK = true;
?>

<html>
    <head>
        <meta charset = "UTF-8" />
        <title>Ajout d'un candidat </title>
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
                    <legend>Ajout d'un candidat</legend>
                    
                    <?php
                if (isset($OK)) {
                echo "Ajout reussi : le Candidat $nom $prenom a ete insere !";
                
                }
                ?> 
                    <input type="hidden" name="recherche" value="<?php echo $codeDetail; ?>"></input>
                    </fieldset>
                    </div>
                </div>
                 <a href="CANDIindex.php"><button>Retour Index</button></a>
            </div>
        </div>
        
        
        
        
    </body>
</html>