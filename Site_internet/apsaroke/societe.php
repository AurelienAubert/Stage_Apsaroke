<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://bootswatch.com/lumen/bootstrap.min.css" rel="stylesheet">
    <link href="apsaroke.css" rel="stylesheet">
        <link rel="stylesheet"  href="screen.css" type="text/css" />
                <meta name="description" content="APSAROKE, l'effet 6ème sens : une société de services experte dans le pôle de l'ingénierie informatique et de l'expertise IT. Notre société offre des services de développement web , solutions applicatives, intégration SAP et administration systèmes et réseaux." />
    <meta name="abstract" content="APSAROKE, l'effet 6ème sens : une société de services experte dans le pôle de l'ingénierie informatique et de l'expertise IT. Notre société offre des services de développement web , solutions applicatives, intégration SAP et administration systèmes et réseaux." />
    <meta name="keywords" content="développement, consulting, SI, c#, c++, ASP.net, VB.net, java, J2EE, php, AJAX, VB, rpg, sql, plsql, shell, adelia, cobol, amoa, itil, erp, sapr3, business intelligence, integrator, administration systèmes et réseaux, intégration d'applications, homologation et test, collaborateur, société, collaborateurs "/>
    
    <title>APSAROKE/société</title>
</head>

<body>
        <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="accueil.html">Accueil <span class="sr-only">(current)</span></a></li>
        <li  class="active"><a href="societe.html">Société</a></li>
                         <li><a href="metiers.html">Métiers</a></li>
          <li><a href="strategieRH.html">Recrutement</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

    <div id="header_page" style="background-color: #FFFFFF">

       <a href="portail.html"><img src="image/LogoApsa.jpg" style="width: 70%; height: 95px; margin-left: 150px;"></a>
        </div>


    <section>
        <div class="container">

            <div class="row">
                                <div class="container-text-cadre">
                
                    
            <div id="my_carousel" class="carousel slide" data-ride="carousel">

	<!-- Slides -->
	<div class="carousel-inner">
	<!-- Page 1 -->
	<div class="item active">  
	<div class="carousel-page">
	<img src="image/societe-presentation.png" class="img-responsive img-rounded" style="margin:0px auto; height: 100%; width: 100%;" />
	</div> 
	<div class="carousel-caption"></div>
	</div>   
	<!-- Page 2 -->
	<div class="item"> 
	<div class="carousel-page">
        <img src="image/societe-departements.png" class="img-responsive img-rounded" 
	style="margin:0px auto;max-height:100%; width: 100%;"  /></div> 
	<div class="carousel-caption"></div>
	</div>  
	</div>
	<!-- Contrôles -->
	<a class="left carousel-control" href="#my_carousel" data-slide="prev">
	<span class="glyphicon glyphicon-chevron-left"></span>
	</a>
	<a class="right carousel-control" href="#my_carousel" data-slide="next">
	<span class="glyphicon glyphicon-chevron-right"></span>
	</a>
                   <button type="button" id="myBtn" class="btn btn-default btn-lg" id="myBtn">Play</button>
  <button type="button" id="myBtn2" class="btn btn-default btn-lg" id="myBtn">Stop</button><br><br>  
	</div>
       
                    </div>  
                </div>
                </div>



    
        <footer>
            <div class="row">
                <div class="col-lg-6">
                       <a href="portail.html"><img class="imgFoot" src="image/LogoPiedPageNav.png"></img></a>
                </div>
                <div class="col-lg-6">
                    <div style="float:right;">
                        <span class="mentions">APSAROKE SAS <br>
                Société par action simplifiée au capital de 39 000€ <br> 435 379 284 RCS Lyon <br>
Siège social : 8 rue Victor Lagrange 69007 LYON <br>
Tel : +33 (0)4 37 65 12 28 <br>
Publication Director : / Directeur de la publication : M. Bernard Peyrin</span>


                    </div>
                </div>
            </div>
        </footer>
    </section>

    <!-- jQuery -->
    <script src="jquery/jquery.min.js"></script>
    <!-- JavaScript Boostrap plugin -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
            <script>
$(document).ready(function(){
    // Activate Carousel
    $("#my_carousel").carousel("pause");

    // Click on the button to start sliding 
    $("#myBtn").click(function(){
        $("#my_carousel").carousel("cycle");
    });

    // Click on the button to stop sliding 
    $("#myBtn2").click(function(){
        $("#my_carousel").carousel("pause");
    });
        
    // Enable Carousel Controls
    $(".left").click(function(){
        $("#my_carousel").carousel("prev");
    });
    $(".right").click(function(){
        $("#my_carousel").carousel("next");
    });
});
</script>
</body>

</html>