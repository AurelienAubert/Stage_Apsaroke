<?php include_once 'inc/connection.php'; ?>
<?php include_once 'calendrier/fonction_nomMois.php'; ?>
<?php include_once 'calendrier/fonction_nbjoursouvres.php'; ?>

<?php
$nomMois = nomMois($mois);
$jourouvre = joursouvres($mois, $annee);
$modeFrais = $_POST['mode'];
$nbligne = 1;

$nsequentiel = null;
$nof_no = null;
$etat = '';
$regle = 0;

// récupérer la note de frais non validée et la réafficher
$query = "SELECT NOF_NO, NOF_NSEQUENTIEL, NOF_ETAT, NOF_REGLER FROM NOTE_FRAIS WHERE NOF_MOIS = " . $mois . " AND NOF_ANNEE = " . $annee . " AND COL_NO = " . $collab . " AND TYF_NO = 2;";
$result = $connexion->query($query);
if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $nsequentiel = $row['NOF_NSEQUENTIEL'];
    $nof_no = $row['NOF_NO'];
    $etat = $row['NOF_ETAT'];
    $regle = $row['NOF_REGLER'];
    $query = "SELECT LIF_JOUR, LIF_OBJET, LIF_NUM_FACTURE, LIF_MONTANT, LIF_TVA FROM LIGNE_FRAIS WHERE NOF_NO = " . $nof_no;
    $result_getligne = $connexion->query($query);
    $nbligne = (int)mysqli_num_rows($result_getligne);
}
//if ($nof_no != null) {
//    $query_etat = "SELECT NOF_ETAT, NOF_REGLER FROM NOTE_FRAIS WHERE NOF_NO = " . $nof_no;
//    $result_etat = $connexion->query($query_etat);
//    $row_etat = $result_etat->fetch_assoc();
//}
?>
<div id = div1 style = 'margin-left:2em;' class=' well' align="center">
    <input type="hidden" name="nsequentiel" id="nsequentiel" value="<?php echo $nsequentiel ?>">
    <input type="hidden" name="nbligne" id="nbligne" value="<?php echo $nbligne ?>">
    <input type="hidden" name="mois" id="mois" value="<?php echo $mois; ?>">
    <input type="hidden" name="annee" id="annee" value="<?php echo $annee; ?>">
    
    <?php
    $query_coll = "SELECT * FROM COLLABORATEUR WHERE COL_NO = '" . $collab . "';";
    $result_coll = $connexion->query($query_coll);
    $row_coll = $result_coll->fetch_assoc();

    if ($modeFrais == 'voir' || $modeFrais == 'modif') {
        echo '<b>' . $row_coll['COL_NOM'] . ' ' . $row_coll['COL_PRENOM'] . '</b> - ' . $nsequentiel . '<br>';
    }
    echo '<b> Note de frais réels -- ' . $nomMois . ' ' . $annee . '</b><br>';
    echo 'Jours ouvrés : ' . $jourouvre . '<br>';

//    if (isset($row_etat['NOF_ETAT'])) {
    if ($etat == 'V') {
        if ($regle == true) {
            echo 'Statut : <font color = green>Validée</font> -- <font color = green>Payée</font><br><br>';
            $modeFrais = "voir";
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
        <thead>
            <tr>
                <th>Date</th>
                <th>Objet</th>
                <th>N° facture fournisseurs*</th>
                <th>Montant HT</th>
                <th>Montant TVA</th>
                <th>Total TTC</th>
                <th>Supp.</th>
            </tr>
        </thead>
        <tbody>
            <tr id="1">
                <td><input type="date" name="date1" id="date1" placeholder='AAAA-MM-JJ' style="width: 125px" required/></td>
                <td><input type='text' name="objet1" id='objet1' style="width: 150px" required></td>
                <td><input type="text" name="facture1" id="facture1" style="width: 150px"/></td>
                <td id="ht1">0</td>
                <td><input type="number" name="tva1" id="tva1" style="width: 100px" placeholder='0' onchange="multi(this);" step="any" required/></td>
                <td><input type="number" name="result1" id="result1" style="width: 100px" placeholder='0' onchange="multi(this);" step="any" required/></td>
                <td style="width: 15px"><img name="suppr1" id="suppr1" class="icon-remove" onclick="supLigne(1);" /></td>
            </tr>
<?php
/*
 * Ajoutes les lignes supplémentaires existants
 * et permet d'en ajouter d'autres
 */
if (mysqli_num_rows($result) > 0) {
    if (mysqli_num_rows($result_getligne) > 1) {
        for ($i = 2; $i <= mysqli_num_rows($result_getligne); $i++) {
            echo "
                <tr id='" . $i . "'>
                    <td><input type='date' name='date" . $i . "' id='date" . $i . "' style='width: 125px' required/></td>
                    <td><input type='text' name='objet" . $i . "' id='objet" . $i . "' style='width: 150px' required></td>
                    <td><input type='text' name='facture" . $i . "' id='facture" . $i . "' style='width: 150px'/></td>
                        <td id='ht" . $i . "'></td>
                    <td><input type='number' name='tva" . $i . "' id='tva" . $i . "' style='width: 100px' min='0.01' placeholder='0' onchange='multi(this);' step='any' required/></td>
                    <td><input type='number' name='result" . $i . "' id='result" . $i . "' style='width: 100px' min='0.01' placeholder='0' onchange='multi(this);' step='any' required/></td>
                    <td style='width: 15px'><img name='suppr" . $i . "'  id='suppr" . $i . "' class='icon-remove' onclick='supLigne(" . $i . ");' /></td>
                </tr>";
        }
    }
}
?>
        </tbody>
        <tfoot>
            <tr>
                <td style="visibility: hidden"></td>
                <td style="visibility: hidden"></td>
                <th>TOTAL</th>
                <td id="result_ht">0</td>
                <td id="result_tva">0</td>
                <td id="result_tot">0</td>
                <td style="visibility: hidden"></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <?php if ($modeFrais != 'voir') { ?>
        <button class="btn btn-primary not_printed" type="button">Ajouter une ligne <i class="icon-plus"></i></button><br>
    <?php } ?>
</div>

<?php
echo "<script type='text/javascript'>";
/*
 * Remplissage des lignes existantes
 */
if (mysqli_num_rows($result) > 0) {
    if (mysqli_num_rows($result_getligne) >= 1) {
        $i = 1;
        while ($row_getlignes = $result_getligne->fetch_assoc()) {
            echo "$('#date" . $i . "').val('" . $row_getlignes['LIF_JOUR'] . "');";
            echo "$('#objet" . $i . "').val('" . $row_getlignes['LIF_OBJET'] . "');";
            echo "$('#facture" . $i . "').val('" . $row_getlignes['LIF_NUM_FACTURE'] . "');";
            echo "$('#tva" . $i . "').val('" . $row_getlignes['LIF_TVA'] . "');";
            echo "$('#result" . $i . "').val('" . $row_getlignes['LIF_MONTANT'] . "');";
            $i++;
        }
    }
}
echo "</script>";
?>

<script type="text/javascript">
    function multi(ici)
    {
        var nbl = $('#nbligne').val();
        
        ligne = ($(ici).parent().parent().attr('id'));
        
        tva = parseFloat($('#tva' + ligne).val());
        result = parseFloat($('#result' + ligne).val());
        ht = Math.round((result - tva) * 100) / 100;

        $('#ht' + ligne).html(ht);

        total = Math.round((parseFloat($('#result1').val())) * 100) / 100;
        total_ht = Math.round((parseFloat($('#ht1').html())) * 100) / 100;
        total_tva = Math.round((parseFloat($('#tva1').val())) * 100) / 100;

        for (i = 2; i <= nbl; i++)
        {
            total += Math.round((parseFloat($('#result' + i).val())) * 100) / 100;
            total_ht += Math.round((parseFloat($('#ht' + i).html())) * 100) / 100;
            total_tva += Math.round((parseFloat($('#tva' + i).val())) * 100) / 100;
        }

        $('#result_tot').html(Math.round((total) * 100) / 100);
        $('#result_ht').html(Math.round((total_ht) * 100) / 100);
        $('#result_tva').html(Math.round((total_tva) * 100) / 100);
        if(nbl < 2) $('img#suppr1').css("display", "none");
    }

    function supLigne(nol)
    {
        var nbl = $('#nbligne').val();
        var j = 0;
        for (i = nol; i < nbl; i++)
        {
            j = i; j++;
            $('#date' + i).val($('#date' + j).val());
            $('#objet' + i).val($('#objet' + j).val());
            $('#facture' + i).val($('#facture' + j).val());
            $('#ht' + i).html($('#ht' + j).html());
            $('#tva' + i).val($('#tva' + j).val());
            $('#result' + i).val($('#result' + j).val());
        }
        $('#' + nbl).remove();
        nbl--;
        $('#nbligne').attr('value', nbl);
        multi("#tva1");
    }

    $(document).ready(function() {
        $("button").click(function() {
            var nbl = $('#nbligne').val();
            nbl++;
            $('#nbligne').attr('value', nbl);
            $("<tr id = '" + nbl + "'><td><input type = 'date' name = 'date" + nbl + "' id = 'date" + nbl + "' placeholder='AAAA-MM-JJ' style = 'width: 125px' required/></td><td><input type = 'text' name = 'objet" + nbl + "' id = 'objet" + nbl + "' style = 'width: 150px' required></td><td><input type = 'text' name = 'facture" + nbl + "' id = 'facture" + nbl + "' style = 'width: 150px'/></td><td id='ht" + nbl + "'></td><td><input type = 'text' name = 'tva" + nbl + "' id = 'tva" + nbl + "' style = 'width: 100px' onchange = 'multi(this);' placeholder = '0'/></td><td><input type='text' name='result" +nbl+ "' id='result" +nbl+ "' style='width: 100px' placeholder='0' onchange='multi(this);'/></td><td style='width: 15px'><img name='suppr" + nbl + "'  id='suppr" + nbl + "' class='icon-remove' onclick='supLigne(" + nbl + ");' /></td></tr>")
                .insertAfter("#" + (nbl - 1));
            $('img#suppr1').removeAttr("style");
        });
        <?php if ($modeFrais == 'voir') { ?>
            $("img[name^='suppr']").css("display", "none");
            $("input[type=text]").attr("readonly", "readonly");
            $("input[type=date]").attr("readonly", "readonly");
        <?php } ?>
        $('[name^="ht"], [name^="tva"]').change();
    });
</script>