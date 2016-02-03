<?php require "inc/verif_session.php"; ?>
<?php include ("inc/connection.php"); ?>
<?php include("calendrier/fonction_mois.php"); ?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Choix des clients</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = '<div class="ram">Choix du(des) client(s)</div>';
            $GLOBALS['retour_page'] = 'chx_date.php?type=edit_ram';
            include ("menu/menu_global.php");

        ?>
        
        <div class="container-fluid ">
            <div class="row-fluid">
                <div class="offset3 span5 ">
                    
                    <form  name="mon_form" action="ram_chx_client_redirect.php"  method="post" class="well" id="form2">
                        <input type="hidden" name="mode" value="creer"/>
                        <fieldset>
                            <legend>Client(s)</legend> <br />
                            <div class="">
                                <?php
                                
                                    //Fonction permettant de créer les zones de saisies pour un RAM
                                    //Entrée : La variable $nom qui contient la "valeur" du client ou projet 
                                    //(exemple : client1, client3, projet2). Ce n'est en aucun cas l'identifiant d'un client ou d'un projet
                                    //La variable $options contient l'ensemble de la table client ou projet (selon les données envoyées)
                                    //La variable $element est utilisé si le collaborateur a déjà saisie un RAM. Cette dernière contient
                                    //l'ensemble des client ou projets (selon le select) qui apparaissent dans le RAM pour l'année et le 
                                    //mois choisis auparavant
                                    //Sortie : On renvoi tout un code html qui permet la création d'une liste déroulant contenant 
                                    //les valeurs nécessaires
                                    //NB : Cette fonction possède une surcharge, "$element = null" signifie que l'on peut appeller cette 
                                    //fonction avec ou sans cette variable
                                    function select($nom, $options, $element = null){
                                        
                                        if(isset($element) || isset($_POST[''.$nom.'']))
                                        {
                                            $select = '<select name="' . $nom . '" required>';
                                        }
                                        else
                                        {
                                            $select = '<select name="' . $nom . '" >';
                                        }
                                            $select .= '<option value=""></option>';
                                        //1ère ligne de la liste déroulante
                                        foreach ($options as $no=>$option) {
                                            $classe = isset($option['classe'])?'class="'.$option['classe'].'" ':'';
                                            $value = 'value="' . $no . '" ';
                                            
                                            //Si un RAM existe déjà, on passe en selected le client ou projet existant
                                            if(isset($element) && $element == $no)
                                            {
                                                $select .= '<option '. $classe . $value . 'selected>'.$option['nom'].'</option>';
                                            }
                                            //Si on vient de la page "ram_chx_client_redirect", c'est-à-dire que l'on vient d'ajouter un 
                                            //client au RAM, on le fait appraitre en selected dans la liste déroulante
                                            elseif(isset($_POST[''.$nom.'']) && $no == $_POST[''.$nom.''])
                                            {
                                                //Si la valeurs de $no correspond à $_POST[$nom]
                                                //c'est-à-dire qu'il correspond au client ou projet
                                                //sélectionné précédemment. Alors, il le met en selected
                                                $select .= '<option ' . $classe . $value . ' selected>' . $option['nom'] . '</option>';
                                            }
                                            //On affiche une liste déroulante sans selected pour choisir un client à ajouter
                                            else
                                            {
                                                $select .= '<option ' . $classe . $value . '>' . $option['nom'] . '</option>';
                                            }
                                            
                                        }
                                        return $select . '</select>';
                                    }
                                    
                                    //Requête récupérant l'ensemble des numéros de projet qui font partis du RAM
                                    // du collaborateur connecté pour l'année et le mois choisis
                                    $query_ram = "SELECT DISTINCT(RA.PRO_NO), PR.CLI_NO
                                                    FROM RAM RA JOIN PROJET PR
                                                    ON RA.PRO_NO = PR.PRO_NO
                                                    WHERE RA.COL_NO = '" . $_SESSION['col_id'] . "' 
                                                    AND RA.RAM_ANNEE = '" . $_POST['annee'] . "' 
                                                    AND RA.RAM_MOIS = '" . $_POST['mois'] . "'";
                                    
                                    $result_ram = $GLOBALS['connexion']->query($query_ram);
                                    $liste_projet_client = array();
                                    
                                    while ($row_ram = $result_ram ->fetch_assoc()) {
                                        if ($row_ram['PRO_NO'] != NULL)
                                            $liste_projet_client[] = $row_ram;
                                    }
                                    

                                    
                                    //Requête récupérant l'ensemble de la table projet
                                    $query_pro = "SELECT PRO_NO, PRO_NOM, CLI_NO FROM PROJET WHERE PRO_ARCHIVE = 0 ORDER BY PRO_NOM ASC";
                                    $result_pro = $GLOBALS['connexion']->query($query_pro);
                                    
                                    $option_projet = array();
                                    while ($row_pro=$result_pro->fetch_assoc()) {
                                        $option_projet[$row_pro['PRO_NO']] = array(
                                            'nom'   => $row_pro['PRO_NOM'],
                                            'classe'=> $row_pro['CLI_NO'],
                                        );
                                    }
                                    
                                    //Requête récupérant l'ensemble des numéros et nom des client dans l'ordre alphabétique sur les nos
                                    $query_client = "SELECT CLI_NO, CLI_NOM FROM CLIENT ORDER BY CLI_NOM ASC";
                                    $result_client = $GLOBALS['connexion']->query($query_client);
                                    
                                    $option_client = array();
                                    //On affecte chaque ligne de result_client à row_client
                                    while ($row_client=$result_client->fetch_assoc()) {
                                        $option_client[$row_client['CLI_NO']] = array(
                                            'nom'   => $row_client['CLI_NOM'],
                                        );
                                    }
                                    
                                    $selects = array();
                                    
                                    $nb_select=0;
                                    
                                    //Si on arrive du formulaire "ram_chx_client_redirect", on affecte le nombre de client à nb_cli
                                    if(isset($_POST['nb_client']))
                                    {
                                        $nb_cli = $_POST['nb_client'];
                                        
                                        while($nb_select < $nb_cli)
                                        {
                                            $nb_select++;
                                            //On préremplie le formulaire pour les projets existant
                                            //récupéré par le POST
                                             if(isset($_POST['client'.$nb_select]))
                                             {
                                                    
                                                    $selects[] = array(
                                                    select('client' . $nb_select, $option_client, $_POST['client'.$nb_select]),
                                                    select('projet' . $nb_select, $option_projet, $_POST['projet'.$nb_select])
                                                    );
                                             }
                                             else
                                             {
                                                $selects[] = array(
                                                select('client' . $nb_select, $option_client),
                                                select('projet' . $nb_select, $option_projet)
                                                );
                                             }
                                        }
                                        
                                        $nb_select++;
                                        $nb_cli++;
                                        $selects[] = array(
                                        select('client' . $nb_select, $option_client),
                                        select('projet' . $nb_select, $option_projet)
                                        );
                                    }
                                    //Si un RAM est déjà existant, on affecte le nombre de ligne du ram à nb_cli
                                    elseif(isset($liste_projet_client) && count($liste_projet_client > 0))
                                    {
                                         $nb_cli = count($liste_projet_client);
                                         $nb_select++;
                                         //On préremplie le formulaire pour les projets existant
                                        foreach($liste_projet_client as $element)
                                        {
                                            $selects[] = array(
                                            select('client' . $nb_select, $option_client, $element['CLI_NO']),
                                            select('projet' . $nb_select, $option_projet, $element['PRO_NO'])
                                            );
                                            $nb_select++;
                                        }
                                        
                                        $nb_cli++;
                                        $selects[] = array(
                                        select('client' . $nb_select, $option_client),
                                        select('projet' . $nb_select, $option_projet)
                                        );
                                    }
                                    //Si le RAM n'existe pas
                                    else
                                    {
                                        $nb_cli = 1;
                                        
                                         //Tant que le nombre de liste déroulante est inférieur au nombre de client,
                                        //on affiche une nouveau liste déroulante
                                        while($nb_select < $nb_cli)
                                        {
                                            $nb_select++;
                                            $selects[] = array(
                                            select('client' . $nb_select, $option_client),
                                            select('projet' . $nb_select, $option_projet)
                                            );
                                        }
                                    }
                                    
                                    foreach ($selects as $num_cli=>$select) {
                                        echo '<div style="margin-left:0px" class="span4 client' . ($num_cli+1) . '">Client</div>';
                                        echo '<div class="span3">' . $select[0] . ' ' . $select[1] . '</div>';
                                        echo '<div class="row"><div class="span12"><br></div></div>';
                                        echo '<div class="projet">Projet</div>';
                                    }
                                    
                                    $query_isvalid = "SELECT DISTINCT(RAM_VALIDATION) FROM RAM WHERE COL_NO = '" . $_SESSION['col_id'] . "' AND RAM_ANNEE = '" . $_POST['annee'] . "' AND RAM_MOIS = '" . $_POST['mois'] . "'";
                                    $result_isvalid = $GLOBALS['connexion']->query($query_isvalid);
                                    if (mysqli_num_rows ($result_isvalid) >= 1) {
                                        $row_isvalid = $result_isvalid->fetch_assoc();
                                        if ($row_isvalid['RAM_VALIDATION'] == 1) {
                                            echo '<script>$(document).ready(function () { $("#form2").submit(); });</script>';
                                        }
                                    }
                                    ?>
                                <input type="hidden" name="nb_client" value ="<?php echo $nb_cli; ?>"></input>
                                <input type="hidden" name="annee" value="<?php echo $_POST['annee'] ?>"></input>
                                <input type="hidden" name="mois" value="<?php echo $_POST['mois'] ?>"></input>
                            </div>
                            <div class="row-fluid">
                                <div class='offset3 span7'>
                                    <button class="btn btn-primary" type="submit" name="continuer">Continuer <i class="icon-ok"></i> </button><br /><br />
                                    <button class="btn btn-primary" type="submit" name="ajouter" id="ajout_client">Ajouter un client <i class="icon-ok"></i> </button>
                                </div>
                            </div>
                        </fieldset> 
                    </form>
                </div>
            </div>
        </div>
        <script>
            function select(nom, options) {
                var select = '<select name="' + nom + '" required>';
                select += '<option value="" selected></option>';   
                for(no in options) {
                    classe = (typeof(options[no]['classe'])!=undefined)?'class="' + options[no]['classe'] + '" ':'';
                    value = 'value="' + no + '" ';
                    select += '<option ' + classe + value + '>' + options[no]['nom'] + '</option>';   
                }
                return select + '</select>';
            }
            
            function changer_client() {
                var name = 'projet' + $(this).attr('name').substr(6);
                var options = '[name="' + name + '"]>option';
                var id = $(this).val();
                
                if (id != ''){
                    //var nb = $(options).find('display', 'none').length,
                    $(options).filter('.' + id).show();
                    $(options).not('.' + id).hide();
                    var h = $(options).css('line-height');
                    $(options).css('height', h);
                }
            }
            
            $(document).ready(function () {
                
                $('#ajout_client').click(function() {
                    var nb_cli = eval($('input[name="nb_client"]').val());
                    var ajout = '<div style="margin-left:0px" class="span4 client' + (nb_cli+1) + '">Client</div>';
                    ajout += '<div class="span3">' + select('client' + (nb_cli+1), 0, option_client) + ' ' + select('projet' + (nb_cli+1), 0, option_projet) + '</div>';
                    ajout += '<div class="row"><div class="span12"><br></div></div>';
                    
                    $('.client' + nb_cli).nextAll().last().after(ajout);
                    $('[name^="client"]').last().change();
                    $('input[name="nb_client"]').val(nb_cli+1);
                });
                
                $('body').on('change', '[name^="client"]', changer_client);
                <?php
                    if (count($liste_projet_client)==0) {
                ?>
                        $('[name^="client"]').each(changer_client);
                <?php
                    }
                    else {
                ?>
                        $('[name^="client"]').each(function() {
                            var name = 'projet' + $(this).attr('name').substr(6);
                            var options = '[name="' + name + '"]>option';
                            var id = $(this).val();
                            $(options).filter('.' + id).show();
                            $(options).not('.' + id).hide();
                        });
                <?php
                    }
                ?>
            });
        </script>
    </body>
</html>
