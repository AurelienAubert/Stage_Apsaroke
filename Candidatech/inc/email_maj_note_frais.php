<?php

function envoimail_valide($id, $mois, $annee){
    $query_valid = "UPDATE NOTE_FRAIS SET NOF_ETAT ='V' WHERE NOF_NSEQUENTIEL = '" . $id . "'";
    $GLOBALS['connexion']->query ($query_valid);

    $q1 = "SELECT * FROM NOTE_FRAIS WHERE NOF_NSEQUENTIEL = '" . $id . "'";
    $r1 = $GLOBALS['connexion']->query($q1)->fetch_assoc();

    $comm = '';
    switch ($r1['TYF_NO']){
        case "1" : 
            // Forfait : cumul des jours travaillés
            $collab = $r1['COL_NO'];
            $q2 = "SELECT SUM(RAM_NBH) AS NBH FROM RAM WHERE COL_NO = " . $collab . " AND RAM_MOIS = " . $mois . "  AND RAM_ANNEE =" . $annee;
            $r2 = $GLOBALS['connexion']->query($q2)->fetch_assoc();
            // forfait journalier
            $q3 = "SELECT INT_FRAIS FROM INTERNE WHERE COL_NO = " . $collab;
            $r3 = $GLOBALS['connexion']->query($q3)->fetch_assoc();
            $comm = '<p>' . $r2['NBH'] . 'jrs x ' . $r3['INT_FRAIS'] . '&euro; = ' . number_format(($r2['NBH'] * $r3['INT_FRAIS']), 2) . '&euro; </p>';
            break;
        case "2" : 
            // Réels : lignes saisies
            $q2 = "SELECT * FROM LIGNE_FRAIS WHERE NOF_NO = '" . $r1['NOF_NO'] . "'";
            $r2 = $GLOBALS['connexion']->query($q2);
            if (mysqli_num_rows($r2) > 0) {
                while ($row = $r2->fetch_assoc()) {
    //                $ht = (($row['LIF_MONTANT'] - $row['LIF_TVA']) * 100) / 100;
    //                $comm = '<p>Le ' . $row['LIF_JOUR'] . ' ' . $row['LIF_OBJET'] . ' : ' . $ht . '+' . $row['LIF_TVA']
    //                    . '='  . $row['LIF_MONTANT'] . '&euro; </p>';
                    $comm .= '<p>Le ' . $row['LIF_JOUR'] . ' ' . $row['LIF_OBJET'] . ' : ' . number_format($row['LIF_MONTANT'], 2) . '&euro; </p>';
                }
            }
            break;
        case "3" : 
            // Grand déplacement : lignes saisies
            $q2 = "SELECT * FROM LIGNE_FRAIS WHERE NOF_NO = '" . $r1['NOF_NO'] . "'";
            $r2 = $GLOBALS['connexion']->query($q2);
            if (mysqli_num_rows($r2) > 0) {
                while ($row = $r2->fetch_assoc()) {
                    $comm .= '<p>Le ' . $row['LIF_JOUR'] . ' ' . $row['LIF_OBJET'] . ' : ' . $row['LIF_NBJ_W'] . ' jours ' . $row['LIF_DETAIL'] . ' : ' . number_format($row['LIF_MONTANT'], 2) . '&euro; </p>';
                }
            }
            break;
        case "4" : 
            // Kilométrique : lignes saisies
            $q2 = "SELECT * FROM LIGNE_FRAIS WHERE NOF_NO = '" . $r1['NOF_NO'] . "'";
            $r2 = $GLOBALS['connexion']->query($q2);
            if (mysqli_num_rows($r2) > 0) {
                while ($row = $r2->fetch_assoc()) {
                    $comm .= '<p>Le ' . $row['LIF_JOUR'] . ' <b>' . $row['LIF_CLIENT'] . '</b> ' . $row['LIF_VILLE'] . ' : ' . $row['LIF_NBJ_W'] . 'km x ' . $row['LIF_TAUX_KM'] . '&euro; = ' . number_format($row['LIF_TOTAL_KM'], 2) . '&euro; </p>';
                }
            }
            break;
    }

    //pour aller chercher le mail du collaborateur dans la base de données
    $rq_mail = "SELECT * FROM `COLLABORATEUR` WHERE `COL_NO`='" . $r1['COL_NO'] ."'";
    $res_mail = $GLOBALS['connexion']->query($rq_mail);
    //=======    
    $ligne = mysqli_fetch_assoc($res_mail);

    // To
    $to = $ligne['COL_EMAILAPSA'];

    // Subject
    $subject = 'Acceptation note de frais pour ' . $ligne['COL_CIVILITE'] . " " . $ligne['COL_PRENOM'] . " " . $ligne['COL_NOM'] . '.';

    // clé aléatoire de limite
    $boundary = md5(uniqid(microtime(), TRUE));

    // Headers
    $headers = 'From: NoReply Apsaroke <noreply@apsaroke.fr>' . "\r\n";
    $headers .= 'Mime-Version: 1.0' . "\r\n";
    $headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
    $headers .= "\r\n";

    // Message
    $msg = $subject . "\r\n\r\n";

    $libmois = nomMois($mois);

    // Message HTML
    $msg .= '--' . $boundary . "\r\n";
    $msg .= 'Content-type: text/html; charset=\"ISO-8859-1\"' . "\r\n\r\n";
    $msg .= '<html>
          <head>
           <title>Acceptation note de frais ';
    $msg .= $ligne['COL_NOM'];
    $msg .= '</title>
          </head>
          <body>
           <p>Votre note de frais <b>' . $id . '</b> a été prise en compte par le service RH pour <b>';
    $msg .= $libmois . ' ' . $annee . '</b><br/><br/></p>';
    $msg .= $comm;
    $msg .= '<br/><p>Merci de renvoyer le document signé pour le règlement de vos frais.';
    $msg .= '</p>
               <p>Vous pouvez consulter l\'état de vos frais sur l\'application <a href="http://chiricahuas.crows-it.com/">Apsaroke</a>.</p>
               <p>Cordialement</p>
          </body>
         </html>' . "\r\n";

    // Fin
    $msg .= '--' . $boundary . "\r\n";

    // Function mail()
    mail($to, $subject, $msg, $headers);
}

function envoimail_refuse($id, $mois, $annee){
    $query_valid = "UPDATE NOTE_FRAIS SET NOF_ETAT ='R' WHERE NOF_NSEQUENTIEL = '" . $id . "'";
    $GLOBALS['connexion']->query ($query_valid);

    $q1 = "SELECT * FROM NOTE_FRAIS WHERE NOF_NSEQUENTIEL = '" . $id . "'";
    $r1 = $GLOBALS['connexion']->query($q1)->fetch_assoc();

    $comm = '';
    switch ($r1['TYF_NO']){
        case "1" : 
            // Forfait : cumul des jours travaillés
            $collab = $r1['COL_NO'];
            $q2 = "SELECT SUM(RAM_NBH) AS NBH FROM RAM WHERE COL_NO = " . $collab . " AND RAM_MOIS = " . $mois . "  AND RAM_ANNEE =" . $annee;
            $r2 = $GLOBALS['connexion']->query($q2)->fetch_assoc();
            // forfait journalier
            $q3 = "SELECT INT_FRAIS FROM INTERNE WHERE COL_NO = " . $collab;
            $r3 = $GLOBALS['connexion']->query($q3)->fetch_assoc();
            $comm = '<p>' . $r2['NBH'] . 'jrs x ' . $r3['INT_FRAIS'] . '&euro; = ' . number_format(($r2['NBH'] * $r3['INT_FRAIS']), 2) . '&euro; </p>';
            break;
        case "2" : 
            // Réels : lignes saisies
            $q2 = "SELECT * FROM LIGNE_FRAIS WHERE NOF_NO = '" . $r1['NOF_NO'] . "'";
            $r2 = $GLOBALS['connexion']->query($q2);
            if (mysqli_num_rows($r2) > 0) {
                while ($row = $r2->fetch_assoc()) {
    //                $ht = (($row['LIF_MONTANT'] - $row['LIF_TVA']) * 100) / 100;
    //                $comm = '<p>Le ' . $row['LIF_JOUR'] . ' ' . $row['LIF_OBJET'] . ' : ' . $ht . '+' . $row['LIF_TVA']
    //                    . '='  . $row['LIF_MONTANT'] . '&euro; </p>';
                    $comm .= '<p>Le ' . $row['LIF_JOUR'] . ' ' . $row['LIF_OBJET'] . ' : ' . number_format($row['LIF_MONTANT'], 2) . '&euro; </p>';
                }
            }
            break;
        case "3" : 
            // Grand déplacement : lignes saisies
            $q2 = "SELECT * FROM LIGNE_FRAIS WHERE NOF_NO = '" . $r1['NOF_NO'] . "'";
            $r2 = $GLOBALS['connexion']->query($q2);
            if (mysqli_num_rows($r2) > 0) {
                while ($row = $r2->fetch_assoc()) {
                    $comm .= '<p>Le ' . $row['LIF_JOUR'] . ' ' . $row['LIF_OBJET'] . ' : ' . $row['LIF_NBJ_W'] . ' jours ' . $row['LIF_DETAIL'] . ' : ' . number_format($row['LIF_MONTANT'], 2) . '&euro; </p>';
                }
            }
            break;
        case "4" : 
            // Kilométrique : lignes saisies
            $q2 = "SELECT * FROM LIGNE_FRAIS WHERE NOF_NO = '" . $r1['NOF_NO'] . "'";
            $r2 = $GLOBALS['connexion']->query($q2);
            if (mysqli_num_rows($r2) > 0) {
                while ($row = $r2->fetch_assoc()) {
                    $comm .= '<p>Le ' . $row['LIF_JOUR'] . ' <b>' . $row['LIF_CLIENT'] . '</b> ' . $row['LIF_VILLE'] . ' : ' . $row['LIF_NBJ_W'] . 'km x ' . $row['LIF_TAUX_KM'] . '&euro; = ' . number_format($row['LIF_TOTAL_KM'], 2) . '&euro; </p>';
                }
            }
            break;
    }

    //pour aller chercher le mail du collaborateur dans la base de données
    $rq_mail = "SELECT * FROM `COLLABORATEUR` WHERE `COL_NO`='" . $r1['COL_NO'] ."'";
    $res_mail = $GLOBALS['connexion']->query($rq_mail);
    //=======    
    $ligne = mysqli_fetch_assoc($res_mail);

    // To
    //$to = $ligne['COL_EMAILAPSA'];
    //$to = "pam@apsaroke.fr";

    // Subject
    $subject = 'Acceptation note de frais pour ' . $ligne['COL_CIVILITE'] . " " . $ligne['COL_PRENOM'] . " " . $ligne['COL_NOM'] . '.';

    // clé aléatoire de limite
    $boundary = md5(uniqid(microtime(), TRUE));

    // Headers
    $headers = 'From: NoReply Apsaroke <noreply@apsaroke.fr>' . "\r\n";
    $headers .= 'Mime-Version: 1.0' . "\r\n";
    $headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
    $headers .= "\r\n";

    // Message
    $msg = $subject . "\r\n\r\n";

    //$libmois = nomMois($_POST['mois']);
    $libmois = nomMois($mois);

    // Message HTML
    $msg .= '--' . $boundary . "\r\n";
    $msg .= 'Content-type: text/html; charset=\"ISO-8859-1\"' . "\r\n\r\n";
    $msg .= '<html>
          <head>
           <title>Acceptation note de frais ';
    $msg .= $ligne['COL_NOM'];
    $msg .= '</title>
          </head>
          <body>
           <p>Votre note de frais <b>' . $id . '</b> pour <b>';
    $msg .= $libmois . ' ' . $annee . '</b> est sujette à modification<br/><br/></p>';
    $msg .= $comm;
    $msg .= '<br/><p>Vous serez contacté par le service RH pour régler ce problème.';
    $msg .= '</p>
               <p>Vous pouvez consulter l\'état de vos frais sur l\'application <a href="http://chiricahuas.crows-it.com/">Apsaroke</a>.</p>
               <p>Cordialement</p>
          </body>
         </html>' . "\r\n";

    // Fin
    $msg .= '--' . $boundary . "\r\n";

    // Function mail()
    mail($to, $subject, $msg, $headers);
}
