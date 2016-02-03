<?php
include_once 'inc/connection.php';
include_once 'inc/verif_session.php';

$annee = $_POST['annee'];

$queryRam = 'SELECT count(RAM_JOUR) "NB_JOUR_TRAVAILLE", R.COL_NO, COL_NOM '
        . 'FROM RAM R JOIN COLLABORATEUR C ON R.COL_NO=C.COL_NO WHERE RAM_ANNEE=2014 AND RAM_MOIS < 9 AND RAM_VALIDATION = 1 '
        . 'OR RAM_ANNEE=2013 AND RAM_MOIS > 9 AND RAM_VALIDATION = 1 GROUP BY R.COL_NO, COL_NOM';

$RAMs = $GLOBALS['connexion']->query($queryRam);
$tabRam = array();
while($RAM = $RAMs->fetch_assoc()){
    $tabRam[] = $RAM;
}

$queryConge = 'select C.COL_NO, count(ABS_JOUR) "NB_JOUR" '
            . 'from ABSENCE A JOIN COLLABORATEUR C ON A.COL_NO = C.COL_NO '
            . 'GROUP BY C.COL_NO, COL_NOM '
            . 'ORDER BY COL_NO';

$conges = $GLOBALS['connexion']->query($queryConge);

$tabConge = array();
while($conge = $conges->fetch_assoc()){
    $tabConge[] = $conge;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Test</title>
    </head>
    <body>
        <?php 
include ("menu/menu_global.php");?>
        <div id="container">
            <table id="datatable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Jours travaillés</th>
                        <th>Jours d'absences</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while(isset($tabRam[$i]))
                    {
                        echo '<tr><th>'.$tabRam[$i]['COL_NOM'].'</th><td>'.$tabRam[$i]['NB_JOUR_TRAVAILLE'].'</td><td>'.$tabConge[$i]['NB_JOUR'].'</td></tr>';
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script src="js/highcharts.js"></script>
        <script src="js/modules/data.js"></script>
        <script src="js/modules/exporting.js"></script>
        <script type='text/javascript'>
        $(function () {
            $('#container').highcharts({
                data: {
                    table: 'datatable'
                },
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Nombre de jours travaillés par collaborateurs sur l\'exercice '+<?php echo $_POST['annee']?>
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Nombre de jours'
                    }
                },
                tooltip: {
                    formatter: function () {
                        return this.point.y + ' jours - Mr/Mme ' + this.point.name.charAt(0).toUpperCase() + this.point.name.substring(1).toLowerCase();
                    }
                }
            });
        });

        </script>
    </body>
</html>