<?php
include_once 'inc/connection.php';
include_once 'inc/verif_session.php';

$anneeChoisi = $_POST['annee'];

$queryFacAnnee = 'SELECT SUM(FAC_TOTAL_HT) - SUM(FAC_MT_AVOIR) "FAC_TOTAL_HT", FAC_MOIS FROM FACTURE WHERE FAC_ANNEE=' . $anneeChoisi . ' AND FAC_MOIS < 9 OR FAC_ANNEE=' . ($anneeChoisi - 1). ' AND FAC_MOIS > 9 GROUP BY FAC_MOIS ORDER BY FAC_MOIS';
$factures = $GLOBALS['connexion']->query($queryFacAnnee);
$tabFac = array();
while ($facture = $factures->fetch_assoc()) {
    if ($facture['FAC_MOIS'] > 9) {
        //Facture entre Octobre et Décembre --> Appartient à l'année suivante
        $tabFac[$facture['FAC_MOIS']-9] = $facture['FAC_TOTAL_HT'];
    } else
        $tabFac[$facture['FAC_MOIS']+3] = $facture['FAC_TOTAL_HT'];
}

$queryAllFac = 'SELECT SUM(FAC_TOTAL_HT) - SUM(FAC_MT_AVOIR) "TOTAL_HT", FAC_ANNEE, FAC_MOIS FROM FACTURE GROUP BY FAC_ANNEE, FAC_MOIS ORDER BY FAC_ANNEE, FAC_MOIS';
$stmtAllFac = $GLOBALS['connexion']->query($queryAllFac);


$tabToutFac = array();
while ($facture = $stmtAllFac->fetch_assoc()) {
    if ($facture['FAC_MOIS'] > 9) {
        //Facture entre Octobre et Décembre --> Appartient à l'année suivante
        $tabToutFac[$facture['FAC_ANNEE'] + 1][$facture['FAC_MOIS']] = $facture;
    } else
        $tabToutFac[$facture['FAC_ANNEE']][$facture['FAC_MOIS']] = $facture;
}

$anneeDepart = $GLOBALS['connexion']->query('SELECT MIN(FAC_ANNEE) "ANNEE_DEPART" FROM FACTURE')->fetch_assoc();

$tabCA = array();
for ($i = $anneeDepart['ANNEE_DEPART'], $c = count($tabToutFac) + $anneeDepart['ANNEE_DEPART']; $i < $c; $i++) {
    $total = 0;
    for ($j = 0; $j < 13; $j++) {
        if (isset($tabToutFac[$i][$j]))
            $total += $tabToutFac[$i][$j]['TOTAL_HT'];
    }
    $tabCA[$i] = $total;
}

$tabMoisCA = array('1' => 'Octobre - ' . ($anneeChoisi - 1),
    '2' => 'Novembre - ' . ($anneeChoisi - 1),
    '3' => 'D&eacute;cembre - ' . ($anneeChoisi - 1),
    '4' => 'Janvier - ' . $anneeChoisi,
    '5' => 'F&eacute;vrier - ' . $anneeChoisi,
    '6' => 'Mars - ' . $anneeChoisi,
    '7' => 'Avril - ' . $anneeChoisi,
    '8' => 'Mai - ' . $anneeChoisi,
    '9' => 'Juin - ' . $anneeChoisi,
    '10' => 'Juillet - ' . $anneeChoisi,
    '11' => 'Ao&ucirc;t - ' . $anneeChoisi,
    '12' => 'Septembre - ' . $anneeChoisi,
);

$queryPar = 'SELECT * FROM PARAMETRE WHERE PAR_LIBELLE="MONTANT_ANNEE_OBJECTIF"';
$stmtPar = $GLOBALS['connexion']->query($queryPar)->fetch_assoc();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <?php include 'head.php'; ?>
        <title>Etat du chiffre d'affares</title>
    </head>
    <body>
        <?php
//Barre de menu
        include ("menu/menu_global.php");

        echo '<table id="objectif" border="1" class="table-condensed" width="45%" align=center>'
        . '<th width="50%">Objectif ' . ($anneeChoisi - 1) . ' - ' . $anneeChoisi . '</th><th id="objText" width="50%">';
        $temp = empty($stmtPar) ? 0 : number_format($stmtPar['PAR_VALEUR'], 2, ',', ' ');
        echo $temp.' ¤</th></table>';
//Tableau 1 : Etat du CA de l'année choisi
        $table = '<table id="EtatCA" border="1" class="table-condensed" width="90%" align=center>
            <tr>
                <th colspan="13">Etat du chiffre d\'affaires - Exercice ' . $anneeChoisi . '</th>
            </tr>';
        echo '</br>';
        $i = 1;
        $mois = '';
        $valeur = '';
        
        while ($i < 13) {
            $mois .= '<th id="mois' . $i . '">' . $tabMoisCA[$i] . '</th    >';
            if (isset($tabFac[$i]))
                $valeur .= '<td id="caMois' . $i . '">' . number_format($tabFac[$i], 2, ',', ' ') . ' ¤</td>';
            else
                $valeur .= '<td id="caMois' . $i . '">0 ¤</td>';
            $i++;
        }
        $table .= '<tr>' . $mois . '</tr><tr>' . $valeur . '</tr><tr style="visibility:hidden"><td></td></tr>';
        $table .= '<th colspan=5>CA Exercice ' . $anneeChoisi . ' en ¤ :</th><th id="totalAnnee' . $anneeChoisi . '"></th>';
        $table .= '<th colspan=5>CA Exercice ' . $anneeChoisi . ' en FF :</th><th id="totalAnnee' . $anneeChoisi . 'FF"></th>';

        $table .='</tr></table>';
        echo $table;


//Tableau 2 : Récapitulatif des CA par années autant que le permet la BDD
        $tableRecap = '<table id="EtatAllCA" border="1" class="table-condensed" width="90%" align=center>
            <tr>
                <th colspan="13">Etat du chiffre d\'affaires par année</th>
            </tr><tr>';
        $i = $anneeDepart['ANNEE_DEPART'];
        $ligne = '';
        
        while (isset($tabCA[$i])) {
            $tableRecap .= '<th>Exercice ' . $i . '</th>';
            $ligne .= '<td id="Exercice' . $i . '">' . number_format($tabCA[$i], 2, ',', ' ') . ' ¤</td>';
            $i++;
        }
        $tableRecap .= '<th>Résultat N-1</th><th style="width :150px;">% de réalisation de l\'objectif ' . $anneeChoisi . '</th>'
                . '<tr>' . $ligne . ''
                . '<td style="font-weight: bold;" id="MTRN-1"></td>'
                . '<td style="font-weight: bolde;" id="pourcentage"></td>'
                . '</tr></table>';
        echo '</br>';
        echo $tableRecap;
        echo '</br>';
        echo '</br>';
        
        echo '<div class="container-fluid" style="width:95%;" align="center">
                <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="stats" class="active"><a href="#graphMois" aria-controls="mois" role="tab" data-toggle="tab">Histogramme du CA par mois</a></li>
                    <li role="stats"><a href="#graphAnnee" aria-controls="annee" role="tab" data-toggle="tab">Histogramme du CA par année</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="graphMois">';
        
                $tableGraphMois = '<div id="containerCaMois"></div>
                    <table id="datatableM" hidden>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Chiffre d\'affaire mensuel</th>
                            </tr>
                        </thead>
                        <tbody>';

                        $i = 0;
                        //On boucle autant de fois qu'il y a de mois dans une année
                        while ($i < 13) {
                            $tableGraphMois .= '<tr><th>' . $tabMoisCA[$i] . '</th><td>';
                            $temp = isset($tabFac[$i]) ? $tabFac[$i] : 0;
                            $tableGraphMois .= $temp . '</td></tr>';
                            $i++;
                        }
                $tableGraphMois .= '</tbody></table></div>';
                echo $tableGraphMois;

                echo '<div role="tabpanel" class="tab-pane" id="graphAnnee">';
                $tableGraphAnnee = '<div id="containerCaAnnee"></div>
                    <table id="datatableA" hidden>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Chiffre d\'affaire par année</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                        $i = $anneeDepart['ANNEE_DEPART'];

                        while(isset($tabCA[$i])) {
                            $tableGraphAnnee .= '<tr><th>Exercice ' . $i . '</th><td>';
                            $tableGraphAnnee .= $tabCA[$i] . '</td></tr>';
                            $i++;
                        }
                $tableGraphAnnee .= '</tbody></table>';
                echo $tableGraphAnnee;
                echo '</div>'
                . ' </div>'
                . '</div>'
            . '</div>';
        ?>
        <script type='text/javascript' src="js/highcharts.js"></script>
        <script type='text/javascript' src="js/modules/data.js"></script>
        <script type='text/javascript' src="js/modules/exporting.js"></script>
        <script type='text/javascript'>
            var anneeChoisi = <?php echo $anneeChoisi; ?>;
            var anneeDepart = <?php echo $anneeDepart['ANNEE_DEPART']; ?>;
            var derniereAnnee = <?php echo $anneeDepart['ANNEE_DEPART'] ?>;
            
            load();
            function load(){
                i = 1;
                caTotal = 0;
                while (document.getElementById('caMois' + i) !== null) {
                    caTotal += parseFloat(document.getElementById('caMois' + i).innerHTML.replace(' ',''));
                    i++;
                }
                document.getElementById('totalAnnee' + anneeChoisi).innerHTML = caTotal.toLocaleString() + ' ¤';
                document.getElementById('totalAnnee' + anneeChoisi + 'FF').innerHTML = (caTotal * 6.55967).toLocaleString() + ' F';

                while (document.getElementById('Exercice' + derniereAnnee) !== null) {
                    derniereAnnee++;
                }
                derniereAnnee--;
                        
                var mtDif = parseFloat(document.getElementById('Exercice' + derniereAnnee).innerHTML.replace(' ','')) - parseFloat(document.getElementById('Exercice' + (derniereAnnee - 1)).innerHTML.replace(' ',''));
                mtDif = format(mtDif,2,' ');
                document.getElementById('MTRN-1').innerHTML = mtDif + ' ¤';
                
                calcul(parseFloat(document.getElementById('objText').innerHTML.replace(' ','')));
            }
            
            function calcul(nb){
                document.getElementById('pourcentage').innerHTML = parseFloat(document.getElementById('Exercice' + (derniereAnnee-1)).innerHTML.replace(' ','')) / nb * 100;
                temp = parseFloat(document.getElementById('pourcentage').innerHTML);
                document.getElementById('pourcentage').innerHTML = temp.toLocaleString() + ' %';
            }
            
            $("#objText").dblclick(function (e) {
                var target = getEventTarget(e);
                var nb = '';
                do {
                    nb = parseFloat(prompt("Veuillez saisir un montant."));
                } while (isNaN(nb) ||nb < 0)
                sauvegarde(nb);
                calcul(nb);
                target.innerHTML = nb.toLocaleString() + ' ¤';
            });

            function getEventTarget(e) {
                e = e || window.event;
                return e.target || e.srcElement;
            }
            
            function sauvegarde(nb) {
            var formData = {MONTANT_ANNEE_OBJECTIF: nb};
            $.ajax({
                url: 'insertUpdateMtCA.php',
                type: 'POST',
                async: true,
                data: formData
            }).success(alert('Modification(s) sauvegardée(s)'));
        }
        
            $(function () {
               $('#containerCaMois').highcharts({
                   data: {
                       table: 'datatableM'
                   },
                   chart: {
                       type: 'column',
                       width: 1200,
                   },
                   title: {
                       text: 'CA mensuel en ¤ exercice ' + anneeChoisi
                   },
                   yAxis: {
                       allowDecimals: true,
                       title: {
                           text: ''
                       }
                   },
                   tooltip: {
                       formatter: function () {
                           return this.point.y.toLocaleString() + ' ¤';
                       }
                   }
               });
           });
            
           $(function () {
               $('#containerCaAnnee').highcharts({
                   data: {
                       table: 'datatableA'
                   },
                   chart: {
                       type: 'column',
                       width: 1200,
                   },
                   title: {
                       text: 'CA en ¤ depuis ' +  anneeDepart
                   },
                   yAxis: {
                       allowDecimals: true,
                       title: {
                           text: ''
                       }
                   },
                   tooltip: {
                       formatter: function () {
                           return this.point.y.toLocaleString() + ' ¤';
                       }
                   }
               });
           });
           
           //Source : http://www.toutjavascript.com
           function format(valeur,decimal,separateur) {
            // formate un chiffre avec 'decimal' chiffres après la virgule et un separateur
                    var deci=Math.round( Math.pow(10,decimal)*(Math.abs(valeur)-Math.floor(Math.abs(valeur)))) ; 
                    var val=Math.floor(Math.abs(valeur));
                    if ((decimal==0)||(deci==Math.pow(10,decimal))) {val=Math.floor(Math.abs(valeur)); deci=0;}
                    var val_format=val+"";
                    var nb=val_format.length;
                    for (var i=1;i<4;i++) {
                            if (val>=Math.pow(10,(3*i))) {
                                    val_format=val_format.substring(0,nb-(3*i))+separateur+val_format.substring(nb-(3*i));
                            }
                    }
                    if (decimal>0) {
                            var decim=""; 
                            for (var j=0;j<(decimal-deci.toString().length);j++) {decim+="0";}
                            deci=decim+deci.toString();
                            val_format=val_format+"."+deci;
                    }
                    if (parseFloat(valeur)<0) {val_format="-"+val_format;}
                    return val_format;
            }
        </script>
    </body>
</html>