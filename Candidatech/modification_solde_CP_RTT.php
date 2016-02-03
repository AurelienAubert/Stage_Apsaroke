<?php
/*
 * Ajout du champ 'INT_SOLDE_DEFAUT' dans la table Interne et 'EXT_SOLDE_DEFAUT' dans la table externe
 */

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//////////PIERRE !!! Yo, votre mission, si vous//////////
//////////l'acceptez est de vous occuper du css//////////
//////////et de faire en sorte que la page     //////////
//////////conserve le scrolling lorsqu'elle est rechargée ... Yeah, la sortie, je m'enfuiiiiiiiiis
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

print_r($_POST);
include 'inc/connection.php';
if(isset($_POST['INT_SOLDE_CP']) || isset($_POST['EXT_SOLDE_CP']) || isset($_POST['INT_SOLDE_RTT']) || isset($_POST['EXT_SOLDE_RTT']))
{
    require 'conges/updateSoldeConge.php';
}
$queryInt = "select * from collaborateur C join interne I on C.col_no=I.col_no";
$collaborateurs = $GLOBALS['connexion']->query($queryInt);

//$queryExt = "select * from collaborateur C join externe E on C.col_no=E.col_no";
//$collaborateursExt = $GLOBALS['connexion']->query($queryExt);

$champ = "";
$title = "";
if(isset($_GET['type']) && $_GET['type'] == "CP")
{
    $title = 'Solde congés payés';
    $champ = 'INT_SOLDE_CP';
    $champ2 = 'EXT_SOLDE_CP';
}
if(isset($_GET['type']) && $_GET['type'] == "RTT")
{
    $title = 'Solde RTT';
    $champ = 'INT_SOLDE_RTT';
    $champ2 = 'EXT_SOLDE_RTT';
}
$anneeParametre = $GLOBALS['connexion']->query('Select PAR_VALEUR FROM PARAMETRE WHERE PAR_LIBELLE="ANNEE_DEPART_SOLDE"')->fetch_assoc();

function bouton($texte, $classe, $valeur, $classeCouleur, $idTab) {
    return '<input type="button" id="'.$valeur.'" class="btn ' . $classeCouleur . ' ' . $classe . '" value="' . $texte . '" onclick="sauvegarde('.$valeur.','.(string)$idTab.')"/>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php';
        echo '<title>'. $title .'</title>';
        echo '</head>';
        echo '<body>';
        require_once ("menu/menu_global.php");
        
        echo '<fieldset >';
        echo '<legend>Solde de démarrage pour l\'année '.$anneeParametre['PAR_VALEUR'].'</legend>';
        echo '<input id="type" type="hidden" name="type" value="'.$_GET['type'].'"/>';
        $table = '<table id="interne" border="1" class="table-bordered table-condensed" width="90%" align="center">
                    <tr>
                        <th colspan=4>Collaborateur interne</th>
                    </tr>
                    <tr>
                        <th>Nom du collaborateur</th>
                        <th>Prénom du collaborateur</th>';
        $table .= $_GET['type'] == 'CP' ? '<th>Solde des congés payés</th>' : '<th>Solde des RTT</th>';
        $table .= '<th></th></tr>';
        
        echo $table;
        while($collaborateur = $collaborateurs->fetch_assoc())
        {
            echo '<tr>';
            echo '<td>'.$collaborateur['COL_NOM'].'</td>';
            echo '<td>'.$collaborateur['COL_PRENOM'].'</td>';
            echo '<td><input id="Solde'.$collaborateur['COL_NO'].'" type="text" value="'.$collaborateur[$champ].'"></input></td>';
            echo '<td>'.bouton('Sauvegarder', 'valider', $collaborateur['COL_NO'], 'btn-primary', 'interne').'</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        
        echo '<br><br><br><br>';
            
//        $tableExt = '<table id="externe" border="1" class="table-bordered table-condensed" width="90%" align="center">
//                    <tr>
//                        <th colspan=4>Collaborateur externe</th>
//                    </tr>
//                    <tr>
//                        <th>Nom du collaborateur</th>
//                        <th>Prénom du collaborateur</th>';
//        $tableExt .= $_GET['type'] == 'CP' ? '<th>Solde des congés payés</th>' : '<th>Solde des RTT</th>';
//        $tableExt .= '<th></th></tr>';
//        echo $tableExt;
//        
//        while($collaborateurExt = $collaborateursExt->fetch_assoc())
//        {
//            echo '<tr>';
//            echo '<td>'.$collaborateurExt['COL_NOM'].'</td>';
//            echo '<td>'.$collaborateurExt['COL_PRENOM'].'</td>';
//            echo '<td><input id="Solde'.$collaborateurExt['COL_NO'].'" type="text" value="'.$collaborateurExt[$champ2].'"></input></td>';
//            echo '<td>'.bouton('Sauvegarder', 'valider', $collaborateurExt['COL_NO'], 'btn-primary','externe').'</td>';
//            echo '</tr>';
//        }
//        echo '</table>';
        ?>
        </fieldset>
    <script>
        function sauvegarde(id, idTab)
            {
                var tableau = idTab.innerHTML.indexOf('Collaborateur interne');
                var col_no = id;
                var col_solde = document.getElementById('Solde'+id).value;
                var type = document.getElementById('type').value;
                
                var post = new Array();

                post['COL_NO'] = col_no;
                
                if(tableau != -1)
                    if(type == "CP")
                        post['INT_SOLDE_CP'] = col_solde;
                    else
                        post['INT_SOLDE_RTT'] = col_solde;
                else
                    if(type == "CP")
                        post['EXT_SOLDE_CP'] = col_solde;
                    else
                        post['EXT_SOLDE_RTT'] = col_solde;
                
                var page = 'modification_solde_CP_RTT.php?type='+type;
                openWithPostData(page, post);
                
               function openWithPostData(page,data)
                {
                    var form = document.createElement('form');
                    
                    form.setAttribute('action', page);
                    form.setAttribute('method', 'post');
                    
                    for (var n in data)
                    {
                      var inputvar = document.createElement('input');
                        inputvar.setAttribute('type', 'hidden');
                        inputvar.setAttribute('name', n);
                        inputvar.setAttribute('value', data[n]);
                         form.appendChild(inputvar);
                    }
                    document.body.appendChild(form);
                    form.submit();
               }
            }
            $(document).ready(function () {
                $("tr:odd").each(function() {
                    $(this).children().css("background-color", "#bbbbff");
                });
            });
    </script>
    </body>
</html><?php