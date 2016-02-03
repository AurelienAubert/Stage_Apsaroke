<?php session_start (); ?>

<?php
include ('inc/connection.php');?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Liste des projets</title>
        
        <?php include "head.php"; ?>
        
        <script type="text/javascript" src="js.js"></script>
    </head>   
    <body>
    <!-- Barre de menu-->
    <?php
    $GLOBALS['titre_page'] = '<div class="ram">Liste des projets</div>';
    include ("menu/menu_global.php"); 
    $tab_projets = array();
    
    ?>
    <div class="container-fluid ">
      <div class="row-fluid">
          <form name ="frm_liste_projets" method="POST" action="liste_projets.php">
               <?php
                    if (isset($_POST['projet'])) {
                        foreach ($_POST['projet'] as $pro_no=>$valeurs) {
                            $query = "UPDATE PROJET SET PRO_DTCLOTURE='" . $valeurs['PRO_DTCLOTURE'] . "', PRO_CLOTURE=" . $valeurs['PRO_CLOTURE'] . " WHERE PRO_NO=" . $pro_no;
                            $GLOBALS['connexion']->query($query);
                        }
                    }
                    $rq_projets = "SELECT * FROM PROJET P, CLIENT C WHERE C.CLI_NO = P.CLI_NO;";
                    $res_projets = $connexion->query($rq_projets);

                    while($ligne = mysqli_fetch_assoc($res_projets))
                    {
                        $tab_projets[$ligne['PRO_NO']]['NOM_PROJET']=$ligne['PRO_NOM'];
                        /*$tab_projets[$ligne['PRO_NO']]['DATE_DEBUT']=$ligne['PRO_DTDEBUT'];
                        $tab_projets[$ligne['PRO_NO']]['DATE_FIN']=$ligne['PRO_DTFINPREVUE'];*/
                        $tab_projets[$ligne['PRO_NO']]['DATE_CLOTURE']=$ligne['PRO_DTCLOTURE'];
                        $tab_projets[$ligne['PRO_NO']]['CLOTURE']=$ligne['PRO_CLOTURE'];
                        $tab_projets[$ligne['PRO_NO']]['NOM_CLIENT']=$ligne['CLI_NOM'];
                    }
                ?>
            <table border = "1" class='table-bordered  table-condensed offset1'style="margin-left: 11em;">
                <tr>
                    <th>NOM DU CLIENT</th>
                    <th>NOM DU PROJET</th>
                    <th>CLOTURER</th>
                    <th>AFFICHER</th>
                    <th>MODIFIER</th>
                </tr>
                <?php
                foreach ($tab_projets as $id=>$contenu) 
                {
                ?>
                <tr>
                    <td>
                       <?php
                        echo $contenu['NOM_CLIENT'];
                       ?>
                    </td>
                    <td>
                       <?php
                        echo $contenu['NOM_PROJET'];
                       ?>
                    </td>
                    <td>
                        <input type="hidden" name="projet[<?php echo $id;?>][PRO_DTCLOTURE]" value="<?php echo $contenu['DATE_CLOTURE']; ?>" id="<?php echo $contenu['DATE_CLOTURE']?>" class="<?php echo $id;?>"></input>
                        <?php if($contenu['CLOTURE']=='1')
                        {
                        ?>
                        <input name="projet[<?php echo $id;?>][PRO_CLOTURE]" value="1" type="radio" checked>
                            OUI
                        </input>
                        <input name="projet[<?php echo $id;?>][PRO_CLOTURE]" value="0" type="radio">
                            NON
                        </input>
                        <?php
                        }
                        else 
                        {
                        ?>
                        <input name="projet[<?php echo $id;?>][PRO_CLOTURE]" value="1" type="radio">
                            OUI
                        </input>
                        <input name="projet[<?php echo $id;?>][PRO_CLOTURE]" value="0" type="radio" checked>
                            NON
                        </input>
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <button style="display: block;" type="button" class="afficher btn btn-primary commentaire" value="<?php echo $id;?>">
                           Afficher
                       </button>
                    </td>
                    <td>
                        <button style="display: block;" type="button" class="modifier btn btn-primary commentaire" value="<?php echo $id;?>">
                           Modifier
                       </button>
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
            <br/>
            <input class="btn btn-primary" style="margin-left:31em;" type="submit" name="envoyer" value="Valider"></input>
        </form>
      </div>
    </div>
    <div id="date-dialog" title="Date">
        <input type="hidden" id="pro_no"></input>
        <input type="date" id="dtcloture" placeholder="AAAA-MM-JJ" pattern="^\d{4}\-\d{2}\-\d{2}$" required></input>
        <div style="display:none;">Date incorrecte</div>
    </div>
        <form id="form_afficher" method="POST" action="affichage.php?type=projet">
            <input type="hidden" name="recherche" value=""></input>
        </form>
      <form id="form_modifier" method="POST" action="modification.php?type=projet">
            <input type="hidden" name="recherche" value=""></input>
        </form>
            <script>
                $(document).ready(function() {
                    
                    $('.afficher').click(function() {
                        $('[name="recherche"]').val($(this).val());
                        $('#form_afficher').submit();
                    });
                     $('.modifier').click(function() {
                        $('[name="recherche"]').val($(this).val());
                        $('#form_modifier').submit();
                    });
                    $('#date-dialog').dialog({
                        autoOpen: false,
                        height: 200,
                        width: 250,
                        modal: true,
                        buttons: {
                            "Sélectionner": function() {
                                $(this).children('div').hide();
                                if (/^\d{4}\-\d{2}\-\d{2}$/.test($(this).children('#dtcloture').val())) {
                                    $('[name="projet[' + $('#pro_no').val() + '][PRO_DTCLOTURE]"]').val($('#dtcloture').val());
                                    $(this).dialog('close');
                                    $(this).children().val('');
                                }
                                else {
                                    $(this).children('div').css({
                                        color: '#610000',
                                        background: '#F0C8C8',
                                        border: '2px solid #610000',
                                        'text-align':'center'
                                    }).slideDown();
                                }
                            },
                            "Annuler": function() {
                                $(this).children('div').hide();
                                $(this).children().val('');
                                $(this).dialog('close');
                            }
                        }
                    });
                    
                    $('[name*="PRO_CLOTURE"]').change(function() {
                        if ($(this).val() == 1) {
                            var num = $(this).attr('name').match(/\d+/)[0];
                            $('#date-dialog').dialog('open').find('#pro_no').val(num);
                        }
                    });
                    
                });
            </script>
    </body>
</html>

