<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
<meta name="viewport" content="width=device-width" />

<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" type="text/css"/>

<link href="style.css" rel="stylesheet" type="text/css"/>
<link href="style_print.css" rel="stylesheet" type="text/css"  media="print"/>

<!-- icones apple -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico"/>
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png"/>
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png"/>
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png"/>

<script type="text/javascript" src="bootstrap/js/jquery-1.11.1.js"></script>
<link rel="stylesheet" href="bootstrap/css/jquery-ui.css">
<script src="bootstrap/js/jquery-ui.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>

<?php
include_once 'inc/connection.php';
//include_once 'inc/filtre_var.php';

$tab_parametre = array();

    $rq_parametre = "SELECT * FROM PARAMETRE;";
    $res_parametre = $GLOBALS['connexion']->query($rq_parametre);
    
    while ($ligne = mysqli_fetch_assoc($res_parametre))
    {
        $tab_parametre[$ligne['PAR_NO']]['LIBELLE']=$ligne['PAR_LIBELLE'];
        $tab_parametre[$ligne['PAR_NO']]['CONTENU']=$ligne['PAR_VALEUR'];
    }
?>
<style>
    .ram
    {
        color: <?php 
        foreach ($tab_parametre as $contenu) 
        {
            if($contenu['LIBELLE']=='couleur_ram')
            {
                echo $contenu['CONTENU'];
            }
        } 
        ?>
    }
    
    .conges
    {
         color: <?php 
        foreach ($tab_parametre as $contenu) 
        {
            if($contenu['LIBELLE']=='couleur_conge')
            {
                echo $contenu['CONTENU'];
            }
        } 
        ?>
    }
    
    .adm
    {
         color: <?php 
        foreach ($tab_parametre as $contenu) 
        {
            if($contenu['LIBELLE']=='couleur_adm')
            {
                echo $contenu['CONTENU'];
            }
        } 
        ?>
    }
    
    .frais
    {
         color: <?php 
        foreach ($tab_parametre as $contenu) 
        {
            if($contenu['LIBELLE']=='couleur_frais')
            {
                echo $contenu['CONTENU'];
            }
        } 
        ?>
    }
    
    .autre,.accueil
    {
         color: <?php 
        foreach ($tab_parametre as $contenu) 
        {
            if($contenu['LIBELLE']=='couleur_autre')
            {
                echo $contenu['CONTENU'];
            }
        } 
        ?>
    }

    #logo
    {
        background-image: url('<?php 
        foreach ($tab_parametre as $contenu) 
        {
            if($contenu['LIBELLE']=='logo_apsaroke')
            {
                echo $contenu['CONTENU'];
            }
        } 
        ?>');
        background-repeat : no-repeat;
        width: 500px;
        height:150px;
    }
</style>