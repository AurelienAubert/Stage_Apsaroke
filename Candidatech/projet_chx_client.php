<?php require "inc/verif_session.php"; ?>
<?php include ("inc/connection.php"); ?>
<?php include("calendrier/fonction_mois.php"); ?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Choix des clients</title>
        <script type='text/javascript'>
 
			function getXhr(){
                                var xhr = null; 
				if(window.XMLHttpRequest) // Firefox et autres
				   xhr = new XMLHttpRequest(); 
				else if(window.ActiveXObject){ // Internet Explorer 
				   try {
			                xhr = new ActiveXObject("Msxml2.XMLHTTP");
			            } catch (e) {
			                xhr = new ActiveXObject("Microsoft.XMLHTTP");
			            }
				}
				else { // XMLHttpRequest non supporté par le navigateur 
				   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
				   xhr = false; 
				} 
                                return xhr;
			}
 
			/**
			* Méthode qui sera appelée sur le click du bouton
			*/
			function go(){
				var xhr = getXhr();
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
					// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
					if(xhr.readyState == 4 && xhr.status == 200){
						leselect = xhr.responseText;
						// On se sert de innerHTML pour rajouter les options a la liste
						document.getElementById('recherche').innerHTML = leselect;
					}
				}
 
				// Ici on va voir comment faire du post
				xhr.open("POST","ajaxProjet.php",true);
				// ne pas oublier ça pour le post
				xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				// ne pas oublier de poster les arguments
				// ici, l'id de l'auteur
				sel = document.getElementById('client');
				idclient = sel.options[sel.selectedIndex].value;
				xhr.send("idClient="+idclient);
			}
		</script>
   </head>
<body>
<!-- Barre de menu-->
<?php
    $GLOBALS['titre_page'] = '<div class="autre">Projet</div>';
    include ("menu/menu_global.php");
    
    
    $action = $_GET['action'];
    
     if($action == 'suppression')
    {
        $redirection = 'suppression.php?type=projet';
    }

    if($action == 'affichage')
    {
        $redirection = 'affichage.php?type=projet';
    }

    if($action == 'modification')
    {
        $redirection = 'modification.php?type=projet';
    }

?>
<div class="container-fluid ">
    <div class="row-fluid">
    <div class="span12">
        <form name="mon_form" action="<?php echo $redirection;?>" method="post" class="well" id="form2" style="background-color: #f7f7f9;">
            <input name="mode" value="creer" type="hidden"></input>
                <div class="row-fluid"  style="background-color: #f7f7f9;">
                    <div style="margin-left:22em; margin-right: -18em;" class="span4 client1">Client : </div>
                    <div class="span3">
                      <select name='client' id='client' onchange='go()' required>
                          <option value=''> </option>
                            <?php
                                $res = $GLOBALS['connexion']->query("SELECT * FROM CLIENT ORDER BY CLI_NOM");
                                while($row = mysqli_fetch_assoc($res)){
                                        echo "<option value='".$row["CLI_NO"]."'>".$row["CLI_NOM"]."</option>";
                                }
                            ?>
                    </select>
                    <div id='recherche' style='display:inline'>
                        <select name='recherche' required>
                                <option value=''> </option>
                        </select>
                    </div>
                    </div><div class="row">
                                <div class="span12"><br></div></div>    
                            </div> 
                            <div class="row-fluid" style="margin-left: 13.1em;">
                                <div class="offset3 span7">
                                    <button class="btn btn-primary" type="submit"><?php if($action == "affichage" || $action == "modification"){echo 'Continuer';} else {echo 'Supprimer';}?><i class="icon-ok"></i> </button><br><br>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>






