<!-- PARTIE DONNEES -->
<?php
// Conexion a la BD
require_once 'CANDIbdd.inc';
$bdd = getBdd();

// Ecriture de la requete SQL dans la variable $requete 
$requetetech = "SELECT TCH_codeTechnologie, TCH_libelleTechnologie FROM candi_technologie ORDER BY TCH_codeTechnologie";
// Préparation de la requete SQL
$prep = $bdd->prepare($requetetech);
// Exécution de la requete 
$prep->execute();
// Récuperation du tableau resultat requete (contenant toutes les lignes)
$rstechnologie = $prep->fetchAll();

// Ecriture de la requete SQL dans la variable $requetedetail
$requeteEtat = "SELECT ET_CDT_codeEtat, ET_CDT_libelleEtat FROM CANDI_ETAT_CANDIDATURE ORDER BY ET_CDT_codeEtat";
// Préparation de la requete SQL
$prep = $bdd->prepare($requeteEtat);
// Exécution de la requete 
$prep->execute();
// Récuperation du tableau resultat requete (contenant toutes les lignes)
$rsetat = $prep->fetchAll();
// Ecriture de la requete SQL dans la variable $requetestatut
$requeteStatut = "SELECT STA_codeStatut, STA_libelleStatut FROM sta_statut ORDER BY sta_codestatut";
// Préparation de la requete SQL
$prep = $bdd->prepare($requeteStatut);
// Exécution de la requete 
$prep->execute();
// Récuperation du tableau resultat requete (contenant toutes les lignes)
$rsstatut = $prep->fetchAll();

// Ecriture de la requete SQL dans la variable $requetedetail
$requeteSuivit = "SELECT SVT_codeSuivit, SVT_libelleSuivit FROM CANDI_SUIVIT ORDER BY SVT_codeSuivit";
// Préparation de la requete SQL
$prep = $bdd->prepare($requeteSuivit);
// Exécution de la requete 
$prep->execute();
// Récuperation du tableau resultat requete (contenant toutes les lignes)
$rssuivit = $prep->fetchAll();

// Fermeture de la connexion
$bdd = null;
?>


<!-- PARTIE AFFICHAGE -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8" />
        <title>Ajouter un candidat</title>
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
                    <legend>Ajout d'un Candidat</legend>
        
                <form  action="CANDIajoutCandidat_post.php" method="post">
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
                            <option value=''>Selectionnez Competence</option>   
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
                        <td><input type="text" name="detail" id="champDetail" /></td>
                        </tr>
                        <tr>
                            <td>
                                <p>Nom :</p>
                            </td>
                            <td><input type="text" name="nom" id="champNom" required /></td>
                        </tr>
                        <tr>
                            <td>
                                <p>Prenom :</p>
                            </td>
                            <td><input type="text" name="prenom" id="champPrenom" required /></td>
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
                        <td><input type="text" name="mail" id="champMail" /></td>
                        
                    </tr>
                    <tr>
                        <td><p>Telephone1 :</p></td>
                        <td><input type="text" name="tel1" id="champTel1" required /></td>
                    </tr>
                    <tr>
                        <td><p>Telephone2 :</p></td>
                        <td><input type="text" name="tel2" id="champTel2" /></td>
                    </tr>
                    <tr>
                        <td><p>Ville :</p></td>
                        <td><input type="text" name="ville" id="champVille" /></td>
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
                            <select name="codeEtat" id="champCodeEtat" required >
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
                            <select name="codeStatut" id="champCodeStatut" required >
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
                        <td><input type="text" name="date" id="champDate" title="(AAAA-MM-JJ)"/></td>
                    </tr>
                    
                </table>
                <br>
                
                    
                    </div>
                </div>
                    
                <br>
                
                    <label for="champCODESUIVIT">Suivit de candidature :</label>
                   
                    <select name="codeSuivit" id="champCodeSuivit" required >
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
                    <textarea name="commentaire" id="champCommentaire" rows="4" cols="40"/>Votre commentaire.</textarea>
               
                <br><br>
                <table>
                    <td><p for="champCV" >Piece Jointe :</p></td>
                    <td><input type="file" title="Choisir un fichier a�importer" name="cv" multiple="1" aria-label="Telecharger un CV" id="champCV"></td>
                </table>
                <br>
                
                    <p for="champMnemonic" title="Correspond a� la premiere lettre du Prenom<br>+premiere lettre du Nom<br>
                       +derniere lettre du Nom">MneMonic :</p>
                    <input type="text" name="mnemonic" id="champMnemonic" required value=""/>
                    
                
                <br>
                <br><br>
                <div class='offset2 span2 '>
                    <button class="btn btn-primary" type="submit">Ajouter le Candidat</button><br><br>
                </div>
        </form>
                    <a href="CANDIindex.php"><button>Retour Index</button></a>
                </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>