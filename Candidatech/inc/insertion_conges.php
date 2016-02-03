<?php

if (count(get_included_files()) > 1) {
    include_once 'inc/connection.php';
    include_once 'calendrier/fonction_nbjoursMois.php';
    include_once 'calendrier/fonction_nomMois.php';
    include_once 'inc/verif_parametres.php';
    include_once 'inc/envoi_email_conges.php';

} else {
    include_once 'connection.php';
    include_once '../calendrier/fonction_nbjoursMois.php';
    include_once '../calendrier/fonction_nomMois.php';
    include_once 'verif_parametres.php';
    include_once 'envoi_email_conges.php';
}

$nbjoursMois = nbjoursMois($_POST['mois'], $_POST['annee']);

$result = $GLOBALS['connexion']->query("SELECT TYA_NO, TYA_LIBELLE FROM TYPE_ABSENCE");
$types = array();
while ($row = $result->fetch_assoc()) {
    $types[str_replace(' ', '', $row['TYA_LIBELLE'])] = $row['TYA_NO'];
}

$notification = array();

switch ($_POST['action']) {
    case 'valider':
        $tab_jour = array();
        $tab_abs = array();
        // Sélection des congés en cours : ABS_VALIDATION=0 (saisie collab) ou ABS_VALIDATION=2 (modif RAM admin)
        $select = "SELECT * FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND (ABS_VALIDATION=0 OR ABS_VALIDATION=2) ORDER BY ABS_JOUR";
        $result = $GLOBALS['connexion']->query($select);
        $insert_vars = array();

        while ($row = $result->fetch_assoc()) {
            array_push($tab_jour, $row['ABS_JOUR']);

            $insert_vars[$row['ABS_JOUR']] = array(
                'ABS_JOUR' => $row['ABS_JOUR'],
                'ABS_MOIS' => $_POST['mois'],
                'ABS_ANNEE' => $_POST['annee'],
                'TYA_NO' => $row['TYA_NO'],
                'COL_NO' => $_POST['col_id'],
                'ABS_NBH' => $row['ABS_NBH'],
                'ABS_VALIDATION' => 1,
                'ABS_DROIT' => $row['ABS_DROIT'],
                'ABS_NOTIFICATION' => $row['ABS_NOTIFICATION'],
                'ABS_ETAT' => (utf8_decode($_POST['commentaire' . $_POST['col_id']]) == null ? 1 : 2)
            );
        }
        
        $insert = "INSERT INTO ABSENCE (ABS_JOUR, ABS_MOIS, ABS_ANNEE, TYA_NO, COL_NO, ABS_NBH, ABS_VALIDATION, ABS_DROIT, ABS_NOTIFICATION, ABS_ETAT) VALUES ";

        for ($i = 1; $i <= $nbjoursMois; $i++) {
            /*
             * Comparaison entre les données reçues et la base
             */
            if (isset($_POST[$i])) {
                if (mb_detect_encoding($_POST[$i], 'UTF-8', true) === false) {
                    $nom = explode('-', $_POST[$i]);
                } else {
                    $nom = explode('-', utf8_decode($_POST[$i]));
                }
                $nbh = 1;
                if (count($nom) > 1) {
                    $nbh = 0.5;
                    unset($nom[1]);
                }
                $type_absence = $types[$nom[0]];

                if (isset($insert_vars[$i])) {
                    if ($nbh != $insert_vars[$i]['ABS_NBH'] || $type_absence != $insert_vars[$i]['TYA_NO']) {
                        $insert_vars[$i]['ABS_NBH'] = $nbh;
                        $insert_vars[$i]['TYA_NO'] = $type_absence;
                        $notification[] = "La demande pour le " . implode('/', array($i, $_POST['mois'], $_POST['annee'])) . " a été modifiée";
                    }
                } else {
                    $insert_vars[$i] = array(
                        'ABS_JOUR' => $i,
                        'ABS_MOIS' => $_POST['mois'],
                        'ABS_ANNEE' => $_POST['annee'],
                        'TYA_NO' => $type_absence,
                        'COL_NO' => $_POST['col_id'],
                        'ABS_NBH' => $nbh,
                        'ABS_VALIDATION' => 1,
                        'ABS_DROIT' => $row['ABS_DROIT'],
                        'ABS_NOTIFICATION' => $row['ABS_NOTIFICATION'],
                        'ABS_ETAT' => (utf8_decode($_POST['commentaire' . $_POST['col_id']]) == null ? 1 : 2)
                    );
                    $notification[] = "Le congé du " . implode('/', array($i, $_POST['mois'], $_POST['annee'])) . " a été enregistrée";
                }
            }
            
            // Test permettant de supprimer un congé qui avait été validé mais qui a été annulé par la suite
            if(!isset($_POST[$i]))
            {
                if(isset($insert_vars[$i]))
                {
                    if($insert_vars[$i]['ABS_NBH'] == 1 || $insert_vars[$i]['ABS_NBH'] == 0.5)
                    {
                        unset($insert_vars[$i]);
                    }
                }
            }
            
            /*
             * Ajout dans la base des données complètes
             */
            if (isset($insert_vars[$i])) {
                $insert .= "('" . implode("', '", $insert_vars[$i]) . "'), ";
            }
        }

        $delete = "DELETE FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND (ABS_VALIDATION=0 OR ABS_VALIDATION=2)";
        $GLOBALS['connexion']->query($delete);
        $GLOBALS['connexion']->query(substr($insert, 0, -2));

        $query_abs = "SELECT ABS_NO FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_JOUR IN (" . implode(', ', $tab_jour) . ");";
        $result_abs = $GLOBALS['connexion']->query($query_abs);

        while ($row_abs = $result_abs->fetch_assoc()) {
            array_push($tab_abs, $row_abs['ABS_NO']);
        }

        if ($_POST['commentaire' . $_POST['col_id']] != null) {
            $com = $_POST['commentaire' . $_POST['col_id']];

            $insert_com = "INSERT INTO COMMENTAIRE (COM_TEXTE) VALUES ('" . $com . "')";
            $GLOBALS['connexion']->query($insert_com);
            $com_no = $GLOBALS['connexion']->insert_id;
            foreach ($tab_abs as $id_abs) {
                $insert_abs_com = "INSERT INTO ABSENCE_COMMENTAIRE (ABS_NO, COM_NO) VALUES ('" . $id_abs . "', '" . $com_no . "');";
                $GLOBALS['connexion']->query($insert_abs_com);
            }
        }
        
        // Envoi d'un mail de validation de congés
        mail_valide_conges($tab_jour);
        
        $notification[] = '<font color=green>Demande de congés validée.</font>';
        break;
    case 'invalider':
        $update = "UPDATE ABSENCE SET ABS_VALIDATION = 0 WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_ETAT !=3";
        $GLOBALS['connexion']->query($update);
        break;
    case 'refuser':
        $tab_abs = array();
        $tab_jour = array();
        $select = "SELECT * FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_VALIDATION=0 ORDER BY ABS_JOUR";
        $result = $GLOBALS['connexion']->query($select);

        while ($row = $result->fetch_assoc()) {
            array_push($tab_abs, $row['ABS_NO']);
            array_push($tab_jour, $row['ABS_JOUR']);
        }

        if ($_POST['commentaire'] != null) {
            $_POST['commentaire'] = utf8_decode($_POST['commentaire']);

            $insert_com = "INSERT INTO COMMENTAIRE (COM_TEXTE) VALUES ('" . $_POST['commentaire'] . "')";
            $GLOBALS['connexion']->query($insert_com);
            $com_no = $GLOBALS['connexion']->insert_id;
            foreach ($tab_abs as $id_abs) {
                $insert_abs_com = "INSERT INTO ABSENCE_COMMENTAIRE (ABS_NO, COM_NO) VALUES ('" . $id_abs . "', '" . $com_no . "');";
                $GLOBALS['connexion']->query($insert_abs_com);
            }
        }

        $update = "UPDATE ABSENCE SET ABS_ETAT=3, ABS_VALIDATION=NULL WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_VALIDATION=0";
        $GLOBALS['connexion']->query($update);

        // Envoi d'un mail de refus de congés
        mail_refuse_conges($tab_jour);
        $notification[] = '<font color=green>Demande de congés refusée.</font>' . $m;
        break;
    case 'demande':
        /*
         * suppression des anciens enregistrements
         */
        $query = "DELETE FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_VALIDATION=0";
        $GLOBALS['connexion']->query($query);
        /*
         * ajout des enregistrements
         */
        $query = "INSERT INTO ABSENCE (ABS_JOUR, ABS_MOIS, ABS_ANNEE, TYA_NO, COL_NO, ABS_NBH, ABS_VALIDATION) VALUES ";
        $vars = array(
            'ABS_JOUR' => '',
            'ABS_MOIS' => $_POST['mois'],
            'ABS_ANNEE' => $_POST['annee'],
            'TYA_NO' => '',
            'COL_NO' => $_SESSION['col_id'],
            'ABS_NBH' => '',
            'ABS_VALIDATION' => 0
        );

        $compteur_conges = 0;
        $compteur_RTT = 0;
        $compteur_maladies = 0;
        $compteur_conges_ss = 0;
        $compteur_conges_excep = 0;
        $compteur_autres = 0;
        $nbJours = nbjoursMois($_POST['mois'], $_POST['annee']);
        for ($i = 1; $i <= $nbJours; $i++) {
            if (isset($_POST[$i])) {
                $vars['ABS_JOUR'] = $i;
                $vars['ABS_NBH'] = 1;
                $classe = explode('-', $_POST[$i]);

                if (count($classe) == 2) {
                    $vars['ABS_NBH'] = 0.5;
                }

                $vars['TYA_NO'] = $types[$classe[0]];

                if ($vars['TYA_NO'] == '2') {
                    $compteur_conges += count($vars['ABS_JOUR']);
                    $result = $GLOBALS['connexion']->query("SELECT TYA_NO, TYA_LIBELLE FROM TYPE_ABSENCE WHERE TYA_NO ='" . $vars['TYA_NO'] . "'");
                    $ligne_conges = $result->fetch_assoc();
                }
                if ($vars['TYA_NO'] == '3') {
                    $compteur_RTT += count($vars['ABS_JOUR']);
                    $result = $GLOBALS['connexion']->query("SELECT TYA_NO, TYA_LIBELLE FROM TYPE_ABSENCE WHERE TYA_NO ='" . $vars['TYA_NO'] . "'");
                    $ligne_RTT = $result->fetch_assoc();
                }
                if ($vars['TYA_NO'] == '5') {
                    $compteur_maladies += count($vars['ABS_JOUR']);
                    $result = $GLOBALS['connexion']->query("SELECT TYA_NO, TYA_LIBELLE FROM TYPE_ABSENCE WHERE TYA_NO ='" . $vars['TYA_NO'] . "'");
                    $ligne_maladies = $result->fetch_assoc();
                }
                if ($vars['TYA_NO'] == '6') {
                    $compteur_conges_ss += count($vars['ABS_JOUR']);
                    $result = $GLOBALS['connexion']->query("SELECT TYA_NO, TYA_LIBELLE FROM TYPE_ABSENCE WHERE TYA_NO ='" . $vars['TYA_NO'] . "'");
                    $ligne_conges_ss = $result->fetch_assoc();
                }
                if ($vars['TYA_NO'] == '7') {
                    $compteur_conges_excep += count($vars['ABS_JOUR']);
                    $result = $GLOBALS['connexion']->query("SELECT TYA_NO, TYA_LIBELLE FROM TYPE_ABSENCE WHERE TYA_NO ='" . $vars['TYA_NO'] . "'");
                    $ligne_conges_excep = $result->fetch_assoc();
                }
                if ($vars['TYA_NO'] == '8') {
                    $compteur_autres += count($vars['ABS_JOUR']);
                    $result = $GLOBALS['connexion']->query("SELECT TYA_NO, TYA_LIBELLE FROM TYPE_ABSENCE WHERE TYA_NO ='" . $vars['TYA_NO'] . "'");
                    $ligne_autres = $result->fetch_assoc();
                }
                $query .= "('" . implode("', '", $vars) . "'), ";
            }
        }
        //$tab_conges = array();

        $GLOBALS['connexion']->query(substr($query, 0, -2));
        // Fonction d'envoi de mail
        mail_demande_conges($ligne_conges, $ligne_RTT, $ligne_maladies, $ligne_conges_ss, $ligne_conges_excep, $ligne_autres, $compteur_conges, $compteur_RTT, $compteur_maladies, $compteur_conges_ss, $compteur_conges_excep, $compteur_autres);
        
        $notification[] = 'Demande de congés envoyée.';
        break;
    case 'droit':
        $update = "UPDATE ABSENCE SET ABS_" . strtoupper($_POST['action']) . "='" . $_POST['valeur'] . "' WHERE COL_NO='" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "'";
        $GLOBALS['connexion']->query($update);
        break;
    case 'notification':
        $update = "UPDATE ABSENCE SET ABS_" . strtoupper($_POST['action']) . "='" . $_POST['valeur'] . "' WHERE COL_NO='" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "'";
        $GLOBALS['connexion']->query($update);
        break;
}
if ($_POST['action'] == "demande"){
    echo implode('<br />', $notification);
}else{
        ?>
        <form id="form" action="tab_conges.php" method="post">
            <input type="hidden" name="annee" value="<?php echo $_POST['annee'] ?>"></input>
            <input type="hidden" name="mois" value="<?php echo $_POST['mois'] ?>"></input>
        </form>
        <script>
            $('#form').submit();
        </script>
        <?php
}
