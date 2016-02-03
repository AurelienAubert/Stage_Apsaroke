<?php require ("inc/verif_session.php") ?>
<?php include "inc/connection.php"; ?>
<?php include "calendrier/fonction_nomMois.php"; ?>
<?php include 'inc/verif_parametres.php'; ?>

<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php' ?>
        <title>Suivi des missions</title>      
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
    <?php
        $GLOBALS['titre_page'] = '<div class="adm">Suivi des missions ' . nomMois ($_POST['mois']) . ' ' . $_POST['annee'] . '</div>';
        $GLOBALS['retour_page'] = 'chx_date.php?type=suivi';
        include ("menu/menu_global.php");
    ?>
    <div class="container-fluid ">
        <form action="suivi_mission.php" method="POST">
        <?php
            $maj = $_POST['projet'];
            if (isset($maj)) {
                foreach ($_POST['projet'] as $cle => $valeurs) {
                    $tcle = explode('_', $cle);
                    $mis_no = $tcle[1];
                    $query = "UPDATE MISSION SET MIS_COMMENTAIRE='" . $valeurs['MIS_COMMENTAIRE'] . "', MIS_ARCHIVE='" . $valeurs['MIS_ARCHIVE'] . "' WHERE MIS_NO=" . $mis_no . ")";
                    $GLOBALS['connexion']->query($query);
                    $query = "UPDATE PROJET SET PRO_ARCHIVE=" . $valeurs['PRO_ARCHIVE'] . ", PRO_DTCLOTURE='" . $valeurs['PRO_DTCLOTURE'] . "', PRO_DTFINPREVUE='" . $valeurs['PRO_DTFINPREVUE'] . "' WHERE PRO_NO IN (SELECT PRO_NO FROM MISSION WHERE MIS_NO=" . $mis_no .")";
                    $GLOBALS['connexion']->query($query);
                }
            }
            $mois = mktime( 0, 0, 0, $_POST['mois'], 1, $_POST['annee'] );
            $date_max = $_POST['annee'] . "-" . $_POST['mois'] . "-" . date("t", $mois);
            //DATE_FORMAT ne marche pas pour les dates. Les remplace par -0001.... ?
            $query = "SELECT P.PRO_NO, M.MIS_NO, C.COL_NO, C.COL_NOM, C.COL_PRENOM, CLI.CLI_NOM, P.PRO_NOM, M.MIS_COMMENTAIRE, M.MIS_DTDEBUT, M.MIS_DTFIN, P.PRO_ARCHIVE, P.PRO_DTCLOTURE, M.MIS_TJM, M.MIS_PA, COM.COL_MNEMONIC, DATEDIFF(M.MIS_DTFIN, NOW()) AS DATES
                FROM COLLABORATEUR C, CLIENT CLI, PROJET P LEFT JOIN COLLABORATEUR COM ON P.PRO_SUIVIPAR=COM.COL_NO, RAM R, INTERNE I, MISSION M 
                WHERE I.COL_NO = C.COL_NO 
                AND C.COL_NO = R.COL_NO 
                AND R.PRO_NO = P.PRO_NO 
                AND P.PRO_NO = M.PRO_NO AND M.MIS_DTDEBUT < '" . $date_max . "' 
                AND P.CLI_NO = CLI.CLI_NO 
                AND R.RAM_MOIS = '" . $_POST['mois'] . "' 
                AND R.RAM_ANNEE = '" . $_POST['annee'] . "'
                AND P.PRO_ARCHIVE='0'
                GROUP BY R.COL_NO, R.PRO_NO 
                ORDER BY C.COL_NOM, C.COL_PRENOM";

            $result = $GLOBALS['connexion']->query($query);

            $tableau = array();
            while($row = $result->fetch_assoc()) {
                $cle = $row['COL_NO'] . "_" . $row['MIS_NO'];
                //if (!isset($tableau[$row['COL_NO']])) {
                    $tableau[$cle] = array(
                        'ID'                =>  $cle,
                        'COL_NOM'           => $row['COL_NOM'] . '<br />' . $row['COL_PRENOM'],
                        'CLI_NOM'           => $row['CLI_NOM'],
                        'PRO_NOM'           => $row['PRO_NOM'],
                        'MIS_COMMENTAIRE'   => '<textarea name="projet[' . $cle . '][MIS_COMMENTAIRE]">' . $row['MIS_COMMENTAIRE'] . '</textarea>',
                        'MIS_DTDEBUT'       => $row['MIS_DTDEBUT'],
                        'MIS_DTFIN'         => '<input class="span2" type="date" placeholder="AAAA-MM-JJ" pattern="^\d{4}\-\d{2}\-\d{2}$" required name="projet[' . $cle . '][MIS_DTFIN]" value="' . $row['MIS_DTFIN'] . '"></input>',
                        'PRO_ARCHIVE'       => 'Oui <input type="radio" name="projet[' . $cle. '][PRO_ARCHIVE]" value="1" ' . ($row['PRO_ARCHIVE']==1?'checked':'') . '><br />Non <input type="radio" name="projet[' . $cle . '][PRO_ARCHIVE]" value="0" ' . ($row['PRO_ARCHIVE']==0?'checked':'') . '><input type="hidden" name="projet[' . $cle . '][PRO_DTCLOTURE]" placeholder="AAAA-MM-JJ" value="' . $row['PRO_DTCLOTURE'] . '" pattern="^\d{4}\-\d{2}\-\d{2}$"></input>',
                        'MIS_TJM'           => $row['MIS_TJM'],
                        'MIS_PA'            => $row['MIS_PA'],
                        'ETAT_MISSION'      => (($row['DATES']) < 30 ? 'PREVOIR RENOUVELLEMENT' : 'OK'),
                        'PRO_SUIVIPAR'      => $row['COL_MNEMONIC'],
                        //'taille'            => 1,
                    );
            }
            $table = '<table border="1" class="table-bordered table-condensed" align="center" width="95%">
                <thead>
                <tr>
                <th>Collaborateur interne</th>
                <th>Client</th>
                <th>Projet</th>
                <th>Commentaire</th>
                <th>Date de début</th>
                <th>Date de fin prévue</th>
                <th>Cloture</th>
                <th>TJM</th>
                <th>PA</th>
                <th>Etat de la mission</th>
                <th>Suivi par</th>
                </tr>
                </thead>
                <tbody style="">';
                   
            echo $table;

            foreach ($tableau as $ligne) {
                echo '<tr id='.$ligne['ID'].'>';
                echo '<td>' . $ligne['COL_NOM'] . '</td>';
                echo '<td>' . $ligne['CLI_NOM'] . '</td>';
                echo '<td>' . $ligne['PRO_NOM'] . '</td>';
                echo '<td>' . $ligne['MIS_COMMENTAIRE'] . '</td>';
                echo '<td>' . $ligne['MIS_DTDEBUT'] . '</td>';
                echo '<td>' . $ligne['MIS_DTFIN'] . '</td>';
                echo '<td>' . $ligne['PRO_ARCHIVE'] . '</td>';
                echo '<td>' . $ligne['MIS_TJM'] . '</td>';
                echo '<td>' . $ligne['MIS_PA'] . '</td>';
                echo '<td>' . $ligne['ETAT_MISSION'] . '</td>';
                echo '<td>' . $ligne['PRO_SUIVIPAR'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table><br><br><br><br>';

                    
            $query_ext = "SELECT P.PRO_NO, M.MIS_NO, C.COL_NO, C.COL_NOM, C.COL_PRENOM, CLI.CLI_NOM, P.PRO_NOM, M.MIS_COMMENTAIRE, M.MIS_DTDEBUT, M.MIS_DTFIN, P.PRO_ARCHIVE, P.PRO_DTCLOTURE, M.MIS_TJM, M.MIS_PA, COM.COL_MNEMONIC, DATEDIFF(M.MIS_DTFIN, NOW()) AS DATES
                    FROM COLLABORATEUR C, CLIENT CLI, PROJET P LEFT JOIN COLLABORATEUR COM ON P.PRO_SUIVIPAR=COM.COL_NO, RAM R, EXTERNE E, MISSION M
                    WHERE E.COL_NO = C.COL_NO 
                    AND C.COL_NO = R.COL_NO 
                    AND R.PRO_NO = P.PRO_NO 
                    AND P.PRO_NO = M.PRO_NO AND M.MIS_DTDEBUT < '" . $date_max . "' 
                    AND P.CLI_NO = CLI.CLI_NO 
                    AND R.RAM_MOIS = '" . $_POST['mois'] . "' 
                    AND R.RAM_ANNEE = '" . $_POST['annee'] . "'
                    AND P.PRO_ARCHIVE='0'
                    GROUP BY R.COL_NO, R.PRO_NO 
                    ORDER BY C.COL_NOM, C.COL_PRENOM";

            $result_ext = $GLOBALS['connexion']->query($query_ext);

            $tableau_ext = array();
            while ($row_ext = $result_ext->fetch_assoc()) {
                $cle = $row_ext['COL_NO'] . "_" . $row_ext['MIS_NO'];
                    $tableau_ext[$cle] = array(
                        'ID'                =>  $cle,
                        'COL_NOM'           => $row_ext['COL_NOM'] . '<br />' . $row_ext['COL_PRENOM'],
                        'CLI_NOM'           => $row_ext['CLI_NOM'],
                        'PRO_NOM'           => $row_ext['PRO_NOM'],
                        'MIS_COMMENTAIRE'   => '<textarea name="projet[' . $cle . '][MIS_COMMENTAIRE]">' . $row_ext['MIS_COMMENTAIRE'] . '</textarea>',
                        'MIS_DTDEBUT'       => $row_ext['MIS_DTDEBUT'],
                        'MIS_DTFIN'         => '<input class="span2" type="date" placeholder="AAAA-MM-JJ" pattern="^\d{4}\-\d{2}\-\d{2}$" required name="projet[' . $cle . '][MIS_DTFIN]" value="' . $row_ext['MIS_DTFIN'] . '"></input>',
                        'PRO_ARCHIVE'       => 'Oui <input type="radio" name="projet[' . $cle . '][PRO_ARCHIVE]" value="1" ' . ($row_ext['PRO_ARCHIVE'] == 1 ? 'checked' : '') . '><br />Non <input type="radio" name="projet[' . $cle . '][PRO_ARCHIVE]" value="0" ' . ($row_ext['PRO_ARCHIVE'] == 0 ? 'checked' : '') . '><input type="hidden" name="projet[' . $cle . '][PRO_DTCLOTURE]" placeholder="AAAA-MM-JJ" value="' . $row_ext['PRO_DTCLOTURE'] . '" pattern="^\d{4}\-\d{2}\-\d{2}$"></input>',
                        'MIS_TJM'           => $row_ext['MIS_TJM'],
                        'MIS_PA'            => $row_ext['MIS_PA'],
                        'ETAT_MISSION'      => (($row_ext['DATES']) < 30 ? 'PREVOIR RENOUVELLEMENT' : 'OK'),
                        'PRO_SUIVIPAR'      => $row_ext['COL_MNEMONIC'],
                    );
            }

            $table = str_replace('interne', 'externe', $table);
            echo $table;

            foreach ($tableau_ext as $ligne) {
                echo '<tr id='.$ligne['ID'].'>';
                echo '<td>' . $ligne['COL_NOM'] . '</td>';
                echo '<td>' . $ligne['CLI_NOM'] . '</td>';
                echo '<td>' . $ligne['PRO_NOM'] . '</td>';
                echo '<td>' . $ligne['MIS_COMMENTAIRE'] . '</td>';
                echo '<td>' . $ligne['MIS_DTDEBUT'] . '</td>';
                echo '<td>' . $ligne['MIS_DTFIN'] . '</td>';
                echo '<td>' . $ligne['PRO_ARCHIVE'] . '</td>';
                echo '<td>' . $ligne['MIS_TJM'] . '</td>';
                echo '<td>' . $ligne['MIS_PA'] . '</td>';
                echo '<td>' . $ligne['ETAT_MISSION'] . '</td>';
                echo '<td>' . $ligne['PRO_SUIVIPAR'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table><br><br>';

            ?>
            <input type="hidden" name="mois" value="<?php echo $_POST['mois']; ?>"></input>
            <input type="hidden" name="annee" value="<?php echo$_POST['annee'];?>"></input>
            <div class="offset5 span7">
                <button class="btn btn-primary not_printed" type="submit">Enregistrer le suivi des missions <i class="icon-ok"></i> </button>
                <button class="btn btn-primary not_printed" type="button" onclick="javascript:ImprimeSuivi();"> Imprimer </button>
            </div>
        </form>

        <div id="date-dialog" title="Date">
            <input type="hidden" id="pro_no"></input>
            <input type="date" id="dtcloture" placeholder="AAAA-MM-JJ" pattern="^\d{4}\-\d{2}\-\d{2}$" required></input>
            <div style="display:none;">Date incorrecte</div>
        </div>
        <br /><br />
        <script>
            function ImprimeSuivi(){
                $('.').css('margin-top', '0px');
                window.print();
            }
            $(document).ready(function() {
                $("tr:odd").each(function() {
                    $(this).children().css("background-color", "#bbbbff");
                });

                $('#date-dialog').dialog({
                    autoOpen: false,
                    height: 200,
                    width: 250,
                    modal: true,
                    buttons: {
                        "Sélectionner": function() {
                            $(this).children('div').hide();
                            if (/^\d{4}\-\d{2}\-\d{2}$/.test($(this).children('#dtcloture').val())) {
                                $('[name="projet[' + $('#pro_no').val() + '][PRO_DTCLOTURE]"]').val($('#dtcloture').val());
                                $(this).dialog('close');
                                $(this).children().val('');
                            }
                            else {
                                $(this).children('div').css({
                                    color: '#610000',
                                    background: '#F0C8C8',
                                    border: '2px solid #610000',
                                    'text-align':'center'
                                }).slideDown();
                            }
                        },
                        "Annuler": function() {
                            $(this).children('div').hide();
                            $(this).children().val('');
                            $(this).dialog('close');
                        }
                    }
                });

                $('[name*="PRO_ARCHIVE"]').change(function() {
                    if ($(this).val() == 1) {
                        var num = $(this).attr('name').match(/\d+/)[0];
                        $('#date-dialog').dialog('open').find('#pro_no').val(num);
                    }
                });
            });
        </script>
    </div>
</body>
</html>