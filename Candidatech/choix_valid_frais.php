<?php include ("inc/connection.php"); ?>
<?php include ("calendrier/fonction_mois.php"); ?>
<?php include ("calendrier/fonction_nomMois.php"); ?>
<?php session_start(); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"/> 
<head>
    <title>Validation des frais</title>
    <?php include 'head.php'; ?>
</head>
<body>
    <!-- Barre de menu-->
    <?php
    $GLOBALS['titre_page'] = '<span class="frais">frais</span>';
    include ("menu/menu_global.php");
    ?>
    <div class="container ">
        <div class="row">
            <div class="offset3 span6 ">
                <form action=<?php
                if (isset($_POST['envoie']) && $_POST['envoie'] = !null) {
                    echo "tab_frais_collab.php";
                }
                else
                    echo "choix_valid_frais.php";
                ?>
                      method="post" enctype="multipart/form-data" class="well" id="form2">
                    <fieldset> 
                        <legend>Validation d'une demande de frais :</legend> <br /><br />
                        <div class="row" id='choix_nom'>
                            <?php
                            $query1 = "SELECT DISTINCT COL_NO FROM NOTE_FRAIS WHERE COALESCE(NOF_ETAT, '') = ''";
                            $result1 = $GLOBALS['connexion']->query($query1);
                            if (mysqli_num_rows($result1) >= 1) {
                                $style = "";
                                echo "<form action=\"choix_valid_conges.php\" method=\"post\" class=\"well\" id=\"formulaire_nom\">
                                            <div class=\"span2\">
                                                <label for=\"Collaborateur\" > Collaborateur :</label>
                                            </div>
                                            <div class = \"span2\">";
                                echo '<select name="coll_demande">', "\n";
                                while ($row = $result1->fetch_assoc()) {
                                    if ($_SESSION['accreditation'] == '1') {
                                        $query2 = "SELECT COL_NOM, COL_PRENOM FROM COLLABORATEUR WHERE COL_NO =" . $row['COL_NO'];
                                        $result2 = $GLOBALS['connexion']->query($query2);
                                        $nom_col = $result2->fetch_assoc();
                                        echo "<option name = 'collab' value = '" . $row['COL_NO'] . "' id='" . $row['COL_NO'] . "'>" . $nom_col['COL_NOM'] . ' ' . $nom_col['COL_PRENOM'] . "</option>";
                                    } else {
                                        if ($_SESSION['col_id'] != $row['COL_NO']) {
                                            $query2 = "SELECT COL_NOM, COL_PRENOM FROM COLLABORATEUR WHERE COL_NO =" . $row['COL_NO'];
                                            $result2 = $GLOBALS['connexion']->query($query2);
                                            $nom_col = $result2->fetch_assoc();
                                            echo "<option name = 'collab' value = '" . $row['COL_NO'] . "' id='" . $row['COL_NO'] . "'>" . $nom_col['COL_NOM'] . ' ' . $nom_col['COL_PRENOM'] . "</option>";
                                        }
                                    }
                                }
                                echo "</select>
                                        </div>
                                    </form>";
                            } else {
                                echo "<div class=\"span5\">
                                            Aucune demande de frais en attente.
                                            </div>";
                                $style = "style='display:none'";
                            }
                            if (isset($_POST['coll_demande'])) {
                                echo "<script type=text/javascript>
                                        $('#" . $_POST['coll_demande'] . "').attr('selected', 'true')
                                        </script>
                                        ";
                            }
                            ?>
                        </div>
                        <br/>
                        <div class='row'>
                            <?php
                            if (isset($_POST['coll_demande'])) {
                                $_SESSION['num_nol'] = $_POST['coll_demande'];
                                $query3 = "SELECT DISTINCT NOF_MOIS, NOF_ANNEE FROM NOTE_FRAIS WHERE COL_NO ='" . $_POST['coll_demande'] . "' AND COALESCE(NOF_ETAT, '') = '' ORDER BY NOF_ANNEE";
                                $result3 = $GLOBALS['connexion']->query($query3);
                                if (mysqli_num_rows($result3) >= 1) {
                                    echo " <div class=\"span2\">
                                    <label for=\"mois_annee\" > mois :</label> 
                                    </div>
                                    <div class=\"span2\">";
                                    echo '<select name="date_demande">', "\n";
                                    while ($row = $result3->fetch_assoc()) {
                                        $moi = nomMois($row['NOF_MOIS']);
                                        echo "<option name='date_frais' value='" . $row['NOF_MOIS'] . '-' . $row['NOF_ANNEE'] . "'>"
                                        . $moi . ' ' . $row['NOF_ANNEE'] . "</option>";
                                    }
                                    echo "</select>
                                        </div>";
                                }
                            }
                            ?>
                        </div>
                        <br/>
                        <div class='offset1 span6 '>
                            <input type="hidden" name="action" value="valider"></input>
                            <button class="btn btn-primary" type="submit" name='envoie' <?php echo $style; ?>>Continuer <i class="icon-ok"></i> </button>
                        </div>
                    </fieldset> 
                </form>
            </div>
        </div>
    </div>
</body>
</html>