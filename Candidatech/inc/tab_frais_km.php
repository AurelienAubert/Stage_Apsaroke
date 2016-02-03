<?php include_once 'inc/connection.php'; ?>
<?php include_once 'calendrier/fonction_nomMois.php'; ?>
<?php include_once 'frais/fonction_fraiskilometrique.php'; ?>
<?php include_once 'calendrier/fonction_nbjoursouvres.php'; ?>
<?php
$nomMois = nomMois($mois);
$jourouvre = joursouvres($mois, $annee);
$modeFrais = $_POST['mode'];
$nbligne = 1;
$taux = null;

$nsequentiel = null;
$nof_no = null;
$etat = '';
$regle = 0;

// récupérer la note de frais non validée et la réafficher
$query = "SELECT NOF_NO, NOF_NSEQUENTIEL, NOF_ETAT, NOF_REGLER FROM NOTE_FRAIS WHERE NOF_MOIS = " . $mois . " AND NOF_ANNEE = " . $annee . " AND COL_NO = " . $collab . " AND TYF_NO = 4;";
$result = $connexion->query($query);
if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $nsequentiel = $row['NOF_NSEQUENTIEL'];
    $nof_no = $row['NOF_NO'];
    $etat = $row['NOF_ETAT'];
    $regle = $row['NOF_REGLER'];
    $query = "SELECT LIF_JOUR, LIF_CLIENT, LIF_VILLE, LIF_KM, LIF_TAUX_KM, LIF_TOTAL_KM FROM LIGNE_FRAIS WHERE NOF_NO = " . $nof_no;
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
    echo '<b> Note de frais kilométriques -- ' . $nomMois . ' ' . $annee . '</b><br>';
    echo 'Jours ouvrés : ' . $jourouvre . '<br>';
    
//    if (isset($row_etat['NOF_ETAT'])) {
//        if ($row_etat['NOF_ETAT'] == 'V') {
//            if ($row_etat['NOF_REGLER'] == true) {
//                echo 'Statut : <font color = green>Validée</font> -- <font color = green>Payée</font><br><br>';
//                $modeFrais = "voir";
//            } else {
//                echo 'Statut : <font color = green>Validée</font> -- <font color = red>Non Payée</font><br><br>';
//            }
//        } elseif ($row_etat['NOF_ETAT'] == 'R') {
//            echo 'Statut : <font color = red>Refusée</font><br><br>';
//        } else {
//            echo 'Statut : <font color = orange>En cours</font><br><br>';
//        }
//    }
    if ($etat == 'V') {
        if ($regle > 0) {
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
    $total = null;
    ?>
    
    <table border="1" class='table-bordered'>
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Ville</th>
                <th>KM parcourus</th>
                <th>Taux kilométrique</th>
                <th>Total</th>
                <th>Supp.</th>
            </tr>
        </thead>
        <tbody>
            <tr id="1">
                <td><input type="date" name="date1" id="date1" placeholder='AAAA-MM-JJ' style="width: 125px" /></td>
                <td><input type="text" name="client1" id="client1" style="width: 125px" required /></td>
                <td><input type="text" name="ville1" id="ville1" style="width: 125px" required /></td>
                <td><input type="number" name="km1" id="km1" style="width: 75px" min="1" onchange="multi(this);" required /></td>
                <td><input type="number" name="taux1" id="taux1" style="width: 150px" min="0.01" onchange="multi(this);" required /></td>
                <td id='total1' value='0'></td>
                <td style="width: 15px"><img name="suppr1" id="suppr1" class="icon-remove"  onclick="supLigne(1);" /></td>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                if (mysqli_num_rows($result_getligne) > 1) {
                    for ($i = 2; $i <= mysqli_num_rows($result_getligne); $i++) {
                        echo "
                        <tr id='" . $i . "'>
                            <td><input type='date' name='date" . $i . "' id='date" . $i . "' style='width: 125px' /></td>
                            <td><input type='text' name='client" . $i . "' id='client" . $i . "' style='width: 125px' required /></td>
                            <td><input type='text' name='ville" . $i . "' id='ville" . $i . "' style='width: 125px' required /></td>
                            <td><input type='number' name='km" . $i . "' id='km" . $i . "' style='width: 75px' min='1' onchange='multi(this);' required /></td>
                            <td><input type='number' name='taux" . $i . "' id='taux" . $i . "' style='width: 150px' min='0.01' onchange='multi(this);' required /></td>
                            <td id='total" . $i . "'></td>
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
                <td style="visibility: hidden"></td>
                <td style="visibility: hidden"></td>
                <th>TOTAL</th>
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
if (mysqli_num_rows($result) > 0) {
    if (mysqli_num_rows($result_getligne) >= 1) {
        $i = 1;
        while ($row_getlignes = $result_getligne->fetch_assoc()) {
            echo "$('#date" . $i . "').val('" . $row_getlignes['LIF_JOUR'] . "');";
            echo "$('#client" . $i . "').val('" . $row_getlignes['LIF_CLIENT'] . "');";
            echo "$('#ville" . $i . "').val('" . $row_getlignes['LIF_VILLE'] . "');";
            echo "$('#km" . $i . "').val('" . $row_getlignes['LIF_KM'] . "');";
            echo "$('#taux" . $i . "').val('" . $row_getlignes['LIF_TAUX_KM'] . "');";
            echo "$('#total" . $i . "').html('" . $row_getlignes['LIF_TOTAL_KM'] . "');";
            $total += $row_getlignes['LIF_TOTAL_KM'];
            $i++;
        }
    }
    $total = number_format($total,2);
    echo "$('#result_tot').html('" . $total . "');";
}
echo "</script>";
?>

<script type="text/javascript">
    function multi(ici)
    {
        var nbl = $('#nbligne').val();
        ligne = ($(ici).parent().parent().attr('id'));

        km = parseFloat($('#km' + ligne).val());
        taux = parseFloat($('#taux' + ligne).val());
        total = Math.round((km * taux) * 100) / 100;

        $('#total' + ligne).html(total);

        result_tot = 0;
        for (i = 1; i <= nbl; i++)
        {
            result_tot += Math.round(parseFloat($('#total' + i).html())* 100) / 100;
        }
        $('#result_tot').html(result_tot);
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
            $('#client' + i).val($('#client' + j).val());
            $('#ville' + i).val($('#ville' + j).val());
            $('#km' + i).val($('#km' + j).val());
            $('#taux' + i).val($('#taux' + j).val());
            $('#total' + i).val($('#total' + j).val());
        }
        $('#' + nbl).remove();
        nbl--;
        $('#nbligne').attr('value', nbl);
        multi("#total1");
    }

    $(document).ready(function() {
        $("button").click(function() {
            var nbl = $('#nbligne').val();
            nbl++;
            $('#nbligne').attr('value', nbl);
            $("<tr id='" + nbl + "'><td><input type='date' name='date" + nbl + "' placeholder='AAAA-MM-JJ' style='width: 125px'/></td><td><input type='text' name='client" + nbl + "' id='client" + nbl + "' style='width: 125px' required/></td><td><input type='text' name='ville" + nbl + "' id='ville" + nbl + "' style='width: 125px' required/></td><td><input type='text' name='km" + nbl + "' id='km" + nbl + "' style='width: 75px' onchange='multi(this);'></td><td><input type='text' name='taux" + nbl + "' id='taux" + nbl + "' style='width: 150px' onchange='multi(this);'/></td><td id='total" +nbl+ "'></td><td style='width: 15px'><img name='suppr" + nbl + "'  id='suppr" + nbl + "' class='icon-remove' onclick='supLigne(" + nbl + ");' /></td></tr>")
                .insertAfter("#" + (nbl - 1));
            $('img#suppr1').removeAttr("style");
        });
        <?php if ($modeFrais == 'voir') { ?>
            $("img[name^='suppr']").css("display", "none");
            $("input[type=text]").attr("readonly", "readonly");
            $("input[type=date]").attr("readonly", "readonly");
        <?php } ?>
        $('[name^="total"]').change();
    });
</script>