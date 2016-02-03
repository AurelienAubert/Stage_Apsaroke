<?php
    include_once 'calendrier/fonction_nbjoursMois.php'; 
    include_once 'calendrier/jours_feries.php'; 
    include_once 'calendrier/fonction_nomMois.php';
    include_once 'inc/connection.php';

    /**
     * Génère le calendrier
     * 
     * @param array $table
     * @return string
     */
    function generer_tableau($table) {
        $retour = "<table border='1'>";
        $retour .= "<thead>
                <td>L</td>
                <td>M</td>
                <td>M</td>
                <td>J</td>
                <td>V</td>
                <td>S</td>
                <td>D</td>
            </thead>
            <tbody>";
        foreach ($table as $semaine)
        {
            $retour .= "<tr>";
            for ($i = 1; $i <= 7; $i++)
            {
                if (isset($semaine[$i]))
                {
                    $retour .= "<td class='" . $semaine[$i]['classe'] . "'><div>".$semaine[$i]['valeur']."</div></td>";
                }
                else
                {
                    $retour .= "<td></td>";
                }
            }
            $retour .= "</tr>";
        }
        $retour .= "</tbody>
            </table>";
        return $retour;
    }
    
    /**
     * Ajoute les styles pour un type d'absence en jour plein et demi-journée
     * @param string $nom
     * @param string $couleur
     * @return string
     */
    function ecrire_style($nom, $couleur) {
        $police = '';
        if (strpos($nom, 'valid') !== false) {
            $police = 'font-weight: bold;';
        }
        
        //Ajout attribut "!important" afin d'afficher les couleurs lors de l'impression
        $retour = '.' . $nom . " {
            background-color: " . $couleur . " !important;"
            . $police . ";}";
        return $retour . '.' . $nom . "-b {"
            . $police  . "
            background: " . $couleur . "; /* Old browsers */
            background: -moz-linear-gradient(-45deg,  " . $couleur . " 0%, " . $couleur . " 50%, #ffffff 50%); /* FF3.6+ */
            background: -webkit-linear-gradient(-45deg,  " . $couleur . " 0%," . $couleur . " 50%,#ffffff 50%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(-45deg,  " . $couleur . " 0%," . $couleur . " 50%,#ffffff 50%); /* Opera 11.10+ */
            background: -ms-linear-gradient(-45deg,  " . $couleur . " 0%," . $couleur . " 50%,#ffffff 50%); /* IE10+ */
            background: linear-gradient(135deg,  " . $couleur . " 0%," . $couleur . " 50%,#ffffff 50%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $couleur . "', endColorstr='#ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
        }";
    }

    /**
     * Ajoute une ligne d'option dans le select pour les types d'absence
     * 
     * @param string $nom
     * @param string $texte
     * @return string
     */
    function option($nom, $texte) {
        return '<option value="' . $nom . '">' . $texte . '</option>';
    }

    /**
     * Créé une ligne pour le tableau de légende
     * 
     * @param string $nom
     * @param string $class
     * @return string
     */
    function ajout_row_conge($nom, $class) {
        return '<tr class="type"><td>' . $nom . '</td>'
                . '<td class="' . $class . '-valid"></td>'
                . '<td class="' . $class . '"></td></tr>';
    }

    /**
     * Génère le calendrier, le select, la légende et les styles pour les congés
     * 
     * @param int $id : l'ID du collaborateur dont on désire les congés
     * @return array : contient chaque morceau généré : style, select, recap et calendrier
     */
    function generer_calendrier($id) {
        $retour = array();

        /*
         * préparation de la liste des types d'absence, des styles et du select et du tableau
         */
        $type_absences = array();
        $result_type_absence = $GLOBALS['connexion']->query("SELECT * FROM TYPE_ABSENCE");

        $retour['select'] = 'Choix du type de congés<br /><select name="type">';
        $retour['style'] = '';
        $retour['recap'] ='<table border="1"><thead><tr><th>Type</th><th style="width:70px;">Validé</th><th style="width:70px;">Demandé</th></tr></thead><tbody>';
        while ($row = $result_type_absence->fetch_assoc()) {
            $nom = str_replace(' ', '', $row['TYA_LIBELLE']);
            $type_absences[$row['TYA_NO']] = array(
                'nom'       => $nom,
                'valide'    => $row['TYA_COULEUR'],
                'demande'   => $row['TYA_COULEUR_DEM']
            );
            $retour['style'] .= ecrire_style($nom, $row['TYA_COULEUR_DEM']);
            $retour['style'] .= ecrire_style($nom . '-valid', $row['TYA_COULEUR']);
            $retour['select'] .= option($nom, $row['TYA_LIBELLE']);
            $retour['recap'] .= ajout_row_conge($row['TYA_LIBELLE'], $nom);
        }
        $retour['select'] .= '</select>';
        $retour['recap'] .= '</tbody></table>';


        /*
         * récupération de la liste des congés validés (ABS_VALIDATION = 1) ou non (ABS_VALIDATION = 0 ou 2) pour la période donnée
         */
        
        $conges = array();
        $query = "SELECT TYA_NO, ABS_JOUR, ABS_NBH, ABS_VALIDATION FROM ABSENCE WHERE ABS_MOIS = '" . $_POST['mois'] . "' AND ABS_ANNEE = '" . $_POST['annee'] . "' AND COL_NO = '" . $id . "' AND ABS_ETAT != 3";
        $result = $GLOBALS['connexion']->query($query);
        while ($row = $result->fetch_assoc()) {
            $conges[$row['ABS_JOUR']] = $type_absences[$row['TYA_NO']]['nom'];
            $conges[$row['ABS_JOUR']] .= ($row['ABS_VALIDATION'] == 1) ? '-valid' : '';
            $conges[$row['ABS_JOUR']] .= ($row['ABS_NBH'] == 1) ? '' : '-b';
        }

        /*
         * préparation du tableau des jours
         */
        $semaine = 1; //nombre de semaine dans un mois
        $table = array();  //table du mois
        $nbjoursMois = nbjoursMois ($_POST['mois'], $_POST['annee']); // connaitre le nb de jours dans le mois
        $jour = jour_semaine ($_POST['mois'], 1, $_POST['annee']); // connaitre le jour du premier du mois
        $feries = getFeriesAnnee($_POST['annee']);
        
        for ($i=1 ; $i<=$nbjoursMois ; $i++) {
            switch (true) {
                case (isset($conges[$i])):
                    $table[$semaine][$jour]['classe'] = $conges[$i];
                    $table[$semaine][$jour]['classe'] .= (strpos($conges[$i], 'valid') === false) ? ' clickable' : '';
                    break;
                case in_array(mktime(0, 0, 0, $_POST['mois'], $i, $_POST['annee']), $feries):
                    $table[$semaine][$jour]['classe'] = 'feries';
                    break;
                case ($jour==6):
                    $table[$semaine][$jour]['classe'] = 'samedi';
                    break;
                case ($jour==7):
                    $table[$semaine][$jour]['classe'] = 'dimanche';
                    break;
                default:
                    $table[$semaine][$jour]['classe'] = 'clickable';
                    break;
            }
            $table[$semaine][$jour]['valeur'] = $i;
            if ($jour == 7) {
                $jour = 0;
                $semaine++;
            }
            $jour++;
        }

        $retour['calendrier'] = generer_tableau($table);
        return $retour;
    }
?>
