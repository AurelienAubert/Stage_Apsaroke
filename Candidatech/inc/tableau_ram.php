<?php
/**
 * créé un tableau de RAM prêt à afficher
 * contenu de $_POST nécessaire :
 * col_id       l'id du collaborateur
 * mois         le mois du RAM
 * annee        l'année du RAM
 * nb_client    le nombre de client pour le RAM
 * clientX      le NO du client n°X (une variable par client)
 * projetX      le NO du projet n°X
 * mode         le type d'utilisation du RAM :
 *                  "voir"      => impossible de modifier le tableau
 *                  "modif"     => on peut modifier le tableau
 *                  "imprimer"  => mise en page spéciale pour l'impression
 */
include_once 'inc/connection.php';
include_once 'inc/zone_signature.php';
include_once 'calendrier/fonction_nbjoursMois.php';
include_once 'calendrier/fonction_mois.php';
include_once 'calendrier/fonction_nomMois.php';
include_once 'calendrier/fonction_dimanche_samedi.php';
include_once 'calendrier/jours_feries.php';
include_once 'inc/verif_parametres.php';

$query_type_absence = "SELECT TYA_NO, TYA_LIBELLE, TYA_COULEUR FROM TYPE_ABSENCE";
$results = $GLOBALS['connexion']->query ($query_type_absence);
$GLOBALS['type_absence'] = array();
while ($row = $results->fetch_assoc ())
{
    $GLOBALS['type_absence'][$row['TYA_NO']] = array(
        'nom' => $row['TYA_LIBELLE'],
        'couleur' => $row['TYA_COULEUR'],
    );
};

/**
 * génère le RAM
 * 
 */
function generer_ram ()
{
    $nbJours = nbjoursMois ($_POST['mois'], $_POST['annee']);
    $tab_jour = array();
    $tab_client = array();
    $tab_conge = array();
    $tab_projet = array();


    $query_projet = "SELECT DISTINCT P.PRO_NO, P.PRO_NOM, C.CLI_NO, C.CLI_NOM FROM CLIENT C, PROJET P WHERE P.CLI_NO=C.CLI_NO";
    $result_projet = $GLOBALS['connexion']->query ($query_projet);
    while ($row = $result_projet->fetch_assoc ())
    {
        $tab_projet[$row['PRO_NO']] = array(
            'PRO_NOM' => $row['PRO_NOM'],
            'CLI_NO' => $row['CLI_NO'],
            'CLI_NOM' => $row['CLI_NOM']
        );
    }

    for ($i = 1; $i <= $_POST['nb_client']; $i++)
    {
        $tab_client[$_POST['projet' . $i]] = array();
    }

    for ($i = 1; $i <= $nbJours; $i++)
    {
        $tab_jour[$i] = array();
        $tab_conge[$i] = array();
        foreach ($tab_client as &$client)
        {
            $client[$i] = array();
        }
    }

    /*
     * à ce point, on a la liste des jours dans tab_jour,
     * dans tab_client on a une ligne par client et la liste des jours pour chacun
     * et pour $tab_conge on a la liste des jours
     */

    ajout_classe ($tab_jour);
    remplir_ram ($tab_jour, $tab_client, $tab_conge);


    $html = '<form action="ram_envoyes.php" method="post">' . ram_to_html ($tab_jour, $tab_client, $tab_conge, $tab_projet);
//echo "MODE ".$_POST['mode'];
    switch ($_POST['mode'])
    {
        case 'creer':
            $html .= ajout_comm_apsa ();
            break;
        case 'modif':
            $html .= ajout_tab_conge () . ajout_comm_apsa () . ajout_comm_cli ($tab_client, $tab_projet);
            break;
        case 'voir':
            $html .= ajout_tab_conge () . ajout_comm_apsa () . ajout_comm_cli ($tab_client, $tab_projet);
            break;
        case 'imprimer':
        case 'imprimerAdm':
            $html = select_cli ($tab_client, $tab_projet) . $html;
            $html .= ajout_tab_recap ($tab_jour) . ajout_comm_cli ($tab_client, $tab_projet);
            $html .= ajout_signature ();
            break;
    }
    return $html;
}

/**
 * ajoute les classes de week-end, jours fériés etc...
 * @param array $tab_jour
 */
function ajout_classe (&$tab_jour)
{
    $time = mktime (0, 0, 0, $_POST['mois'], 1, $_POST['annee']);
    $feries = getFeries ($time);

    foreach ($tab_jour as $jour => &$jour_val)
    {
        $time = mktime (0, 0, 0, $_POST['mois'], $jour, $_POST['annee']);
        if (check_jour ($_POST['mois'], $jour, $_POST['annee']) == 0)
        {
            $jour_val['classe'] = 'dimanche';
        }
        elseif (check_jour ($_POST['mois'], $jour, $_POST['annee']) == 6)
        {
            $jour_val['classe'] = 'samedi';
        }
        elseif (in_array ($time, $feries))
        {
            $jour_val['classe'] = 'feries';
        }
    }
    unset ($jour_val);
}

/**
 * rempli les valeurs du RAM d'après la base
 * 
 * @param array $tab_jour
 * @param array $tab_client
 * @param array $tab_conge
 */
function remplir_ram (&$tab_jour, &$tab_client, &$tab_conge)
{
    ajout_conge ($tab_client, $tab_conge);
    ajout_ram_existant ($tab_jour, $tab_client, $tab_conge);
}

/**
 * ajoute les congés et met les valeurs par défaut pour le premier client
 * 
 * @param array $tab_client
 * @param array $tab_conge
 */
function ajout_conge (&$tab_client, &$tab_conge)
{
    $conges = "SELECT ABS_JOUR, ABS_NBH, TYA_NO FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND (ABS_VALIDATION=1 OR ABS_VALIDATION=2)";
    $result = $GLOBALS['connexion']->query ($conges);
    if (mysqli_num_rows ($result) >= 1)
    {
        while ($row = $result->fetch_assoc ())
        {
            $tab_conge[$row['ABS_JOUR']] = array(
                'valeur' => $row['ABS_NBH'],
                'classe' => $row['TYA_NO']
            );
            if (isset ($_POST['projet1']))
            {
                /*
                 * si on n'a pas de client précisé, c'est qu'on veut voir un ram qui existe déjà
                 * donc pas besoin de mettre des valeurs par défaut
                 */
                switch ($row['ABS_NBH'])
                {
                    case 0.5:
                        $tab_client[$_POST['projet1']][$row['ABS_JOUR']]['valeur'] = 0.5;
                        break;
                    case 1:
                        $tab_client[$_POST['projet1']][$row['ABS_JOUR']]['valeur'] = 0;
                        break;
                    case 0:
                        $tab_client[$_POST['projet1']][$row['ABS_JOUR']]['valeur'] = 1;
                        break;
                }
            }
        }
    }
}

/**
 * donne les valeurs des jours pour chaque client d'après les enregistrements dans la base
 * 
 * @param array $tab_jour
 * @param array $tab_client
 * @param array $tab_conge
 */
function ajout_ram_existant (&$tab_jour, &$tab_client, &$tab_conge)
{
    $liste_ram = "SELECT RAM_JOUR, PRO_NO, RAM_NBH, RAM_VALIDATION FROM RAM WHERE COL_NO = '" . $_POST['col_id'] . "' AND RAM_ANNEE = '" . $_POST['annee'] . "' AND RAM_MOIS = '" . $_POST['mois'] . "'";
    $result = $GLOBALS['connexion']->query ($liste_ram);
    if (mysqli_num_rows ($result) >= 1)
    {
        while ($row = $result->fetch_assoc ())
        {
            if ($row['RAM_VALIDATION'] == 1 && $_POST['mode'] == 'modif')
            {
                $_POST['mode'] = 'voir';
            }
            $tab_client[$row['PRO_NO']][$row['RAM_JOUR']]['valeur'] = $row['RAM_NBH'];
        }
    }
    elseif (isset ($_POST['projet1']))
    {
        //on complète à 1 les congés
        foreach ($tab_jour as $jour => $temp)
        {
            if (!isset ($temp['classe']))
            {
                if (isset ($tab_conge[$jour]['valeur']))
                {
                    $tab_client[$_POST['projet1']][$jour]['valeur'] = 1 - $tab_conge[$jour]['valeur'];
                }
                else
                {
                    $tab_client[$_POST['projet1']][$jour]['valeur'] = 1;
                }
            }
        }
    }
}

/**
 * prend les tableaux complets en paramètre et génère le code html qui correspond
 * 
 * @param array $tab_jour
 * @param array $tab_client
 * @param array $tab_conge
 * @param array $tab_projet
 * @return string
 */
function ram_to_html (&$tab_jour, &$tab_client, &$tab_conge, &$tab_projet)
{
    $tab_nom_jour = array('D', 'L', 'M', 'M', 'J', 'V', 'S');
    $ligne_nom_jour = '<table border="1" class="table table-bordered table-condensed"><thead style="background-color:lightgrey;"><tr ><th class="not_printed"></th><th class="not_printed"></th>';
    $ligne_jour = '<tr><th class="not_printed" style="min-width:125px">Client</th><th class="not_printed" style="min-width:125px">Projet</th>';
    $ligne_conge = '<tr class="not_printed"><td>Absences</td><td></td>';
    $ligne_client = array();

    /*
     * si on n'a qu'un client, on passe en readonly
     * par contre, du moment qu'on n'est pas en modification, on force à disabled
     */
    $disabled = $_POST['nb_client'] == 1 ? 'readonly' : '';
    $disabled = $_POST['mode'] == 'modif' || $_POST['mode'] == 'creer' ? $disabled : 'disabled';

    $i = 1;

    foreach ($tab_client as $nom => $client)
    {
        $ligne_client[$nom] = '<tr class="' . $nom . '">
                <td class="not_printed">' . $tab_projet[$nom]['CLI_NOM'] . '<input type="hidden" name="client' . $i . '" value = "' . $tab_projet[$nom]['CLI_NO'] . '"/></td>
                <td class="not_printed">' . $tab_projet[$nom]['PRO_NOM'] . '<input type="hidden" name="projet' . $i . '" value = "' . $nom . '"/></td>';
        $i++;
    }

    foreach ($tab_jour as $jour => $ligne)
    {
        if (isset ($ligne['classe']))
        {
            $classe = ' class="' . $ligne['classe'] . '" ';
        }
        else
        {
            $classe = '';
        }
        $ligne_nom_jour .= '<th' . $classe . '>' . $tab_nom_jour[check_jour ($_POST['mois'], $jour, $_POST['annee'])] . '</th>';
        $ligne_jour .= '<th' . $classe . '>' . $jour . '</th>';
        $ligne_conge .= '<td' . $classe . '>' . select ('c', $jour, $tab_conge[$jour], 'disabled') . '</td>';
        foreach ($tab_client as $nom => $client)
        {
            $ligne_client[$nom] .= '<td' . $classe . '>' . select ($nom, $jour, isset ($client[$jour]) ? $client[$jour] : array(), $disabled) . '</td>';
        }
    }
    $retour = $ligne_nom_jour . '</tr>' . $ligne_jour . '</tr></thead><tbody>';
    foreach ($ligne_client as $client)
    {
        $retour .= $client . '</tr>';
    }
    return $retour . $ligne_conge . '</tr></tbody></table>';
}

/**
 * génère les balises option pour les select
 * 
 * @param string    $nom
 * @param int       $valeur
 * @param bool      $selected
 * @return string
 */
function option ($nom, $valeur, $selected)
{
    $selected = $selected ? ' selected="selected"' : '';
    if ($valeur == 1)
    {
        (int) $valeur = 1;
    }
    $suffixe = array(
        '0' => '0',
        '0.5' => 'b',
        '1' => '1'
    );
    return '<option ' . $nom . $suffixe[$valeur] . '" value="' . $valeur . '" ' . $selected . '>' . $valeur . '</option>';
}

/**
 * génère le code HTML d'un select entouré de sa div pour le tableau
 * 
 * @param string    $nom        le name à donner au select
 * @param int       $jour       le numéro du jour dans le mois
 * @param array     $tab        la case du tableau PHP contenant la valeur par défaut et les classes et type pour le select (peut être vide)
 * @param string    $disabled   autorise ou non la modification du select par l'utilisateur
 * @return string
 * 
 * si $tab est vide, la valeur par défaut donnée est 0
 */
function select ($nom, $jour, $tab, $disabled = '')
{
    if (empty ($tab))
    {
        $tab = array('valeur' => 0);
    }

    $nom_select = 'name="' . $nom . '-' . $jour . '" ';
    $id_select = 'id="' . $nom . '-' . $jour . '" ';

    $classe = isset ($tab['classe']) ? 'class="' . $GLOBALS['type_absence'][$tab['classe']]['nom'] . '" ' : '';

    $style = isset ($tab['classe']) ? ' background-color:' . $GLOBALS['type_absence'][$tab['classe']]['couleur'] : '';

    $disabled = !empty ($disabled) ? $disabled . '="' . $disabled . '" ' : '';

    $retour = '<div class="select_div">';
    $retour .= '<select style="width:75px;' . $style . '" ' . $nom_select . $id_select . $disabled . $classe . ' onchange="javascript:changeConge(' . $jour . ', this.value);">';

    $nom_option = 'name="' . $nom . '-' . $jour . '-';

    if (!empty ($disabled))
    {
        $options = option ($nom_option, $tab['valeur'], true);
    }
    else
    {
        $options = '';
        for ($i = 0; $i <= 1; $i+=0.5)
        {
            $options .= option ($nom_option, $i, $tab['valeur'] == $i ? true : false);
        }
    }
    $retour .= $options . '</select></div>';
    return $retour;
}

/**
 * Ajoute le tableau de légende pour les congés
 * 
 * @return string
 */
function ajout_tab_conge ()
{
    $retour = '<div style="float:left;width:15%;margin:10px"><br><table border="1"><tbody>';
    foreach ($GLOBALS['type_absence'] as $type)
    {
        $retour .= '<tr><td style="background-color:' . $type['couleur'] . '; width:25px;"></td>';
        $retour .= '<td>' . $type['nom'] . '</td></tr>';
    }
    return $retour . '</tbody></table></div>';
}

/**
 * Ajoute une zone de commentaire pour Apsaroke
 * 
 * @return string
 */
function ajout_comm_apsa ()
{
    $query = "SELECT COM_TEXTE FROM COMMENTAIRE C, RAM R WHERE R.COM_NO_APSA = C.COM_NO AND RAM_ANNEE = '" . $_POST['annee'] . "' AND RAM_MOIS = '" . $_POST['mois'] . "' AND COL_NO = '" . $_POST['col_id'] . "'";
    $result = $GLOBALS['connexion']->query ($query);
    $commentaire = '';
    if (mysqli_num_rows ($result) >= 1)
    {
        $commentaire = $result->fetch_assoc()['COM_TEXTE'];
    }
    $retour = '<div style="float:left;width:40%;margin:10px">Message pour Apsaroke<br />';
    $retour .= '<textarea name="textarea_apsa" style="width:100%;height:100px;">' . $commentaire . '</textarea>';
    return $retour . '</div>';
}

/**
 * Ajoute une zone de commentaire pour chaque client
 * 
 * @param array $tab_client
 * @param array $tab_projet
 * @return string
 */
function ajout_comm_cli (&$tab_client, &$tab_projet)
{
    $query = "SELECT COM_TEXTE FROM COMMENTAIRE C, RAM R WHERE R.COM_NO_CLI = C.COM_NO AND RAM_ANNEE = '" . $_POST['annee'] . "' AND RAM_MOIS = '" . $_POST['mois'] . "' AND COL_NO = '" . $_POST['col_id'] . "'";

    $retour = '';
    foreach ($tab_client as $nom => $client)
    {
        $result = $GLOBALS['connexion']->query ($query . "AND PRO_NO = '" . $nom . "'");
        $commentaire = '';
        if (mysqli_num_rows ($result) >= 1)
        {
            $commentaire = $result->fetch_assoc ()['COM_TEXTE'];
        }
        $retour .= '<div name="libcomcli" class="' . $nom . '" style="float:left;width:40%;margin:10px">Commentaire client pour ' . $tab_projet[$nom]['CLI_NOM'] . ' (' . $tab_projet[$nom]['PRO_NOM'] . ')<br />';
        $retour .= '<textarea name="comcli_' . $nom . '" style="width:100%;height:100px;">' . $commentaire . '</textarea></div>';
    }
    return $retour;
}

/**
 * affiche un select permettant de sélectionner un client 
 * 
 * @param array $tab_client
 * @param array $tab_projet
 * @return string
 */
function select_cli (&$tab_client, &$tab_projet)
{
    $retour = '<span class="not_printed">Sélection du client<br /><select name="client">';
    foreach ($tab_client as $nom => $client)
    {
        $retour .= '<option value="' . $nom . '">' . $tab_projet[$nom]['CLI_NOM'] . ' : ' . $tab_projet[$nom]['PRO_NOM'] . '</option>';
    }
    return $retour . '</select></span>';
}

/**
 * ajoute le tableau récapitulatif des jours travaillés ou non
 * 
 * @param array $tab_jour
 * @return type
 */
function ajout_tab_recap (&$tab_jour)
{
    $query_travaille = "SELECT SUM(RAM_NBH) as travaille FROM RAM WHERE COL_NO = '" . $_POST['col_id'] . "' AND RAM_ANNEE = '" . $_POST['annee'] . "' AND RAM_MOIS = '" . $_POST['mois'] . "'";
    $query_absence = "SELECT SUM(ABS_NBH) as absence FROM ABSENCE WHERE COL_NO = '" . $_POST['col_id'] . "' AND ABS_ANNEE= '" . $_POST['annee'] . "' AND ABS_MOIS = '" . $_POST['mois'] . "' AND (ABS_VALIDATION=1 OR ABS_VALIDATION=2)";

    $query_speciaux = $query_travaille . ' AND RAM_JOUR IN (';
    foreach ($tab_jour as $num => $jour)
    {
        if (isset ($jour['classe']))
        {
            $query_speciaux .= $num . ', ';
        }
    }
    $query_speciaux = substr ($query_speciaux, 0, -2) . ')';

    $nombre_travaille = $GLOBALS['connexion']->query ($query_travaille)->fetch_assoc ()['travaille'];
    $nombre_absence = $GLOBALS['connexion']->query ($query_absence)->fetch_assoc ()['absence'];
    $nombre_speciaux = $GLOBALS['connexion']->query ($query_speciaux)->fetch_assoc ()['travaille'];
    $retour = '<div style="float:left;width:20%;margin:10px"><br><table border="1"><tbody>';
    $retour .= '<tr><td>Nombre de jours travaillés : <span id="nbJoursTravailles">' . (is_null ($nombre_travaille) ? 0 : $nombre_travaille) . '</span></td></tr>';
    $retour .= '<tr><td>dont week-end et jour fériés : <span id="nbJoursTravaillesWE">' . (is_null ($nombre_speciaux) ? 0 : $nombre_speciaux) . '</span></td></tr>';
    $retour .= '<tr><td>Nombre de jours d\'absence : <span id="nbJoursAbsence">' . (is_null ($nombre_absence) ? 0 : $nombre_absence) . '</span></td></tr>';
    $retour .= '<tr><td><span id="heureParJour">1 journée = 7 h 24 minutes</span></td></tr>';
    return $retour . '</tbody></table></div>';
}

?>
