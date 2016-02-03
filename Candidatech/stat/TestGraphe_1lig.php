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
        // Script issu de demos/bar-csv-reader.html mais avec un vrai fichier csv
        $(document).ready(function ()
        {
            RGraph.CSV('../temp/CSV_1ligne.csv', function (csv)
            {
                /**
                * Fetch the first row of the CSV file, starting at the second column
                */
                var data = csv.getRow(1);
                
                /**
                * Fetch the first column which become the labels
                */
                var labels = csv.getRow(0);


                var bar = new RGraph.Bar({
                    id: 'cvs',
                    data: data,
                    options: {
                        labels: {
                            self: labels,
//                            above: {
//                                specific: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
//                                size: 8
//                            }
                        },
//                        colors: ['Gradient(#DDF:#01B4FF)', 'yellow'],
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
                        colors: ['Gradient(#DDF:#01B4FF)'],
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

</body>
</html>