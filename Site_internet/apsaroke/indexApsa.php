<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="APSAROKE, l'effet 6ème sens : une société de services experte dans le pôle de l'ingénierie informatique et de l'expertise IT. Notre SSII, prestataire de services informatiques spécialisée dans l'infrastrucute IT et l'intégration d'applications, propose des services de qualités aux Grands Comptes et aux PMI-PME." />
    <meta name="abstract" content="APSAROKE, l'effet 6ème sens : une société de services experte dans le pôle de l'ingénierie informatique et de l'expertise IT. Notre SSII, prestataire de services informatiques spécialisée dans l'infrastrucute IT et l'intégration d'applications, propose des services de qualités aux Grands Comptes et aux PMI-PME." />
    <meta name="keywords" content="APSAROKE, SSII, ERP, IT, infrastructures, solutions, services informatiques, prestataire informatique, corse, intégration, développement, consulting" />
    <link href="https://bootswatch.com/lumen/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet">
       <link href=" https://raw.githubusercontent.com/noizwaves/bootstrap-social-buttons/v1.0.0/social-buttons-3.css" rel="stylesheet">
    <link href="apsaroke.css" rel="stylesheet">

    <title>APSAROKE, prestataire de services informatiques/Accueil</title>
</head>

<body>
    

    <div id="header_page" style="background-color: #FFFFFF">
       <a href="portail.html"><img src="image/LogoApsa.jpg" style="width: 70%; height: 95px; margin-left: 150px;"></a>
    
</div>
    
        <?php include 'nav.php';  ?>
                
<section>
            <div class="container">
                        <div class="blocDroit">

            <div class="nav1">
                <h3> Contact : </h3>
                <p>Bernard PEYRIN<br />
                    Directeur
                    <br />
                    <p>Lolita MARTIN<br />
                    assistante RH</p>
                <p>8, rue Victor Lagrange
                    <br>69007 Lyon</p>
                <p><a href="mailto:message@apsaroke.com"><img src="image/email.png" height="24" width="24"></img></a> message@apsaroke.com</p>
                <p>Tél: 04.37.65.12.28</p>
                 <p><a href="https://fr-fr.facebook.com/pages/Apsaroke/638127282879757"><img src="image/facebook.png"></img></a> Découvrez <span class="apsaroke">APSAROKE</span> sur facebook</p>
                <p><a href="https://fr.linkedin.com/in/apsaroke-lyon-159a76a8"><img src="image/linkedin.png" height="24" width="24"></img></a>Découvrez <span class="apsaroke">APSAROKE</span> sur Linkedin.</p>
<p><a href="http://fr.viadeo.com/fr/profile/groupe.apsaroke"><img src="image/viadeo.png" height="24" width="24"></img></a>Découvrez <span class="apsaroke">APSAROKE</span> sur Viadeo.</p></p>

        </div>
    </div>


            <div class="row">

                                <div class="container-text-cadre">
  
      <!-- carousel -->
            <div id="my_carousel" class="carousel slide" data-ride="carousel">

	<!-- Slides -->
	<div class="carousel-inner">
	<!-- Page 1 -->
	<div class="item active">  
	<div class="carousel-page">
	<img src="image/voeux%20-%20Copie.png" class="img-responsive img-rounded" style="margin:0px auto; height: 100%; width: 100%;" />
	</div> 
	<div class="carousel-caption"></div>
	</div>   
	<!-- Page 2 -->
	<div class="item">  
	<div class="carousel-page">
	<img src="image/"g class="img-responsive img-rounded" 
	style="margin:0px auto;max-height:100%; width: 100%;"  />
	</div>  
	<div class="carousel-caption"></div>
	</div>
        <!-- Page 3 -->
        <div class="item">  
	<div class="carousel-page">
	<img src="image/"g class="img-responsive img-rounded" 
	style="margin:0px auto;max-height:100%; width: 100%;"  />
	</div>  
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
                                <!-- boutons stop and play -->
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
    <script type="text/javascript" src="scriptApsaroke.js"></script>
            
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