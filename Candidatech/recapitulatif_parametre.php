<?php
      include 'inc/verif_session.php';
      include 'calendrier/fonction_mois.php';
      include 'inc/connection.php';?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Paramètre de l'application</title>
        <?php include 'head.php'; ?>
    </head>
<body>
    <!-- Barre de menu-->
    <?php
    $GLOBALS['titre_page'] = '<div class="">Paramètres</div>';
    include ("menu/menu_global.php"); ?>
<?php
$tab_parametre = array();

    $rq_parametre = "SELECT * FROM PARAMETRE;";
    $res_parametre = $GLOBALS['connexion']->query($rq_parametre);
    
    while ($ligne = mysqli_fetch_assoc($res_parametre))
    {
        $tab_parametre[$ligne['PAR_NO']]['LIBELLE']=$ligne['PAR_LIBELLE'];
        $tab_parametre[$ligne['PAR_NO']]['CONTENU']=$ligne['PAR_VALEUR'];
    }
?>
<div style="margin-top: 339px;" class="container-fluid ">
<form action="remplissage_parametre.php" method="post" enctype="multipart/form-data" class="form-horizontal well">


<fieldset> 
<legend>TVA :</legend>
<div class="row">
    <div class="span3 ">TVA1 :</div>
    <div class="span3"><input name="TVA1" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='TVA1')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span12"><br></div>
    <div class="span3 ">TVA2 :</div>
    <div class="span3"><input name="TVA2" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='TVA2')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span12"><br></div>
    <div class="span3 ">TVA3 :</div>
    <div class="span3"><input name="TVA3" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='TVA3')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
</div>
</fieldset>
<fieldset> 
<legend>Conditions de vente :</legend>
<div class="row">
    <div class="span3 ">Nouvelles conditions de vente :</div>
    <div class="span3"><textarea name="CONDITIONS_VENTE" style="width: 680px; height: 80px" onkeyup="javascript: haut(this.id)" onfocus="javascript: top(this.id)"><?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='conditions_vente')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?></textarea></div>
</div>
</fieldset>
<fieldset> 
<legend>P&eacute;nalit&eacute;s de retard :</legend>
<div class="row">
    <div class="span3 ">Nouvelles p&eacute;nalit&eacute;s de retard :</div>
    <div class="span3"><textarea name="PENALITES_RETARD" style="width: 680px; height: 80px" onkeyup="javascript: haut(this.id)" onfocus="javascript: top(this.id)"><?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='penalites_retard')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?></textarea></div>
</div>
</fieldset>
<fieldset> 
    <legend>Modes de r&egrave;glement</legend>
    <div class="row">
    <div class="span3 ">Nouveaux modes de r&egrave;glement :</div>
    <div class="span3"><textarea name="MODES_REGLEMENT" style="width: 680px; height: 80px" onkeyup="javascript: haut(this.id)" onfocus="javascript: top(this.id)"><?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='mode_reglement')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?></textarea></div>
    </div>
</fieldset>
<fieldset> 
    <legend>Adresse du si&egrave;ge social : </legend>
    <div class="row">
    <div class="span3 ">Nouvelle adresse du si&egrave;ge social :</div>
    <div class="span3"><textarea name="ADRESSE_SIEGE" style="width: 680px; height: 80px" onkeyup="javascript: haut(this.id)" onfocus="javascript: top(this.id)"><?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='adresse_siege_social')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?></textarea></div>
    </div>
</fieldset>
<fieldset> 
    <legend>Coordonn&eacute;es bancaires :</legend>
    <div class="row">
    <div class="span3 ">Code Guichet 1 :</div>
    <div class="span3"><input name="CDGUICHET1" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_CDGUICHET1')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>

    <div class="span3 ">Nom de la banque 1 :</div>
    <div class="span3"><input name="NOMBANQUE1" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_NOMBANQUE1')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span12"><br></div>
    <div class="span3 ">Code Banque 1 :</div>
    <div class="span3"><input name="CDBANQUE1" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_CDBANQUE1')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span3 "> Num&eacute;ro du compte 1 :</div>
    <div class="span3"><input name="NUMCOMPTE1" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_NUMCOMPTE1')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span12"><br></div>
    <div class="span3 ">Cl&eacute; de RIB 1 :</div>
    <div class="span3"><input name="CLERIB1" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_CLERIB1')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>

    <div class="span12"><br></div>
    <div class="span3 ">Code Guichet 2 :</div>
    <div class="span3"><input name="CDGUICHET2" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_CDGUICHET2')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span3 ">Nom de la banque 2 :</div>
    <div class="span3"><input name="NOMBANQUE2" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_NOMBANQUE2')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span12"><br></div>
    <div class="span3 ">Code Banque 2 :</div>
    <div class="span3"><input name="CDBANQUE2" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_CDBANQUE2')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>

    <div class="span3 ">Num&eacute;ro du compte 2 :</div>
    <div class="span3"><input name="NUMCOMPTE2" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_NUMCOMPTE2')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    
    <div class="span12"><br></div>
    <div class="span3 ">Cl&eacute; de RIB 2 :</div>
    <div class="span3"><input name="CLERIB2" value="<?php 
     foreach ($tab_parametre as $contenu) 
     {
        if($contenu['LIBELLE']=='PAR_CLERIB2')
         {
            echo $contenu['CONTENU'];
         }
     }
     ?>" type="text"></div>
    </div>
</fieldset>
    <br/>
    <input name="recherche" value="" type="hidden">
    <div class="row-fluid">
        <div class="offset5 span7">
            <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i> </button>
        </div>
    </div>
</form>
</div>
<script type="text/javascript">
        function haut(idt) {
           if (document.getElementById(idt).scrollTop > 0) aug(idt);
        }
        function aug(idt) {
           var h = parseInt(document.getElementById(idt).style.height);
           document.getElementById(idt).style.height = h + 10 +"px";
           haut(idt);
        }
        function top(idt) {
           document.getElementById(idt).scrollTop = 100000;
           haut(idt);
        }
</script>
<?php
    include ('inc/regex.php');
    include ('inc/regex_javascript.php');
?>
</body>
</html>


