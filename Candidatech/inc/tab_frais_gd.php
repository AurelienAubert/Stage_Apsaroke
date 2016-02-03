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
$query = "SELECT NOF_NO, NOF_NSEQUENTIEL, NOF_ETAT, NOF_REGLER FROM NOTE_FRAIS WHERE NOF_MOIS = " . $mois . " AND NOF_ANNEE = " . $annee . " AND COL_NO = " . $collab . " AND TYF_NO = 3;";
$result = $connexion->query($query);
if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $nsequentiel = $row['NOF_NSEQUENTIEL'];
    $nof_no = $row['NOF_NO'];
    $etat = $row['NOF_ETAT'];
    $regle = $row['NOF_REGLER'];
    $query = "SELECT LIF_JOUR, LIF_OBJET, LIF_NBJ_W, LIF_DETAIL, LIF_MONTANT FROM LIGNE_FRAIS WHERE NOF_NO = " . $nof_no;
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
    echo '<b> Note de frais grand déplacement -- ' . $nomMois . ' ' . $annee . '</b><br>';
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
                <th>Jours</th>
                <th>Objet</th>
                <th>NB de jours travaillés</th>
                <th>Détail</th>
                <th>Montant total en &euro;</th>
                <th>Supp.</th>
            </tr>
        </thead>
        <tbody>
            <tr id="1">
                <td><input type="date" name="jour1" id="jour1" style="width: 150px"/></td>
                <td><input type="text" name="objet1" id="objet1" style="width: 150px" required/></td>
                <td><input type="text" name="nbjW1" id="nbjW1" style="width: 125px" required/></td>
                <td><input type="text" name="detail1" id="detail1" style="width: 125px"></td>
                <td><input type="number" name="total1" id="total1" value="0" min="0.01" style="width: 150px" onchange="multi(this);" required/></td>
                <td style="width: 15px"><img name="suppr1" id="suppr1" class="icon-remove"  onclick="supLigne(1);" /></td>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                if (mysqli_num_rows($result_getligne) > 1) {
                    for ($i = 2; $i <= mysqli_num_rows($result_getligne); $i++) {
                        echo "
                        <tr id='" . $i . "'>
                            <td><input type='date' name='jour" . $i . "' id='jour" . $i . "' style='width: 150px'/></td>
                            <td><input type='text' name='objet" . $i . "' id='objet" . $i . "' style='width: 150px' required/></td>
                            <td><input type='text' name='nbjW" . $i . "' id='nbjW" . $i . "' style='width: 125px' required/></td>
                            <td><input type='text' name='detail" . $i . "' id='detail" . $i . "' style='width: 125px'/></td>
                            <td><input type='number' name='total" . $i . "' id='total" . $i . "' value='0' min='0.01' style='width: 150px' onchange='multi(this);' required/></td>
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
            echo "$('#jour" . $i . "').val('" . $row_getlignes['LIF_JOUR'] . "');";
            echo "$('#objet" . $i . "').val('" . $row_getlignes['LIF_OBJET'] . "');";
            echo "$('#nbjW" . $i . "').val('" . $row_getlignes['LIF_NBJ_W'] . "');";
            echo "$('#detail" . $i . "').val('" . $row_getlignes['LIF_DETAIL'] . "');";
            echo "$('#total" . $i . "').val('" . $row_getlignes['LIF_MONTANT'] . "');";
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
        total = 0;
        for (i = 1; i <= nbl; i++)
        {
            total += Math.round(parseFloat($('#total' + i).val()) * 100) / 100;
        }
        $('#result_tot').html(total);
        if(nbl < 2) $('img#suppr1').css("display", "none");
    }

    function supLigne(nol)
    {
        var nbl = $('#nbligne').val();
        var j = 0;
        for (i = nol; i < nbl; i++)
        {
            j = i; j++;
            $('#jour' + i).val($('#jour' + j).val());
            $('#objet' + i).val($('#objet' + j).val());
            $('#nbjW' + i).val($('#nbjW' + j).val());
            $('#detail' + i).val($('#detail' + j).val());
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
            $("<tr id='" + nbl + "'><td><input type='date' name='jour" + nbl + "' id='jour" + nbl + "' style='width: 150px'/></td><td><input type='text' name='objet" + nbl + "' id='objet" + nbl + "' style='width: 150px' required/></td><td><input type='text' name='nbjW" + nbl + "' id='nbjW" + nbl + "' style='width: 125px' required/></td><td><input type='text' name='detail" + nbl + "' id='detail" + nbl + "' style='width: 125px'></td><td><input type='text' name='total" + nbl + "' id='total" + nbl + "' value='0' style='width: 150px' onchange='multi(this); ' required/></td><td style='width: 15px'><img name='suppr" + nbl + "'  id='suppr" + nbl + "' class='icon-remove' onclick='supLigne(" + nbl + ");' /></td></tr>")
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