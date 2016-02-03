<?php
    include_once "connection.php";
    include_once 'calendrier/fonction_nomMois.php';
    include_once "calendrier/fonction_nbjoursMois.php";
    include_once 'inc/suppression_donnees.php';
    include_once 'inc/verif_parametres.php';
    
    if (isset($_POST['col_id'])) {
        $id = $_POST['col_id'];
    }
    else {
        $id = $_SESSION['col_id'];
    }
    $mode = "";
    if (isset($_POST['mode'])) {
        $mode = $_POST['mode'];
    }
//print_r($_POST);
?>
<script>//alert('<?php echo $_POST['mod_10'] . "-" . $_POST['mod_14']; ?>');</script>
<?php
    //récupération du nombre de jours en fonction du mois choisi via la fonction nbjoursMois();
    $nbjoursMois = nbjoursMois ($_POST['mois'], $_POST['annee']);

    /*
     * Suppression du RAM précédent
     */
    $query_sup = "SELECT RAM_NO FROM RAM WHERE COL_NO = " . $id . " AND RAM_ANNEE = " . $_POST['annee'] . " AND RAM_MOIS = " . $_POST['mois'];
    $result = $GLOBALS['connexion']->query ($query_sup);
    
    $liste_ram = array();
    while ($row = $result->fetch_assoc()) {
        $liste_ram[] = $row['RAM_NO'];
    }
    supprimer_ram($liste_ram);

    /*
     * ajout du message pour Apsaroke et récupération de l'ID
     */
    if(!isset($_POST['textarea_apsa']))
    {
        $_POST['textarea_apsa'] = " ";
    }

    $query_message = "INSERT INTO COMMENTAIRE (COM_TEXTE) VALUES ('" . $_POST['textarea_apsa'] . "')";
    $GLOBALS['connexion']->query($query_message);
    $id_apsa = $GLOBALS['connexion']->insert_id;

    /*
     * Ajout des informations de RAM et des commentaires pour chaque client
     */
    $i=1;
    while (isset($_POST['projet' . $i])) {
        $projet = $_POST['projet' . $i];

        if(!isset($_POST['textarea_'.$projet]))
        {
            $_POST['textarea_'.$projet] = " ";
        }
        
        $query_comm_cli = "INSERT INTO COMMENTAIRE (COM_TEXTE) VALUES ('" . $_POST['textarea_' . $projet] . "')";
        $GLOBALS['connexion']->query($query_comm_cli);
        $id_comm = $GLOBALS['connexion']->insert_id;

        $valeurs = array(
            'COL_NO'        => $id,
            'RAM_ANNEE'     => $_POST['annee'],
            'RAM_MOIS'      => $_POST['mois'],
            'PRO_NO'        => $projet,
            'COM_NO_CLI'    => $id_comm,
            'COM_NO_APSA'   => $id_apsa,
            'RAM_CLIENT'    => '',
            'RAM_JOUR'      => '',
            'RAM_NBH'       => '',
        );

        
        $query_ram = "INSERT INTO RAM (" . implode(', ', array_keys($valeurs)) . ') VALUES ';

        
        //On récupère le nom du client pour le projet choisi
        $query_nom_cli = 'SELECT PR.CLI_NO, CLI_NOM, PRO_NO FROM PROJET PR JOIN CLIENT CL'
            .' ON PR.CLi_NO = CL.CLI_NO WHERE PRO_NO = '.$projet;
        
        //Renvoi un objet de la classe client ET projet
        $stmt = $GLOBALS['connexion']->query($query_nom_cli)->fetch_object();


        for ($jour = 1; $jour <= $nbjoursMois; $jour++) {
            
            // traitement RAM
            $valeurs['RAM_CLIENT'] = $stmt->CLI_NOM;
            $valeurs['RAM_JOUR'] = $jour;
            $valeurs['RAM_NBH'] = $_POST[$projet . '-' . $jour];
            if ($valeurs['RAM_NBH'] != 0) {
                $query_ram .= "('" . implode("', '", $valeurs) . "'), ";
            }
            
            // Traitement congés modifiés
            $jc = "mod_" . $jour;
            if (isset($_POST[$jc])){
                $q_conge = "SELECT ABS_JOUR, ABS_NBH, TYA_NO FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_JOUR = '" . $jour . "'";
                $r_conge = $GLOBALS['connexion']->query($q_conge)->fetch_object();
                $valconge = 1 - $valeurs['RAM_NBH'];
                // Si le congé n'existe pas
                if ($r_conge == null){
                    // Insertion si congé modifié.
                    if ($valeurs['RAM_NBH'] != 1){
                        $qc  = "INSERT INTO ABSENCE (ABS_JOUR, ABS_MOIS, ABS_ANNEE, TYA_NO, COL_NO, ABS_NBH, ABS_VALIDATION, ABS_DROIT, ABS_NOTIFICATION, ABS_ETAT) VALUES ";
                        $qc .= "('" . $jour . "', '" . $_POST['mois'] . "', '" . $_POST['annee'] . "', '6', '" . $_POST['col_id'] . "', '" . $valconge . "', '2', '1', '1', '1')";
                        $rc = $GLOBALS['connexion']->query($qc);
                    }
                // Si le congé existe :
                }else{
                    // Modification si congé saisi...
                    if ($valeurs['RAM_NBH'] != 1){
                        $qc  = "UPDATE ABSENCE SET ";
                        $qc .= "TYA_NO='6', ABS_VALIDATION='2', ABS_NBH='" . $valconge . "' WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_JOUR = '" . $jour . "'";
                        $rc = $GLOBALS['connexion']->query($qc);
                    // ... ou suppression si congé enlevé (RAM_NBH=1)
                    }else{
                        $qc = "DELETE FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_JOUR = '" . $jour . "'";
                        $rc = $GLOBALS['connexion']->query($qc);
                    }
                }
            }
        }
        $GLOBALS['connexion']->query(substr($query_ram, 0, -2));
        $i++;
    }
    // Fin de maj :
    //  si modif Administrateur => retour tableau des ram
    //  si saisie du collaborateur => mail
    if ($mode == "modif"){
        ?>
        <form id="form" action="tab_coll_ram.php" method="post">
            <input type="hidden" name="annee" value="<?php echo $_POST['annee'] ?>"></input>
            <input type="hidden" name="mois" value="<?php echo $_POST['mois'] ?>"></input>
        </form>
        <script>
            $('#form').submit();
        </script>
        <?php
    }else{
        // Envoi d'un mail de notification
        $LigneMail[0] = '<p>Un RAM a été rempli par ' . $_SESSION['nom'] . ' ' . $_SESSION['prenom'] . '</b> pour <b>' . nomMois($_POST['mois']) . ' ' . $_POST['annee'] . '</b></p><br/><br/>';
        include 'envoi_email_RAM.php';

        echo '<h4>Votre RAM a bien été envoyé à l\'administration d\'Apsaroke</h4>';
    }
?>