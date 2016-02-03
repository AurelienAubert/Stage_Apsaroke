<?php
// Conexion a la BD
require_once 'CANDIbdd.inc';

// Récupération de la saisie formulaire
$codeDetail = $_POST['detail'];
$lieu = $_POST['lieu'];

//Appel de la fonction getBdd()
$bdd = getBdd();


//SI LIEU + RECHERCHE OK 
if(isset($lieu) && $_POST['independant'] == NULL){
    //preparation de la requete
$requete = "SELECT * FROM candi_candidat c join candi_technologie t on c.tch_codetechnologie=t.tch_codetechnologie join candi_etat_candidature e on c.et_cdt_codeetat=e.et_cdt_codeetat "
         . "where det_detail like '%$codeDetail%' AND CDT_VILLECANDIDAT= '$lieu' ORDER BY det_detail" ;

}


//SI INDEPENDANT + LIEU + RECHERCHE
else if(isset($_POST['independant']) && isset($_POST['lieu'])){
    //preparation de la requete
$requete = "SELECT * FROM candi_candidat c join candi_technologie t on c.tch_codetechnologie=t.tch_codetechnologie "
         . "join candi_etat_candidature e on c.et_cdt_codeetat=e.et_cdt_codeetat where det_detail "
         . "like '%".$codeDetail."%' AND c.et_cdt_codeetat=6 AND CDT_VILLECANDIDAT like '%".$lieu."%'   ORDER BY det_detail" ;
}

//SI QUE LIEU OK 
else if($_POST['independant'] == NULL && isset($_POST['lieu']) && $codeDetail==''){
    //preparation de la requete
$requete = "SELECT * FROM candi_candidat c join candi_technologie t on c.tch_codetechnologie=t.tch_codetechnologie "
         . "join candi_etat_candidature e on c.et_cdt_codeetat=e.et_cdt_codeetat where"
         . " CDT_VILLECANDIDAT like '%".$lieu."%'   ORDER BY det_detail" ;
}

//SI RECHERCHE + INDEPENDANT OK 
else if(isset($_POST['independant'])){
    //preparation de la requete
$requete = "SELECT * FROM candi_candidat c join candi_technologie t on c.tch_codetechnologie=t.tch_codetechnologie "
         . "join candi_etat_candidature e on c.et_cdt_codeetat=e.et_cdt_codeetat where det_detail "
         . "like '%".$codeDetail."%' AND c.et_cdt_codeetat=6 ORDER BY det_detail" ;
}

//SI Que INDEPENDANT 
else if(isset($_POST['independant']) && $lieu =='' && $codeDetail == ''){
    //preparation de la requete
$requete = "SELECT * FROM candi_candidat where et_cdt_codeetat=6 ORDER BY det_detail" ;
}


//SI QUE LA RECHERCHER OK 
else{
//preparation de la requete
$requete = "SELECT * FROM candi_candidat c join candi_technologie t on c.tch_codetechnologie=t.tch_codetechnologie "
         . "join candi_etat_candidature e on c.et_cdt_codeetat=e.et_cdt_codeetat where det_detail "
         . "like '%".$codeDetail."%' ORDER BY det_detail" ;
}


// Pre paration de la requete SQL
$prep = $bdd->prepare($requete);
// Execution de la requete 
$prep->execute(array($codeDetail));
// Calcul du nombre de lignes retournees par la requete
$nbCandidats = $prep->rowCount();
// Recuperation du tableau resultat requete (contenant toutes les lignes)
$rsCandidats = $prep -> fetchAll();

$bdd = null;
?>

<html>
    <head>
        <meta charset = "UTF-8" />
        <title>Recherche Candidats</title>
        <link rel="stylesheet" href="CANDIstyle.css" />
        <link rel="icon" type="image/png" href="apsaroke.png" /> 
        <?php include 'head.php' ?>
    </head>
    <body>
        
         <?php include ("menu/menu_accueil.php"); ?>   
        <br>
         
        
        <div class="container ">
            <div class="couleur">
            <div class="row">
                
                <div class="offset1 span4">
                   
                    <br>
                    <fieldset>
                       
                      <legend>Liste des candidats</legend>
            
                <p><?php echo "Il y a en tout : $nbCandidats candidat(s)"; ?>
                    <br> <br>    
                    
                    <table border =  border-radius="2px">
                        <tr><th>Competence candidat<th>Langage</th><th>Nom</th><th>Prenom</th>
                            <th>Date Disponibilite</th><th>Etat</th><th>Ville</th><th>Mnemonic</th><th>Joindre</th><th>Traitement</th></tr>
            <?php
            //Iteration sur les lignes du tableau resultat de la requete SQL
            foreach ($rsCandidats as $ligne){
                $technologie = $ligne['TCH_LIBELLETECHNOLOGIE'];
                $detail = $ligne['DET_DETAIL'];
                $nom = $ligne['CDT_NOMCANDIDAT'];
                $prenom = $ligne['CDT_PRENOMCANDIDAT'];
                $date = date("d-m-Y");
                $heure = date("H:i");
                $ville = $ligne['CDT_VILLECANDIDAT'];
                $date2 = "04/06/2015";
                $datedispo = $ligne['CDT_DATEDISPONIBILITE'];
                $etat = $ligne['ET_CDT_LIBELLEETAT'];
                $mnemo = $ligne['CDT_MNEMONIC'];
                $num=$ligne['CDT_NUMCANDIDAT'];
                if(strtotime($datedispo) <= strtotime($date2)){
                    echo "<tr><td>$technologie</td><td>$detail</td><td>$nom</td><td>$prenom</td><td>"
                       . "<span class='grasvert'>$datedispo</span></td><td>$etat</td><td>$ville</td><td>$mnemo</td><td>"
                       . "<A HREF='CANDIinfo.php?num_candidat=".$num."'><img src='telephone.png'></a></td>"
                       . "<td><table><td><div class='decalage2'>"
                       . "<a href='CANDImodifier.php?num_candidat=".$num."'>"
                       . "<img src='modifier.png' title='modifier'></a></div></td>"
                       . "<td><div class='decalage2'><a href='CANDIsupprimer.php?num_candidat=".$num."' >"
                       . "<img src='supprimer.png' title='supprimer'></a></div></td></table></td></tr>";
               
            }
                else {
                    echo "<tr><td>$technologie</td><td>$detail</td><td>$nom</td><td>$prenom</td>"
                       . "<td>$datedispo</td><td>$etat</td><td>$ville</td><td>$mnemo</td><td>"
                       . "<A HREF='CANDIinfo.php?num_candidat=".$num."'><img src='telephone.png'></a></td>"
                       . "<td><table><td><div class='decalage2'>"
                       . "<a href='CANDImodifier.php?num_candidat=".$num."'>"
                       . "<img src='modifier.png' title='modifier'></a></div></td>"
                       . "<td><div class='decalage2'><a href='CANDIsupprimer.php?num_candidat=".$num."'> "
                       . "<img src='supprimer.png' title='supprimer'></a></div></td></table></td></tr>";
               
            }
            }
            ?>
            </table>
                
        <br>
        <br>
        <form action="CANDIAjouteCandidat.php" method="post">
        <div class="decalage4">
        <div class='offset2 span2 '>
                <button class="btn btn-primary" type="submit">Ajouter un Candidat</button><br><br>
        </div>
        </div>
     
        </form>
                    </fieldset>
                    </div>
                </div>
                </div>
            </div>
       
        <div class="decalage">
         <footer>
            <?php 
            echo "Nous sommes le <strong>$date</strong> et il est <strong>$heure</strong>";
            ?>
             <a href="CANDIindex.php"><br><button>Retour vers l'index</button></a>
        </footer>
        </div>

    </body>
</html>