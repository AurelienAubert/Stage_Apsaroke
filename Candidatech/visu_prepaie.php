<?php
require "inc/verif_session.php";
include 'calendrier/fonction_mois.php';
include ('calendrier/fonction_nbjoursMois.php');
include ('calendrier/jours_feries.php');
include ('calendrier/fonction_dimanche_samedi.php');
include ('calendrier/fonction_nomMois.php');
include ('inc/connection.php');

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">";

/* VISU_PREPAIE
 * 1    |   CREPU   |   20/06/2014  |   Correction du bug 
 *                                      "les jours pris n'apparaissent plus 
 *                                      dans la fiche de prepaie
   2    |   CREPU   |   20/06/2014  |   Documentation du code

*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <title>Pre paie</title>
        <?php include "head.php"; ?>
        <script type="text/javascript" src="js.js"></script>
		<style>
            td span
            {
                display: none;
                font-size: 10px;
            }
            td:active span
            {
                font-size: 10px;
                display: inline;
                position: absolute;
                text-decoration: none;
                background-color: #ffffff;
                border-radius: 6px;
            }
        </style>
    </head>

    <body>
        <!-- Barre de menu-->
        <?php
        $rq_parametre = "SELECT PAR_VALEUR FROM PARAMETRE WHERE PAR_LIBELLE = 'mois_prime';";
        $res_parametre = $GLOBALS['connexion']->query($rq_parametre);
        $row = mysqli_fetch_assoc($res_parametre);
        $moisprime = $row['PAR_VALEUR'];
        
        $mois = $_POST['mois'];
        $annee = $_POST['annee'];
        $nomMois = nomMois($mois);

        $GLOBALS['retour_page'] = 'chx_date.php?type=visu_prepaie';
        $GLOBALS['titre_page'] = '<div class="adm">Pré-Paie de ' . $nomMois . ' ' . $annee.'</div>';

        
        include ("menu/menu_global.php");
        ?> 
        <!-- Affiche Bonjour Prénom Nom de la personne en loguée + date du jour-->                                                     
        <?php
        $date_depart = strtotime($annee . "-" . $mois . "-01");
        $date_arrive = strtotime($annee . "-" . $mois . "-31");

        $samedi = 0;
        $dimanche = 0;
        $ferie1 = 0;
        $tab_we = array();

        $nbjoursMois = nbjoursMois($mois, $annee); //connaitre le nb de jours dans le mois
        $jour = jour_semaine($mois, 1, $annee);
        $feries = getFeries($date_depart, $date_arrive);

        for ($i = 1; $i <= $nbjoursMois; $i++) {
            switch (true) {
                case in_array(mktime(0, 0, 0, $mois, $i, $annee), $feries):
                    $ferie1++;
                    break;
                case ($jour == 6):
                    $samedi++;
                    array_push($tab_we, $i);
                    break;
                case ($jour == 7):
                    $dimanche++;
                    array_push($tab_we, $i);
                    break;
            }
            if ($jour == 7) {
                $jour = 0;
            }
            $jour++;
        }

        $jourouvre = $nbjoursMois - $ferie1 - $dimanche - $samedi;
        ?>
        <div id = div1 style = 'margin-left:2em; text-align: center;' class=''>
            <?php
            echo '<br>';
            echo '<b>';
            echo 'Pré-Paie - ';
            echo $nomMois;
            echo ' ';
            echo $annee;
            echo '</b>';
            echo '<br>';
            echo 'Jours ouvrés : ' . $jourouvre . '.';
            echo '<br>';
            echo '<br>';
            ?>

            <table border='1' class='table-bordered' align='center'>
                <tr style='font-size: 11px;'>
                    <td COLSPAN='3'></td>
                    <th COLSPAN='5' BGCOLOR='#bbbbff'>Dates</th>
                    <td COLSPAN='2'></td>
                    <?php
                    if ($mois == $moisprime) {
                        ?><th COLSPAN='8' BGCOLOR='#bbbbff'>En &euro;</th><?php
                    } else {
                        ?><th COLSPAN='7' BGCOLOR='#bbbbff'>En &euro;</th><?php
                        }
                        ?>
                    <td></td>
                </tr>
                <tr>
                    <th>Collaborateurs</th>
                    <th>JW</th>               
                    <th>Nb ABS</th>
                    <th WIDTH='85px'>CP</th>
                    <th WIDTH='85px'>RTT</th>
                    <th WIDTH='85px'>Maladies</th>
                    <th WIDTH='85px'>Sans solde</th>
                    <th WIDTH='85px'>WE<br/>travaillés</th>
                    <th>Nb TR</th>
                    <th>13ème<br/>mois</th>
                    <th>GSM</th>
                    <th>PEE</th>
                    <?php
                    if ($mois == $moisprime) {
                        ?>
                        <th>Primes<br/>Annuelles<br/>ancienneté</th>
                        <?php
                    }
                    ?>
                    <th>Frais<br/>journaliers</th>
                    <th>Montant frais<br/>à rembourser</th>
                    <th>Commissions</th>
                    <th>Primes</th>
                    <th>Avances<br/>sur<br/>salaire</th>
                    <th>Commentaires</th>
                </tr>

                <?php
                $recapconge = array();
                $recup_abs = array();
                $recup_we = array();
                $recup_spe = array();
                $idcol = NULL;
                $commentairegeneral = null;

                //Requête récupérant l'ensemble des collaborateur interne ayant un RAM pour une année et un mois donné
                $query_coll = "SELECT C.COL_NO, C.COL_NOM, C.COL_MNEMONIC, SUM(R.RAM_NBH) AS JW, I.INT_TR, I.INT_GSM, I.INT_PEE, I.INT_TREIZIEME , I.INT_PRIME_ANCI, I.INT_FRAIS
                              FROM COLLABORATEUR C, INTERNE I, RAM R
                              WHERE I.COL_NO = C.COL_NO
                              AND C.COL_NO = R.COL_NO
                              AND R.RAM_MOIS = '" . $mois . "'
                              AND R.RAM_ANNEE = '" . $annee . "'
                              GROUP BY C.COL_NO
                              ORDER BY C.COL_NOM, C.COL_PRENOM;";

                
                //Requ$ete récupérant l'ensemble des commentaire pour une année et un mois donné
                $query_com = "SELECT DISTINCT(C.COM_TEXTE)
                            FROM COMMENTAIRE C, SPECIF_MENSUELLE S
                            WHERE C.COM_NO = S.COM_NO
                            AND S.SPM_MOIS = '" . $mois . "'
                            AND S.SPM_ANNEE = '" . $annee . "';";

                //Requête récupérant le nombre d'heure d'absence de l'ensemble des collaborateur pour une année et un mois donné
                $rq_abs = "SELECT DISTINCT C.COL_NO, SUM(A.ABS_NBH) AS ABS
                             FROM ABSENCE A, COLLABORATEUR C
                             WHERE C.COL_NO = A.COL_NO 
                             AND ABS_MOIS = '" . $mois . "'
                             AND ABS_ANNEE = '" . $annee . "'";

                //Requête récupérant le nombre de jour et d'heure travaillées pour un collaborateur pour une année et un mois donné
                $rq_we = "SELECT C.COL_NO, R.RAM_JOUR, R.RAM_NBH
                         FROM RAM R, COLLABORATEUR C
                         WHERE C.COL_NO = R.COL_NO 
                         AND R.RAM_MOIS = '" . $mois . "'
                         AND R.RAM_ANNEE = '" . $annee . "'";

                $rq_spe = "SELECT S.SPM_NO, C.COL_NO, S.SPM_COMMISSION, S.SPM_A_DEDUIR, S.SPM_PRIME, S.SPM_COMMENTAIRE 
                          FROM SPECIF_MENSUELLE S, COLLABORATEUR C
                          WHERE C.COL_NO = S.COL_NO
                          AND S.SPM_MOIS = '" . $mois . "'
                          AND S.SPM_ANNEE = '" . $annee . "'";

                $result_comm = $GLOBALS['connexion']->query($query_com);
                $ligne_com = mysqli_fetch_assoc($result_comm);

                if (mysqli_num_rows($result_comm) == 1) {
                    $commentairegeneral = $ligne_com['COM_TEXTE'];
                }

                $result_coll = $GLOBALS['connexion']->query($query_coll);

                while ($ligne_coll = mysqli_fetch_assoc($result_coll)) {
                    $idcol = $ligne_coll['COL_NO'];
                    
                    $recapconge[$ligne_coll['COL_NO']][$ligne_coll['COL_NOM']] = array();
                    $recup_spe[$ligne_coll['COL_NO']] = array();

                    
                    $query_abs = $rq_abs . ' AND C.COL_NO=' . $idcol . ' GROUP BY C.COL_NO ORDER BY C.COL_NOM, C.COL_PRENOM;';
                    $query_we = $rq_we . ' AND C.COL_NO=' . $idcol . ' ORDER BY C.COL_NOM, C.COL_PRENOM, R.RAM_JOUR;';
                    $query_spe = $rq_spe . ' AND C.COL_NO=' . $idcol . ' ORDER BY C.COL_NOM, C.COL_PRENOM;';

                    
                    $result_abs = $GLOBALS['connexion']->query($query_abs);
                    $ligne_abs = mysqli_fetch_assoc($result_abs);

                    $result_we = $GLOBALS['connexion']->query($query_we);
                    $ligne_we = mysqli_fetch_assoc($result_we);

                    $result_spe = $GLOBALS['connexion']->query($query_spe);
                    $ligne_spe = mysqli_fetch_assoc($result_spe);

                    if ($ligne_abs['ABS'] == null) {
                        $ligne_abs['ABS'] = 0;
                    }

                    $recup_abs[$ligne_coll['COL_NO']] = array('nom' => $ligne_coll['COL_NOM'],
                        'mnemonic' => $ligne_coll['COL_MNEMONIC'],
                        'JW' => $ligne_coll['JW'],
                        'ABS' => $ligne_abs['ABS'],
                        'tr' => $ligne_coll['INT_TR'],
                        'gsm' => $ligne_coll['INT_GSM'],
                        'pee' => $ligne_coll['INT_PEE'],
                        'treizieme' => $ligne_coll['INT_TREIZIEME'],
                        'prime' => $ligne_coll['INT_PRIME_ANCI'],
                        'frais' => $ligne_coll['INT_FRAIS']
                    );

                    
                    $query_conge = "SELECT C.COL_NOM, A.COL_NO, A.TYA_NO, A.ABS_NBH, A.ABS_JOUR
                            FROM ABSENCE A, COLLABORATEUR C, INTERNE I
                            WHERE I.COL_NO = C.COL_NO 
                            AND C.COL_NO = A.COL_NO
                            AND A.ABS_MOIS = '" . $mois . "'
                            AND A.ABS_ANNEE = '" . $annee . "'
                            ORDER BY C.COL_NOM, C.COL_PRENOM, A.TYA_NO, A.ABS_JOUR;";
                    
                    $result_conge = $GLOBALS['connexion']->query($query_conge);
                    
                    //remplit le tableau avec les congés en fonction du numéro du col.
                    while ($ligne_conge = $result_conge->fetch_assoc())
                    {
                        if ($idcol == $ligne_conge['COL_NO']) {
                            $recapconge[$ligne_coll['COL_NO']][$ligne_coll['COL_NOM']][$ligne_conge['TYA_NO']][] = array($ligne_conge['ABS_JOUR'], $ligne_conge['ABS_NBH']);
                        }
                    }
                    
                    //remplit le tableau avec les samedi et dimanche travaillés en fonction du numéro col.
                    do {
                        foreach ($tab_we as $WE) {
                            if ($ligne_we['RAM_JOUR'] == $WE) {
                                $recup_we[$ligne_coll['COL_NO']][] = array($ligne_we['RAM_JOUR'], $ligne_we['RAM_NBH']);
                            }
                        }
                    } while ($ligne_we = mysqli_fetch_assoc($result_we));

                    if (isset($ligne_spe['SPM_NO'])) {
                        $recup_spe[$ligne_coll['COL_NO']] = array($ligne_spe['SPM_COMMISSION'], $ligne_spe['SPM_PRIME'], $ligne_spe['SPM_A_DEDUIR'], $ligne_spe['SPM_COMMENTAIRE']);
                    }
                }
                /*
                 * donc ici, il nous reste un tableau avec au moins un tableau vide par collab...
                 * si ce collab a des absences, on les conserves dans des sous tableaux...
                 * et sinon on conserve uniquement le nom
                 */

                //affiche le nom du col, les jours travaillés et le total des abs.
                foreach ($recup_abs as $id => $contenu) {
                    
                    ?>
                    <tr align="CENTER">
                        <td><?php echo '<b>' . $contenu['nom'] . '</b><br/>' . '<i>(' . $contenu['mnemonic'] . ')</i>'; ?><span>Collaborateur</span></td>
                        <td><?php echo $contenu['JW']; ?><span>Jours travaillés</span></td>
                        <td><?php echo $contenu['ABS']; ?><span>Jours absents</span></td>
                        <?php
                        $compCP = 0;
                        $compRTT = 0;
                        $compMal = 0;
                        $compSS = 0;
                        $compWE = 0;
                        $debutCP = true;
                        $debutRTT = true;
                        $debutMal = true;
                        $debutSS = true;
                        $debutWE = true;

                        
                        
                        //affiche les dates de congés en fonction de leur type.
                        foreach ($recapconge as $id1 => $element2) {
                            foreach ($element2 as $element) {
                                if ($id == $id1) {
                                    if (isset($element[2])) {
                                        ?>
                                        <td>
                                            <?php
                                            foreach ($element[2] as $element1) {
                                                if ($element1[0] != 0) {
                                                    if ($element1[1] == 0.5) {
                                                        if ($compCP == 3) {
                                                            echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i><br/>';
                                                            $compCP = 0;
                                                            $debutCP = true;
                                                        } else {
                                                            if ($debutCP == true) {
                                                                echo '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compCP++;
                                                                $debutCP = false;
                                                            } else {
                                                                echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compCP++;
                                                            }
                                                        }
                                                    } else {
                                                        if ($compCP == 3) {
                                                            echo '/' . $element1[0] . '<br/>';
                                                            $compCP = 0;
                                                            $debutCP = true;
                                                        } else {
                                                            if ($debutCP == true) {
                                                                echo $element1[0];
                                                                $compCP++;
                                                                $debutCP = false;
                                                            } else {
                                                                echo '/' . $element1[0];
                                                                $compCP++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <span>CP</span></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><?php echo ''; ?><span>CP</span></td>
                                        <?php
                                    }
                                    if (isset($element[3])) {
                                        ?>
                                        <td>
                                            <?php
                                            foreach ($element[3] as $element1) {
                                                if ($element1[0] != 0) {
                                                    if ($element1[1] == 0.5) {
                                                        if ($compRTT == 3) {
                                                            echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i><br/>';
                                                            $compRTT = 0;
                                                            $debutRTT = true;
                                                        } else {
                                                            if ($debutRTT == true) {
                                                                echo '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compRTT++;
                                                                $debutRTT = false;
                                                            } else {
                                                                echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compRTT++;
                                                            }
                                                        }
                                                    } else {
                                                        if ($compRTT == 3) {
                                                            echo '/' . $element1[0] . '<br/>';
                                                            $compRTT = 0;
                                                            $debutRTT = true;
                                                        } else {
                                                            if ($debutRTT == true) {
                                                                echo $element1[0];
                                                                $compRTT++;
                                                                $debutRTT = false;
                                                            } else {
                                                                echo '/' . $element1[0];
                                                                $compRTT++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <span>RTT</span></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><?php echo ''; ?><span>RTT</span></td>
                                        <?php
                                    }
                                    if (isset($element[5])) {
                                        ?>
                                        <td>
                                            <?php
                                            foreach ($element[5] as $element1) {
                                                if ($element1[0] != 0) {
                                                    if ($element1[1] == 0.5) {
                                                        if ($compMal == 3) {
                                                            echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i><br/>';
                                                            $compMal = 0;
                                                            $debutMal = true;
                                                        } else {
                                                            if ($debutMal == true) {
                                                                echo '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compMal++;
                                                                $debutMal = false;
                                                            } else {
                                                                echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compMal++;
                                                            }
                                                        }
                                                    } else {
                                                        if ($compMal == 3) {
                                                            echo '/' . $element1[0] . '<br/>';
                                                            $compMal = 0;
                                                            $debutMal = true;
                                                        } else {
                                                            if ($debutMal == true) {
                                                                echo $element1[0];
                                                                $compMal++;
                                                                $debutMal = false;
                                                            } else {
                                                                echo '/' . $element1[0];
                                                                $compMal++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <span>Maladie</span></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><?php echo ''; ?><span>Maladie</span></td>
                                        <?php
                                    }
                                    if (isset($element[6])) {
                                        ?>
                                        <td>
                                            <?php
                                            foreach ($element[6] as $element1) {
                                                if ($element1[0] != 0) {
                                                    if ($element1[1] == 0.5) {
                                                        if ($compSS == 3) {
                                                            echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i><br/>';
                                                            $compSS = 0;
                                                            $debutSS = true;
                                                        } else {
                                                            if ($debutSS == true) {
                                                                echo '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compSS++;
                                                                $debutSS = false;
                                                            } else {
                                                                echo '/' . '<b><i>' . $element1[0] . 'AM' . '</b></i>';
                                                                $compSS++;
                                                            }
                                                        }
                                                    } else {
                                                        if ($compSS == 3) {
                                                            echo '/' . $element1[0] . '<br/>';
                                                            $compSS = 0;
                                                            $debutSS = true;
                                                        } else {
                                                            if ($debutSS == true) {
                                                                echo $element1[0];
                                                                $compSS++;
                                                                $debutSS = false;
                                                            } else {
                                                                echo '/' . $element1[0];
                                                                $compSS++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <span>Sans Solde</span></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><?php echo ''; ?><span>Sans Solde</span></td>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?><td><?php
                            //affiche le détail des WE travaillés.
                            foreach ($recup_we as $id2 => $value) {
                                if ($id == $id2 && isset($value)) {
                                    foreach ($value as $jourwe) {
                                        if ($jourwe[1] == 0.5) {
                                            if ($compWE == 3) {
                                                echo '/' . '<b><i>' . $jourwe[0] . 'AM' . '</b></i><br/>';
                                                $compWE = 0;
                                                $debutWE = true;
                                            } else {
                                                if ($debutWE == true) {
                                                    echo '<b><i>' . $jourwe[0] . 'AM' . '</b></i>';
                                                    $compWE++;
                                                    $debutWE = false;
                                                } else {
                                                    echo '/' . '<b><i>' . $jourwe[0] . 'AM' . '</b></i>';
                                                    $compWE++;
                                                }
                                            }
                                        } else {
                                            if ($compWE == 3) {
                                                echo '/' . $jourwe[0] . '<br/>';
                                                $compWE = 0;
                                                $debutWE = true;
                                            } else {
                                                if ($debutWE == true) {
                                                    echo $jourwe[0];
                                                    $compWE++;
                                                    $debutWE = false;
                                                } else {
                                                    echo '/' . $jourwe[0];
                                                    $compWE++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            ?><span>WE<br/>travaillés</span></td><?php
                        //affiche le nombre de TR, le GSM, le PEE...
                        if ($contenu['tr'] == true) {
                            ?>
                            <td><?php echo floor($contenu['JW']); ?><span>Nombre<br/>TR</span></td>
                            <?php
                        } else {
                            ?>
                            <td><?php echo '0'; ?><span>Nombre<br/>TR</span></td>
                            <?php
                        }
                        if ($contenu['treizieme'] == true) {
                            ?>
                            <td><?php echo 'Oui'; ?><span>Treizième<br/>mois</span></td>
                            <?php
                        } else {
                            ?>
                            <td><?php echo 'Non'; ?><span>Treizième<br/>mois</span></td>
                            <?php
                        }
                        if (isset($contenu['gsm'])) {
                            ?>
                            <td><?php echo $contenu['gsm']; ?><span>GSM</span></td>
                            <?php
                        } 
                        if (isset($contenu['pee'])) {
                            ?>
                            <td><?php echo $contenu['pee']; ?><span>PEE</span></td>
                            <?php
                        }
                        if ($mois == $moisprime) {
                            if (isset($contenu['prime'])) {
                                ?>
                                <td><?php echo $contenu['prime']; ?><span>Prime</span></td>
                                <?php
                            }
                        }
                        if (isset($contenu['frais'])) {
                            ?>
                            <td><?php echo $contenu['frais']; ?><span>Frais</span></td>
                            <td><?php echo $contenu['frais'] * $contenu['JW']; ?><span>Montant frais<br />à rembourser</span></td>
                            <?php
                        } 
                        
                        foreach ($recup_spe as $id4 => $value3) {
                            if ($id == $id4) {
                                if (isset($value3[0]) && $value3[0] != 0) {
                                    ?>
                                    <td>
                                        <?php echo $value3[0]; ?>
                                    <span>Commission</span></td>
                                    <?php
                                } else {
                                    ?>
                                    <td><span>Commission</span></td>
                                    <?php
                                }
                                if (isset($value3[1]) && $value3[1] != 0) {
                                    ?>
                                    <td>
                                        <?php echo $value3[1]; ?>
                                    <span>Prime</span></td>
                                    <?php
                                } else {
                                    ?>
                                    <td><span>Prime</span></td>
                                    <?php
                                }
                                if (isset($value3[2]) && $value3[2] != 0) {
                                    ?>
                                    <td>
                                        <?php echo $value3[2]; ?>
                                    <span>Avance <br/> sur salaire</span></td>
                                    <?php
                                } else {
                                    ?>
                                    <td><span>Avance <br/> sur salaire</span></td>
                                    <?php
                                }
                                if (isset($value3[3]) && $value3[3] != null) {
                                    ?>
                                    <td>
                                        <?php echo $value3[3]; ?>
                                    <span>Commentaire</span></td>
                                    <?php
                                } else {
                                    ?>
                                    <td><span>Commentaire</span></td>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?> 
            </table>
            <br/>
            <br/>
            <b><u>Commentaire g&eacute;n&eacute;ral :</b></u>
            <br/>
            <br/>
            <?php echo $commentairegeneral; ?>
            <br/>
            <br/>
            <br/>
        </div>
        <form id="pdf" method="POST" action="pdf_PrePaie.php">
            <input name="mois" type="hidden" value="<?php echo $nomMois; ?>"></input>
            <input name="annee" type="hidden" value="<?php echo $annee; ?>"></input>
            <input name="joursouvres" type="hidden" value="<?php echo $jourouvre; ?>"></input>
            <input name="mois" type="hidden" value="<?php echo $nomMois; ?>"></input>
            <input name="tab_prepaie" type="hidden" value=""></input>
            <input name="titrepdf" type="hidden" value="Pr&eacute;paie"></input>
            <input name="titrejo" type="hidden" value="Jours ouvr&eacute;s"></input>
            <input name="titre" type="hidden" value="Commentaire g&eacute;n&eacute;ral :"></input>
            <input name="commentaire" type="hidden" value="<?php echo $commentairegeneral; ?>"></input>
        </form>
        <script type="text/javascript">
            $("tr:odd").each(function() {
                $(this).children().css("background-color", "#bbbbff");
            });

            $(document).ready(function() {
                $('#pdf').submit(function() {
                    var tableau = new Array();

                    $('table').find('tr').each(function() {
                        var ligne = new Array();
                        $(this).children().each(function() {
                            var texte = $(this).html().replace(/(<[/]?[bi]>)/g, '');
                            if (texte.indexOf('name') != -1) {
                                texte = $(this).children().val();
                            }
                            else {
                                texte = texte.replace(/<br>/g, "\n");
                            }
                            ligne.push({
                                height: $(this).height(),
                                width: $(this).width(),
                                texte: texte.replace(/^\s+/g, '').replace(/\s+$/g, ''),
                                couleur: $(this).css('background-color')
                            });
                        });
                        tableau.push(ligne);

                    });
                    $('input[name="tab_prepaie"]').val(JSON.stringify(tableau));
                });
            });
        </script>
    </body>
</html>