<!DOCTYPE html >
<html>
<head>
    <link rel="stylesheet" href="../bootstrap/RGraph/demos/demos.css" type="text/css" media="screen" />
    
    <script src="../bootstrap/RGraph/libraries/RGraph.common.core.js" ></script>
    <script src="../bootstrap/RGraph/libraries/RGraph.common.csv.js" ></script>
    <script src="../bootstrap/RGraph/libraries/RGraph.bar.js" ></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!--[if lt IE 9]><script src="bootstrap/RGraph/excanvas/excanvas.js"></script><![endif]-->
    
    <title>A Bar chart using the CSV reader</title>

    <meta name="robots" content="noindex,nofollow" />
    <meta name="description" content="A Bar chart using the CSV reader" />
    
</head>
<body>

    <h1>Graphique barre en utilisant un fichier CSV avec le CSV reader de RGraph</h1>

    <div style="background-color: #ffc; border: 2px solid #cc0; border-radius: 5px; padding: 5px;">
        <b>Exemple de graphe avec PHP</b>
    </div>
    <canvas id="cvs" width="500" height="250">[No canvas support]</canvas>
    
    <script>
        $(document).ready(function ()
        {
            //RGraph.CSV('id:klkl', function (csv)
            RGraph.CSV('../temp/CSV_multi.csv', function (csv)
            {
                /**
                * La 1ère ligne est celle des intitulés de colonnes
                */
                var labels = csv.getRow(0);
                var nbcol = labels.length;

                // Combinaison 2 graphes par colonne ... valables pour n graphes
                var data = new Array();
                for (i = 0; i < nbcol; i++){
                    var d1 = new Array();
                    d1 = csv.getCol(i, 1);
                    data[i] = d1;
                }

                var bar = new RGraph.Bar({
                    id: 'cvs',
                    data: data,
                    options: {
                        labels: labels,
                        colors: ['Gradient(#DDF:#01B4FF)', 'yellow'],
                        grouping: 'stacked',
                        noxaxis: true,
                        shadow: {
                            offsetx: 2,
                            offsety: 2,
                            blur: 5
                        },
                        hmargin: 15,
                        background: {
                            grid: {
                                autofit: {
                                    numvlines: 7
                                }
                            }
                        },
                        linewidth: 2,
                        strokestyle: 'white'
                    }                    
                }).draw();
            })
        })
    </script>

    <p>

    </p>

    <p>
        <a href="./">&laquo; Back</a>
    </p>

    <div id="klkl" style="display: none">
        Richard,8,4,7,6,5,3,4
        Dave,7,4,6,9,5,8,7
        Luis,4,3,5,2,6,5,4
        Kevin,4,2,8,9,6,7,3
        Joel,4,5,1,3,5,8,6
        Pete,4,5,6,3,5,8,6
    </div>

</body>
</html>