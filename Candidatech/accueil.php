<?php require "inc/verif_session.php"; ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Accueil</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = '<div class="accueil">Accueil</div>';
            include ("menu/menu_global.php");
        ?>
        <h4 class="not_printed "><?php echo $GLOBALS['num_version'] . ' du ' . $GLOBALS['date_version']; ?>, compatible IE 11, Chrome, Firefox</h4>
<?php
// recherche si message existant
$query = "SELECT * FROM MESSAGE";
$result = $GLOBALS['connexion']->query($query)->fetch_assoc();

// Ajustement de la hauteur du Textarea à la taille du message.
$style = '';
$not = str_replace("\n", "", $result['MES_NOTE']);
$nbl = explode("\r", $not);
$haut = count($nbl) * 20;
$style = ' style="height:' . $haut . 'px"';

if (isset($_SESSION['col_id']) && $_SESSION['accreditation'] < 5) {
?>
        <form id='accueil' action='' method='post'>
            <div class="row-fluid not_printed">
                <div class="span2">
                   <input type="button" value="Livre de bord" onclick="javascript:livredebord();" class='btn btn-primary'></input> 
                </div>
                <div class="span2">
                   <input type="button" value="Notes de service" onclick="javascript:noteservice();" class='btn btn-primary'></input> 
                </div>
                </br></br></br></br></br></br></br>
                <div class="span12">
                    <?php
                    if (count($result) > 0){
                        echo '<textarea class="textarea800" readonly ' . $style . '>' . $result['MES_NOTE'] . '</textarea>';
                    }
                    ?>
                </div>
            </div>
            <script>
                function livredebord(){
                    document.forms[0].action = 'liste_livredebord.php';
                    document.forms[0].submit();
                }
                function noteservice(){
                    document.forms[0].action = 'liste_noteservice.php';
                    document.forms[0].submit();
                }
            </script>
        </form>
<?php
}
?>
    </body>
</html>