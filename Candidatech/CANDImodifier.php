<!-- PARTIE DONNEES -->
<?php
// Conexion a la BD
require_once 'CANDIbdd.inc';
$bdd = getBdd();

$requete = "SELECT * FROM `candi_candidat` WHERE cdt_numcandidat=".$_GET['num_candidat']."";
// Preparation de la requete SQL
$prep = $bdd->prepare($requete);
// Execution de la requete 
$prep->execute();
// Recuperation du tableau resultat requete (contenant toutes les lignes)
$candidat = $prep -> fetchObject();


// Ecriture de la requete SQL dans la variable $requete 
$requetetech = "SELECT TCH_codeTechnologie, TCH_libelleTechnologie FROM candi_technologie ORDER BY TCH_codeTechnologie";
// PrÃ©paration de la requete SQL
$prep = $bdd->prepare($requetetech);
// ExÃ©cution de la requete 
$prep->execute();
// RÃ©cuperation du tableau resultat requete (contenant toutes les lignes)
$rstechnologie = $prep->fetchAll();

// Ecriture de la requete SQL dans la variable $requetedetail
$requeteEtat = "SELECT ET_CDT_codeEtat, ET_CDT_libelleEtat FROM CANDI_ETAT_CANDIDATURE ORDER BY ET_CDT_codeEtat";
// PrÃ©paration de la requete SQL
$prep = $bdd->prepare($requeteEtat);
// ExÃ©cution de la requete 
$prep->execute();
// RÃ©cuperation du tableau resultat requete (contenant toutes les lignes)
$rsetat = $prep->fetchAll();

// Ecriture de la requete SQL dans la variable $requetestatut
$requeteStatut = "SELECT STA_codeStatut, STA_libelleStatut FROM sta_statut ORDER BY sta_codestatut";
// PrÃ©paration de la requete SQL
$prep = $bdd->prepare($requeteStatut);
// ExÃ©cution de la requete 
$prep->execute();
// RÃ©cuperation du tableau resultat requete (contenant toutes les lignes)
$rsstatut = $prep->fetchAll();

// Ecriture de la requete SQL dans la variable $requetedetail
$requeteSuivit = "SELECT SVT_codeSuivit, SVT_libelleSuivit FROM CANDI_SUIVIT ORDER BY SVT_codeSuivit";
// PrÃ©paration de la requete SQL
$prep = $bdd->prepare($requeteSuivit);
// ExÃ©cution de la requete 
$prep->execute();
// RÃ©cuperation du tableau resultat requete (contenant toutes les lignes)
$rssuivit = $prep->fetchAll();

// Fermeture de la connexion
$bdd = null;
?>


<!-- PARTIE AFFICHAGE -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8" />
        <title>Modifier un candidat</title>
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
                    <fieldset>  
                        <legend>Modification de <span class="blue"><?php echo $candidat->CDT_NOMCANDIDAT ." ". $candidat->CDT_PRENOMCANDIDAT ?></span></legend>
        
                <form  action="CANDImodifierCandidat_post.php?num_candidat=<?php echo $num ?>" method="post">
                    <div class="bordure">
                    <div class="decalage">
                    
                        <legend>Qualifications</legend>
                   
                    <table>
                         
                        <tr>
                        <td>
                            <p>Competence :</p>
                        </td>
                        <td>    
                            <select name="codeTechnologie" id="champTechnologie">
                            <option value="<?php echo $candidat->TCH_codeTECHNOLOGIE ?>">Selectionnez Competence</option>   
                            
                            <?php
                            foreach ($rstechnologie as $ligne) {
                            $codet = $ligne['TCH_codeTechnologie'];
                            $libellet = $ligne['TCH_libelleTechnologie'];
                            echo "<option value=$codet>$libellet</option>";
                            }
                            ?>
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <p>Langage : </p>
                        </td>
                        <td><input type="text" name="detail" id="champDetail" value="<?php echo $candidat->DET_DETAIL ?>"/></td>
                        </tr>
                        <tr>
                            <td>
                                <p>Nom :</p>
                            </td>
                            <td><input type="text" name="nom" id="champNom" value="<?php echo $candidat->CDT_NOMCANDIDAT ?>" required /></td>
                        </tr>
                        <tr>
                            <td>
                                <p>Prenom :</p>
                            </td>
                            <td><input type="text" name="prenom" id="champPrenom" value="<?php echo $candidat->CDT_PRENOMCANDIDAT ?>" required /></td>
                        </tr>
                    </table>    
               
                    <br>
                    </div>
                    </div>
                    <br>
                    
                <div class="bordure">
                    <div class="decalage">
                    <legend>Informations</legend>
                        <br>
                <table>
                    <tr>
                        <td>
                            <p>Adresse Mail:</p>
                        </td>
                        <td><input type="text" name="mail" id="champMail" value="<?php echo $candidat->CDT_MAILCANDIDAT ?>" /></td>
                        
                    </tr>
                    <tr>
                        <td><p>Telephone1 :</p></td>
                        <td><input type="text" name="tel1" id="champTel1" value="<?php echo $candidat->CDT_TEL1CANDIDAT ?>" required /></td>
                    </tr>
                    <tr>
                        <td><p>Telephone2 :</p></td>
                        <td><input type="text" name="tel2" id="champTel2" value="<?php echo $candidat->CDT_TEL2CANDIDAT ?>" /></td>
                    </tr>
                    <tr>
                        <td><p>Ville :</p></td>
                        <td><input type="text" name="ville" id="champVille" value="<?php echo $candidat->CDT_VILLEANDIDAT ?>" /></td>
                    </tr>
                </table>
                    <br>
                    </div>
                    </div>
                    
                    <br>
                    
                <div class="bordure">
                    <div class="decalage3">
                        
                        <legend>Specifications</legend>
                    <br>
                    <table>
                        <tr>
                        <td>
                            <p >Etat candidature :</p>
                        </td>
                        <td>
                            <select name="codeEtat" id="champCodeEtat"  >
                            <option value=''>Selectionnez un Etat</option>   
                            <?php
                            foreach ($rsetat as $ligne) {
                            $codee = $ligne['ET_CDT_codeEtat'];
                            $libellee = $ligne['ET_CDT_libelleEtat'];
                            echo "<option value=$codee>$libellee</option>";
                            }
                            ?>
                            </select>
                        </td>
                        </tr>
                        <tr>
                            <td>
                                <p>STATUT :</p>
                            </td>
                            <td>
                            <select name="codeStatut" id="champCodeStatut"  >
                            <option value=''>Selectionnez un Statut</option>
                            <?php
                            foreach ($rsstatut as $ligne) {
                            $codea = $ligne['STA_codeStatut'];
                            $libellea = $ligne['STA_libelleStatut'];
                            echo "<option value=$codea>$libellea</option>";
                            }
                            ?>
                            </select>
                            </td>
                    </tr>
                    <tr>
                        <td><p title="(AAAA-MM-JJ)">Date Mise a jour:</p></td>
                        <td><input type="text" name="date" id="champDate" title="(AAAA-MM-JJ)" value="<?php echo $candidat->CDT_DATEDISPONIBILITE ?>" /></td>
                    </tr>
                    
                </table>
                <br>
                
                    
                    </div>
                </div>
                    
                <br>
                
                    <label for="champCODESUIVIT">Suivit de candidature :</label>
                   
                    <select name="codeSuivit" id="champCodeSuivit"  >
                    <option value=''>Selectionnez un Suivit</option>                        
                    <?php
                    foreach ($rssuivit as $ligne) {
                    $codes = $ligne['SVT_codeSuivit'];
                    $libelles = $ligne['SVT_libelleSuivit'];
                    echo "<option value=$codes>$libelles</option>";
                    }
                    ?>
                    </select>
               
                <br>
               
                    
               
            
                
                <br>
               
                    <label for="champCommentaire">Commentaire :</label>
                    <textarea name="commentaire" id="champCommentaire" rows="4" cols="40" value="<?php echo $candidat->CDT_COMMENTAIRE ?>" /></textarea>
               
                <br><br>
                <table>
                    <td><p for="champCV" >Piece Jointe :</p></td>
                    <td><input type="file" title="Choisir un fichier a importer" name="cv" multiple="1" aria-label="Telecharger un CV" id="champCV" value="<?php echo $candidat->CDT_PIECEJOINTE ?>" ></td>
                </table>
                <br>
                
                    <p for="champMnemonic" title="Correspond a  la premiere lettre du Prenom<br>+premiere lettre du Nom<br>
                       +derniere lettre du Nom">MneMonic :</p>
                    <input type="text" name="mnemonic" id="champMnemonic" value="<?php echo $candidat->CDT_MNEMONIC ?>"/>
                    
                
                <br>
                <br><br>
                
                <div class='offset2 span2 '>
                    <button class="btn btn-primary" type="submit">Modifier le Candidat</button><br><br>
                </div>
        </form>
                    <a href="CANDIrechercheCandidat.php"><button>Retour vers la Recherche</button></a>
                </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>