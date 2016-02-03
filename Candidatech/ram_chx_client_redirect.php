<?php
if (isset($_POST['continuer']) && $_POST['client1'] == null) {
    $cible = "ram_chx_client.php";
} elseif (isset($_POST['continuer']) && $_POST['client1'] != null) {
    $cible = "tableau_ram.php";
} elseif (isset($_POST['ajouter'])) {
    $cible = "ram_chx_client.php";
} else {
    $cible = "tableau_ram.php";
}
$nb_client = $_POST['nb_client'];
for ($i = 1; $i <= $nb_client; $i++) {
    //Si on a cliqué sur continuer avec une liste déroulante vide
    if (isset($_POST['continuer']) && $_POST['client' . $i] == null) {
        $nb_client--;
    }
    //Si on a cliqué sur ajouter avec une liste déroulabnte vide
    if (isset($_POST['ajouter']) && $_POST['client' . $i] == null) {
        $nb_client--;
    }
    //Si le RAm est déjà validé, un script de redirection nous a amené directement ici
    if (!isset($_POST['continuer']) && !isset($_POST['ajouter']) && $_POST['client' . $i] == null) {
        $nb_client--;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                window.document.forms[0].submit();
            });
        </script>
    </head>
    <body>
        <form name="form_temp" action="<?php echo $cible ?>" method="post" class="well" id="form2">
            <input type="hidden" name="mode" value ="<?php $_POST['mode'] ?>"></input>
<?php
for ($nb_select = 1; $nb_select <= $nb_client; $nb_select++) {

    if (isset($_POST['continuer']) || $_POST['client' . $nb_select] == null || $_POST['projet' . $nb_select] == null) {
        ?>
                    <input type="hidden" name="nb_client" value ="<?php echo $nb_client; ?>"></input>
        <?php
    } else {
        ?>
                    <input type="hidden" name="nb_client" value ="<?php echo $nb_client; ?>"></input>
                    <?php
                }
                if (!empty($_POST['client' . $nb_select])) {
                    ?>
                    <input type="hidden" name="client<?php echo $nb_select; ?>" value="<?php echo $_POST['client' . $nb_select]; ?>"></input>
                    <input type="hidden" name="projet<?php echo $nb_select; ?>" value="<?php echo $_POST['projet' . $nb_select]; ?>"></input>
                <?php
                }
            }
            ?>
            <input type="hidden" name="mode" value ="<?php echo $_POST['mode']; ?>"></input>
            <input type="hidden" name="annee" value ="<?php echo $_POST['annee']; ?>"></input>
            <input type="hidden" name="mois" value ="<?php echo $_POST['mois']; ?>"></input>

        </form>
    </body>
</html>