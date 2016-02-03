<?php require ("inc/verif_session.php");
include ("inc/connection.php"); 
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Création login</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
            $GLOBALS['titre_page'] = "<div class=''>Création/Mise à jour d'un mot de passe pour un collaborateur </div>";
            include ("menu/menu_global.php");
        ?>
        <div class="row-fluid ">
            <div class="offset3 span5 ">
                <form action="validation_autorisation.php" method="post" class="well" id="form2">
                    <fieldset>
                        <legend>Collaborateur</legend>
                        <div class="row-fluid">
                            <div class="span4 ">
                                <label for='collab_interne'>Collaborateur :</label>
                            </div>
                            <div class="span3">
                                <?php
                                try
                                {
                                    //Requête SQL qui récupère nom prénom et Id de tous les collab (interne et externe) et qui les affichent dans une liste déroulante
                                    $query1 = "SELECT COL_NO, CONCAT_WS(' ',UPPER(COL_NOM), COL_PRENOM) AS nom_complet FROM COLLABORATEUR ORDER BY COL_NOM, COL_PRENOM";
                                    //exécution de la requête SQL
                                    $result1 = $GLOBALS['connexion']->query($query1);
                                    if (mysqli_num_rows ($result1) >= 1) {
                                        echo "<select name='collab'>";
                                        while ($row = $result1->fetch_assoc ()) {
                                            echo "<option name='collab' value='" . $row['COL_NO'] . "'>" . $row['nom_complet'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                } catch (Exception $e) {
                                    // message en cas d"erreur
                                    die ('Erreur : ' . $e->getMessage ());
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 ">
                                <label for='type_aut'>Autorisation :</label>
                            </div>
                            <div class="span3">
                                <?php
                                try
                                {
                                    //Requête SQL qui récupère nom prénom et Id de tous les collab (interne et externe) et qui les affichent dans une liste déroulante
                                    $query2 = "SELECT TAU_NO, TAU_LIBELLE FROM TYPE_AUTORISATION ORDER BY TAU_NO DESC";
                                    //exécution de la requête SQL
                                    $result2 = $GLOBALS['connexion']->query($query2);
                                    if (mysqli_num_rows ($result2) >= 1)
                                    {
                                        echo "<select name='type_aut'>";
                                        while ($row = $result2->fetch_assoc ())
                                        {
                                            echo "<option name='type_aut' value='" . $row['TAU_NO'] . "'>" . $row['TAU_LIBELLE'] . "</option>";
                                        }
                                        echo "</select>";
                                    }
                                } catch (Exception $e)
                                {
                                    // message en cas d"erreur
                                    die ('Erreur : ' . $e->getMessage ());
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 ">
                                <label for='pwd_interne'>Mot de Passe :</label>
                            </div>
                            <div class="span3">
                                <input type='password' name='pwd' required></input>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 ">
                                <label for='pwd_interne_key'>Mot de Passe Clé :</label>
                            </div>
                            <div class="span3">
                                <input type='password' name='pwd_key' required></input>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 "></div>
                            <div class="span5 ">
                                <button class='btn btn-primary' type='submit'>Valider <i class='icon-ok'></i> </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </body>
</html>