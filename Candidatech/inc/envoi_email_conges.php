<?php

//pour aller chercher $mail dans la base de donn�es
function mail_demande_conges($ligne_conges, $ligne_RTT, $ligne_maladies, $ligne_conges_ss, $ligne_conges_excep, $ligne_autres, $compteur_conges, $compteur_RTT, $compteur_maladies, $compteur_conges_ss, $compteur_conges_excep, $compteur_autres){
    $rq_mail = "SELECT `PAR_VALEUR` FROM `PARAMETRE` WHERE `PAR_LIBELLE`='email_destinataire';";
    $res_mail = $GLOBALS['connexion']->query($rq_mail);
    $ligne = mysqli_fetch_assoc($res_mail);

    $jour_conges = 'jours demand�s';
    $jour_RTT = 'jours demand�s';
    $jour_maladies = 'jours demand�s';
    $jour_conges_ss = 'jours demand�s';
    $jour_conges_exc = 'jours demand�s';
    $jour_autres = 'jours demand�s';

    if ($compteur_conges == 1) {
        $jour_conges = 'jour demand�';
    }

    if ($compteur_RTT == '1') {
        $jour_RTT = 'jour demand�';
    }

    if ($compteur_maladies == '1') {
        $jour_maladies = 'jour demand�';
    }

    if ($compteur_conges_ss == '1') {
        $jour_conges_ss = 'jour demand�';
    }

    if ($compteur_conges_excep == '1') {
        $jour_conges_exc = 'jour demand�';
    }

    if ($compteur_autres == '1') {
        $jour_autres = 'jour demand�';
    }

    $type_conges = null;
    $type_RTT = null;
    $type_maladies = null;
    $type_conges_sans_solde = null;
    $type_conges_exceptionnels = null;
    $type_autres = null;

    if (isset($ligne_conges['TYA_LIBELLE'])) {
        $type_conges = '<b>' . $ligne_conges['TYA_LIBELLE'] . '</b> : <b>' . $compteur_conges . '</b> ' . $jour_conges . '<br/>';
    } else {
        $type_conges = '';
    }

    if (isset($ligne_RTT['TYA_LIBELLE'])) {
        $type_RTT = '<b>' . $ligne_RTT['TYA_LIBELLE'] . '</b> : <b>' . $compteur_RTT . '</b> ' . $jour_RTT . '<br/>';
    } else {
        $type_RTT = '';
    }

    if (isset($ligne_maladies['TYA_LIBELLE'])) {
        $type_maladies = '<b>' . $ligne_maladies['TYA_LIBELLE'] . '</b> : <b>' . $compteur_maladies . '</b> ' . $jour_maladies . '<br/>';
    } else {
        $type_maladies = '';
    }
    if (isset($ligne_conges_ss['TYA_LIBELLE'])) {
        $type_conges_sans_solde = '<b>' . $ligne_conges_ss['TYA_LIBELLE'] . '</b> : <b>' . $compteur_conges_ss . '</b> ' . $jour_conges_ss . '<br/>';
    } else {
        $type_conges_sans_solde = '';
    }
    if (isset($ligne_conges_excep['TYA_LIBELLE'])) {
        $type_conges_exceptionnels = '<b>' . $ligne_conges_excep['TYA_LIBELLE'] . '</b> : <b>' . $compteur_conges_excep . '</b> ' . $jour_conges_exc . '<br/>';
    } else {
        $type_conges_exceptionnels = '';
    }
    if (isset($ligne_autres['TYA_LIBELLE'])) {
        $type_autres = '<b>' . $ligne_autres['TYA_LIBELLE'] . '</b> : <b>' . $compteur_autres . '</b> ' . $jour_autres . '';
    } else {
        $type_autres = '';
    }

    // To
    $to = $ligne['PAR_VALEUR'];

    // Subject
    $subject = 'Demande de conges ' . $_SESSION['nom'] . '.';

    // cl� al�atoire de limite
    $boundary = md5(uniqid(microtime(), TRUE));

    // Headers
    $headers = 'From: NoReply Apsaroke <noreply@apsaroke.fr>' . "\r\n";
    $headers .= 'Mime-Version: 1.0' . "\r\n";
    $headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
    $headers .= "\r\n";

    // Message
    $msg = 'Demande de conges ' . $_SESSION['nom'] . '.' . "\r\n\r\n";

    // Message HTML
    $msg .= '--' . $boundary . "\r\n";
    $msg .= 'Content-type: text/html; charset=\"ISO-8859-1\"' . "\r\n\r\n";
    $msg .= '
             <html>
          <head>
           <title>Demande de cong�s' . $_SESSION['nom'] . '</title>
          </head>
          <body>
           <p>Une demande de cong�s a �t� cr��e par <b>' . $_SESSION['nom'] . ' ' . $_SESSION['prenom'] . '</b> pour <b>' . nomMois($_POST['mois']) . ' ' . $_POST['annee'] . '</b><br/><br/>
            D�tails : <br/>
                ' . $type_conges . '
                ' . $type_RTT . '
                ' . $type_maladies . '
                ' . $type_conges_sans_solde . '
                ' . $type_conges_exceptionnels . '
                ' . $type_autres . '
               </p>
               <p>Vous pouvez modifier l\'�tat de la demande sur l\'application <a href="http://chiricahuas.crows-it.com/">Apsaroke</a>.</p>
               <p>Cordialement</p>
          </body>
         </html>' . "\r\n";

    // Fin
    $msg .= '--' . $boundary . "\r\n";

    // Function mail()
    mail($to, $subject, $msg, $headers);
}

function mail_valide_conges($tab_jour){
    //pour aller chercher $mail du collaborateur dans la base de donn�es
    $rq_mail = "SELECT * FROM `COLLABORATEUR` WHERE `COL_NO`='" . $_POST['col_id'] ."'";
    $res_mail = $GLOBALS['connexion']->query($rq_mail);
    //=======    
    $ligne = mysqli_fetch_assoc($res_mail);

    // To
    $to = $ligne['COL_EMAILAPSA'];

    // Subject
    $subject = 'Validation de conges pour ' . $ligne['COL_CIVILITE'] . ' ' . $ligne['COL_PRENOM'] . ' ' . $ligne['COL_NOM'] . '.';

    // cl� al�atoire de limite
    $boundary = md5(uniqid(microtime(), TRUE));

    // Headers
    $headers = 'From: NoReply Apsaroke <noreply@apsaroke.fr>' . "\r\n";
    $headers .= 'Mime-Version: 1.0' . "\r\n";
    $headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
    $headers .= "\r\n";

    // Message
    $msg = $subject . "\r\n\r\n";

    $libjours = implode(', ', $tab_jour);
    $comm = "";
    if ($_POST['commentaire' . $_POST['col_id']] != null) {
        $comm = $_POST['commentaire' . $_POST['col_id']] . '<br/>';
        $comm = stripslashes($comm);
        $reserve = '<b>sous r�serves</b> ';
    }
    $libmois = nomMois($_POST['mois']);

    // Message HTML
    $msg .= '--' . $boundary . "\r\n";
    $msg .= 'Content-type: text/html; charset=\"ISO-8859-1\"' . "\r\n\r\n";
    $msg .= '
             <html>
          <head>
           <title>Validation de conges ' . $ligne['COL_NOM'] . '</title>
          </head>
          <body>
           <p>Votre demande de cong�s a �t� valid�e '. $reserve . 'pour <b>' . $libmois . ' ' . $_POST['annee'] . '</b><br/><br/>
                ' . $comm . 
                'D�tails des jours : <br/>
                ' . $libjours . '
               </p>
               <p>Vous pouvez consulter l\'�tat de vos cong�s sur l\'application <a href="http://chiricahuas.crows-it.com/">Apsaroke</a>.</p>
               <p>Cordialement</p>
          </body>
         </html>' . "\r\n";

    // Fin
    $msg .= '--' . $boundary . "\r\n";

    // Function mail()
    mail($to, $subject, $msg, $headers);
}
function mail_refuse_conges($tab_jour){
    //pour aller chercher $mail du collaborateur dans la base de donn�es
    $rq_mail = "SELECT * FROM `COLLABORATEUR` WHERE `COL_NO`='" . $_POST['col_id'] ."'";
    $res_mail = $GLOBALS['connexion']->query($rq_mail);
    //=======    
    $ligne = mysqli_fetch_assoc($res_mail);

    // To
    $to = $ligne['COL_EMAILAPSA'];

    // Subject
    $subject = 'Refus de cong�s pour ' . $ligne['COL_CIVILITE'] . " " . $ligne['COL_PRENOM'] . " " . $ligne['COL_NOM'] . '.';

    // cl� al�atoire de limite
    $boundary = md5(uniqid(microtime(), TRUE));

    // Headers
    $headers = 'From: NoReply Apsaroke <noreply@apsaroke.fr>' . "\r\n";
    $headers .= 'Mime-Version: 1.0' . "\r\n";
    $headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
    $headers .= "\r\n";

    // Message
    $msg = $subject . "\r\n\r\n";

    $libjours = implode(', ', $tab_jour);
    $comm = "";
    if ($_POST['commentaire'] != null) {
        $comm = utf8_decode($_POST['commentaire']) . '<br/>';
        $comm = stripslashes($comm);
    }
    $libmois = nomMois($_POST['mois']);

    // Message HTML
    $msg .= '--' . $boundary . "\r\n";
    $msg .= 'Content-type: text/html; charset=\"ISO-8859-1\"' . "\r\n\r\n";
    $msg .= '<html>
          <head>
           <title>Refus de cong�s ';
    $msg .= $ligne['COL_NOM'];
    $msg .= '</title>
          </head>
          <body>
           <p>Votre demande de cong�s a �t� refus�e pour <b>';
    $msg .= $libmois . ' ' . $_POST['annee'] . '</b><br/><br/>';
    $msg .= $comm;
    $msg .= 'D�tails des jours : <br/>' . $libjours .
               '</p>
               <p>Vous pouvez consulter l\'�tat de vos cong�s sur l\'application <a href="http://chiricahuas.crows-it.com/">Apsaroke</a>.</p>
               <p>Cordialement</p>
          </body>
         </html>' . "\r\n";

    // Fin
    $msg .= '--' . $boundary . "\r\n";
//echo $msg;

    // Function mail()
    mail($to, $subject, $msg, $headers);
    //return $msg;
}
?>