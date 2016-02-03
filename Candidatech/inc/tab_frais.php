<?php include_once 'inc/connection.php'; ?>
<?php include_once 'calendrier/fonction_nomMois.php'; ?>
<?php include_once 'calendrier/fonction_nbjoursouvres.php'; ?>

<?php
$nomMois = nomMois($mois);
$jourouvre = joursouvres($mois, $annee);
$modeFrais = $_POST['mode'];

$nsequentiel = null;
$nof_no = null;
$etat = '';
$regle = 0;

// récupérer la note de frais non validée et la réafficher
$query = "SELECT NOF_NO, NOF_NSEQUENTIEL, NOF_ETAT, NOF_REGLER FROM NOTE_FRAIS WHERE COL_NO = " . $collab . " AND NOF_MOIS = " . $mois . " AND NOF_ANNEE = " . $annee . " and TYF_NO = 1;";
$result = $connexion->query($query);
if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $nsequentiel = $row['NOF_NSEQUENTIEL'];
    $nof_no = $row['NOF_NO'];
    $etat = $row['NOF_ETAT'];
    $regle = $row['NOF_REGLER'];
    $query = "SELECT * FROM LIGNE_FRAIS WHERE NOF_NO = " . $nof_no;
    $result = $connexion->query($query);
    $row_getlignes = $result->fetch_assoc();
}
?>
<div id = div1 style = 'margin-left:2em;' class=' well' align="center">
    <input type="hidden" name="nsequentiel" id="nsequentiel" value="<?php echo $nsequentiel ?>">
    <input type="hidden" name="nbligne" id="nbligne" value="1">
    <input type="hidden" name="mois" id="mois" value="<?php echo $mois; ?>">
    <input type="hidden" name="annee" id="annee" value="<?php echo $annee; ?>">
    <?php
    // cumul des jours travaillés
    $query = "SELECT SUM(RAM_NBH) AS NBH FROM RAM WHERE COL_NO = " . $collab . " AND RAM_MOIS = " . $mois . "  AND RAM_ANNEE =" . $annee;
    $result = $connexion->query($query);
    $row = $result->fetch_assoc();
    // forfait journalier
    $query_frais = "SELECT INT_FRAIS FROM INTERNE WHERE COL_NO = " . $collab;
    $result_frais = $connexion->query($query_frais);
    $row_frais = $result_frais->fetch_assoc();

//    if ($nof_no != null) {
//        $query_etat = "SELECT NOF_ETAT, NOF_REGLER FROM NOTE_FRAIS WHERE NOF_NO = " . $nof_no;
//        $result_etat = $connexion->query($query_etat);
//        $row_etat = $result_etat->fetch_assoc();
//    }
    
    $query_coll = "SELECT * FROM COLLABORATEUR WHERE COL_NO = '" . $collab . "';";
    $result_coll = $connexion->query($query_coll);
    $row_coll = $result_coll->fetch_assoc();

    if ($modeFrais == 'voir') {
        echo '<b>' . $row_coll['COL_NOM'] . ' ' . $row_coll['COL_PRENOM'] . '</b> - ' . $nsequentiel . '<br>';
    }
    echo '<b> Note de frais forfaitaire -- ' . $nomMois . ' ' . $annee . '</b><br>';
    echo 'Jours ouvrés : ' . $jourouvre . '<br>';

//    if (isset($row_etat['NOF_ETAT'])) {
    if ($etat == 'V') {
        if ($regle == true) {
            echo 'Statut : <font color = green>Validée</font> -- <font color = green>Payée</font><br><br>';
        } else {
            echo 'Statut : <font color = green>Validée</font> -- <font color = red>Non Payée</font><br><br>';
        }
    } elseif ($etat == 'R') {
        echo 'Statut : <font color = red>Refusée</font><br><br>';
    } else {
        echo 'Statut : <font color = orange>En cours</font><br><br>';
    }
//    }
    ?>

    <table border="1" class='table-bordered'>
        <input type="hidden" id="fnbh" name="fnbh" value="<?php echo ($row['NBH']); ?>">
        <input type="hidden" id="ffrais" name="ffrais" value="<?php echo ($row_frais['INT_FRAIS']); ?>">
        <input type="hidden" id="fresult" name="fresult" value="0">
        <thead>
            <tr>
                <th>Mois</th>
                <th>Objet</th>
                <th>NB de jours travaillés</th>
                <th>Montant Journalier en &euro;</th>
                <th>Montant total en &euro;</th>
            </tr>
        </thead>
        <tbody>
            <tr id="1">
                <td id="jour"><?php echo $nomMois; ?></td>
                <td> Forfait Déplacement </td>
                <td id="nbh" name="nbh"><?php echo ($row['NBH']); ?></td>
                <?php print_r($row_frais); ?>
                <td id="frais" name="frais"><?php echo ($row_frais['INT_FRAIS']) ?></td>
                <td id="result" name="result">0</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td style="visibility: hidden"></td>
                <td style="visibility: hidden"></td>
                <td style="visibility: hidden"></td>
                <th>TOTAL</th>
                <td id="result_tot">&euro;</td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        nbh = parseFloat($('#nbh').html());
        montant = parseFloat($('#frais').html());
        result = Math.round((nbh * montant) * 100) / 100;
        $('#fresult').val(result);
        $('#result').html(result);
        $('#result_tot').html(result)
    });
</script>

