<?php
if(!isset($_SESSION))
{
    session_start();
}
$accred = $_SESSION['accreditation'];

if ($accred == 5) {
    echo "<style>
    #COL_CON{display: none}
    #COL_RAM_edi{display: none}
    #COL_RAM_pri{display:none}
    #COL_FRA{display: none}
    #COL_AUT{display: none}
    #REP_REE{display: none}
     </style>";
}
if ($accred == 4) {
    echo "<style>
    #COL_RAM_tab{display: none}
    #COL_ADM{display: none}
    #REP_REE{display: none}
    </style>";
}
if ($accred == 3) {
    echo "<style>
    #CON_inv{display: none}
    #FRA_val{display: none}
    #COL_cre{display: none}
    #COL_mod{display: none}
    #COL_sup{display: none}
    #RH_MP_cre{display: none}
    #RH_mes{display: none}
    #RH_ser{display: none}
    #CLI_sup{display :none}
    #CTC_sup{display :none}
    #FOU_sup{display: none}
    #CTF_sup{display: none}
    #PRO_sup{display: none}
    #CPT{display: none}
    #PAR{display: none}
    #REP_REE{display: none}
</style>";
}
if ($accred == 2) {
    echo "<style>
    #RH_ser{display: none}
    #CPT_PAI_edi{display: none}
    #CPT_PAI_vis{display: none}
    #CPT_FAC_pre{display: none}
    #CPT_FAC_pro{display: none}
    #CPT_FAC_fac{display: none} 
    #CPT_moisprime{display: none}
    #PAR_NUM_DEF{display: none}
    #PAR_COU{display: none}
    #PAR_MAI{display: none}
    #PAR_DOC{display: none}
</style>";
}
if ($accred == 1) {
    echo "<style>
</style>";
}
include 'menu/version.php';

// Recherche des documents perso d'un collaborateur
include 'recherche_docperso.php';
?>
<div class="header">			
    <div class='pull-right not_printed' style="margin-right:10px;">
        <legend>
            <strong class="not_printed"><?php echo $GLOBALS['num_version']; ?></strong><br />
            Bonjour <?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?><br />
            <?php echo date('d-m-Y'); ?>
        </legend>
<?php
if (isset($GLOBALS['retour_page'])) {
    echo '<button id="boutonRetour" class="btn btn-primary" type="button" onclick="javascript:window.location.replace(\'' . $GLOBALS['retour_page'] . '\');"> Retour  <i class="icon-arrow-left"></i> </button>';
}

if (strpos($_SERVER['PHP_SELF'], 'accueil.php') === false) {
    echo ' <button id="boutonQuitter" class="btn btn-primary" type="button" onclick="javascript:window.location.replace(\'accueil.php\');"> Quitter<i class="icon-remove"></i> </button>';
}
?>
    </div>

    <!-- Logo d'Apsaroke-->
    <div>
        <img src="image/LogoApsa.jpg" alt="logo Apsaroke" height="500" width="565" class='logo'/>
        <!--<div id='logo'></div>-->
        <h3 id="titremenu" style="text-align:center;"><?php echo isset($GLOBALS['titre_page']) ? $GLOBALS['titre_page'] : '&nbsp;'; ?></h3>
    </div>

    <div class="navbar navbar-static not_printed">
        <div class="navbar-inner">
<?php
if ($accred > 3) {
?>
            <ul role="navigation" class="nav">
                <li><a href="accueil.php">Accueil</a></li>
                <li class="dropdown" id="COL_CON">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Cong&eacutes<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="chx_date.php?type=dem_conge">Demander/Modifier un cong&eacute</a></li> 
                        <li id="CON_listcol"><a href="chx_annee.php?type=tab_conges_coll">Liste des cong&eacutes</a></li>
                        <li id="CON_vis"><a href="etatConges.php?type=collaborateur_interne">Visualiser les congés payés</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="COL_RAM">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">RAM<b class="caret" ></b></a>
                    <ul class="dropdown-menu">
                        <li id="COL_RAM_edi"><a href="chx_date.php?type=edit_ram">Voir/Modifier un RAM</a></li>
                        <li id="COL_RAM_pri"><a href="chx_date.php?type=print_ram">Imprimer un RAM</a></li>
                        <li id="COL_RAM_tab"><a href="chx_date.php?type=tab_ram">Visualiser les RAM des Collaborateurs</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="COL_FRA">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Frais<b class="caret" ></b></a>
                    <ul class="dropdown-menu">
                        <li id="COL_FRA_urb"><a href="chx_date.php?type=frais_urb">Note de Frais Urbains</a></li>
                        <li id="COL_FRA_reel"><a href="chx_date.php?type=frais_reel">Note de Frais R&eacuteels</a></li>
                        <li id="COL_FRA_gd"><a href="chx_date.php?type=frais_gd">Note de Frais Grand D&eacuteplacement</a></li>
                        <li id="COL_FRA_km"><a href="chx_date.php?type=frais_km">Note de Frais Kilom&eacutetriques</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="COL_ADM">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Administration<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li id="COL_ADM_PAI_vis"><a href="chx_date.php?type=visu_prepaie">Visualiser une fiche Pr&eacute-Paie</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="COL_AUT">
                    <a data-target="#" href="#" data-toggle="dropdown" class="dropdown-toggle">Param&egravetres<b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                        <li id="AUT_mod"><a href="modification_mdp.php">Modifier un login/mot de passe pour un collaborateur</a></li>
                    </ul>
                </li>
    <?php
    if (count($arrficperso) > 0){
    ?>
                <li class="dropdown" id="PERSO">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Documents perso<b class="caret"></b></a>
                    <ul class="dropdown-menu">
        <?php
        foreach($arrficperso as $fic){
            $arr = explode('/', $fic);
        ?>
                        <li><a href="javascript:ouvre('<?php echo $fic ?>');"><?php echo $arr[count($arr) - 1] ?></a></li>
        <?php
        }
        ?>
                    </ul>
                </li>
    <?php
    }
    ?>
            </ul>
<?php
}else{
?>
<!--    <div class="navbar navbar-static not_printed">
        <div class="navbar-inner">-->
            <ul role="navigation" class="nav">
                <li><a href="accueil.php">Accueil</a></li>
                <li class="dropdown" id="RH">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Gestion RH<b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">

                        <li id="CON" class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Cong&eacutes<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="chx_date.php?type=dem_conge">Demander/Modifier un cong&eacute</a></li> 
                                <li id="CON_val"><a href="choix_valid_conges.php">Valider une demande de cong&eacutes</a></li>
                                <li id="CON_lis"><a href="chx_annee.php?type=tab_conges_coll">Liste des cong&eacutes</a></li>
                                <li id="CON_tab" ><a href="chx_date.php?type=tab_conges">Visualiser les cong&eacutes des Collaborateurs</a></li>
                                <li id="CON_inv"><a href="modifier_conges.php">Invalider des cong&eacutes</a></li>
                                <li id="CON_eta" class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Etat des congés<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li id="CON_vis"><a href="recherche.php?type=collaborateur_interne&action=etatConges">Visualiser les congés payés</a></li>
                                        <li id="CON_rtt"><a href="recherche.php?type=collaborateur_interne&action=etatRTT">Visualiser les RTT</a></li>
                                        <li id="CON_saiSol"><a href="modification_solde_CP_RTT.php?type=CP">Saisi du solde des congés payés par défaut</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li id="RAM" class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">RAM<b class="caret" ></b></a>
                            <ul class="dropdown-menu">
                                <li id="RAM_edi"><a href="chx_date.php?type=edit_ram">Voir/Modifier un RAM</a></li>
                                <li id="RAM_pri"><a href="chx_date.php?type=print_ram">Imprimer un RAM</a></li>
                                <li id="RAM_tab"><a href="chx_date.php?type=tab_ram">Visualiser les RAM des Collaborateurs</a></li>
                            </ul>
                        </li>
                        <li id="FRA" class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Frais<b class="caret" ></b></a>
                            <ul class="dropdown-menu">
                                <li id="FRA_urb"><a href="chx_date.php?type=frais_urb">Note de Frais Urbains</a></li>
                                <li id="FRA_ree"><a href="chx_date.php?type=frais_reel">Note de Frais R&eacuteels</a></li>
                                <li id="FRA_gd"><a href="chx_date.php?type=frais_gd">Note de Frais Grand D&eacuteplacement</a></li>
                                <li id="FRA_km"><a href="chx_date.php?type=frais_km">Note de Frais Kilom&eacutetriques</a></li>
                                <li id="FRA_val"><a href="choix_valid_frais.php">Valider une demande de frais</a></li>
                                <li id="FRA_tab"><a href="chx_date.php?type=tab_frais">Tableau des Frais par Collaborateur</a></li>
                            </ul>
                        </li>
                        <li id="COL" class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Collaborateurs<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="COL_cre"><a href="chx_type_collaborateur.php?action=ajout">Ajouter un collaborateur</a></li>
                                <li id="COL_vis"><a href="chx_type_collaborateur.php?action=affichage">Visualiser un collaborateur</a></li>
                                <li id="COL_NJT"><a href="recherche.php?type=mission&action=suivi_commande">Visualiser un suivi de commande client</a></li> 
                                <li id="COL_mod"><a href="chx_type_collaborateur.php?action=modification">Modifier un collaborateur</a></li> 
                                <li id="COL_sup"><a id="COL_sup" href="chx_type_collaborateur.php?action=suppression">Supprimer un collaborateur</a></li> 
                            </ul>
                        </li>
                        <li id="RH_sui"><a href="chx_date.php?type=suivi">Suivi missions</a></li> 
                        <li id="RH_MP_cre"><a href="creation_login_pwd.php">Cr&eacuteer un login/mot de passe pour un collaborateur</a></li>
                        <li id="RH_MP_mod"><a href="modification_mdp.php">Modifier un login/mot de passe pour un collaborateur</a></li>
                        <li id="RH_mes"><a href="rechercheMessage.php">Gestion du message accueil</a></li>
                        <li id="RH_ser"><a href="gestion_noteservice.php">Gestion des notes de service</a></li>

                    </ul>
                </li>

                <li class="dropdown" id="COM">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Gestion commerciale<b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">

                        <li id="CLI" class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Client<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="CLI_cre"><a href="ajout.php?type=client">Ajouter un client</a></li>
                                <li id="CLI_vis"><a href="recherche.php?type=client&action=affichage">Visualiser une fiche client</a></li>
                                <li id="CLI_mod"><a href="recherche.php?type=client&action=modification">Modifier une fiche client</a></li>
                                <li id="CLI_sup"><a href="suppression.php?type=client">Supprimer une fiche client</a></li> 
                                <li id="CTC" class="dropdown-submenu">
                                    <a href="#">Contact client<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li id="CTC_cre"><a href="ajout.php?type=contact_client">Ajouter un contact client</a></li>
                                        <li id="CTC_vis"><a href="recherche.php?type=contact_client&action=affichage">Visualiser un contact client</a></li> 
                                        <li id="CTC_mod"><a href="recherche.php?type=contact_client&action=modification">Modifier un contact client</a></li>
                                        <li id="CTC_sup"><a href="suppression.php?type=contact_client">Supprimer un contact client</a></li> 
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li id="FOU" class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Fournisseurs<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="FOU_cre"><a href="ajout.php?type=fournisseur">Ajouter un fournisseur</a></li>
                                <li id="FOU_vis"><a href="recherche.php?type=fournisseur&action=affichage">Visualiser une fiche fournisseur</a></li> 
                                <li id="FOU_mod"><a href="recherche.php?type=fournisseur&action=modification">Modifier une fiche fournisseur</a></li>
                                <li id="FOU_sup"><a href="suppression.php?type=fournisseur">Supprimer une fiche fournisseur</a></li>  
                                <li id="CTF" class="dropdown-submenu">
                                    <a href="#">Contact fournisseur<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li id="CTF_cre"><a href="ajout.php?type=contact_fournisseur">Ajouter un contact fournisseur</a></li>
                                        <li id="CTF_vis"><a href="recherche.php?type=contact_fournisseur&action=affichage">Visualiser un contact fournisseur</a></li> 
                                        <li id="CTF_mod"><a href="recherche.php?type=contact_fournisseur&action=modification">Modifier un contact fournisseur</a></li>
                                        <li id="CTF_sup"><a href="suppression.php?type=contact_fournisseur">Supprimer un contact fournisseur</a></li> 
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li id="PRO" class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Projets<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="PRO_cre"><a href="ajout.php?type=projet">Ajouter un projet</a></li>
                                <li id="PRO_vis"><a href="recherche.php?type=projet&action=affichage">Visualiser une fiche projet</a></li> 
                                <li id="PRO_mod"><a href="recherche.php?type=projet&action=modification">Modifier une fiche projet</a></li>
                                <li id="PRO_sup"><a href="suppression.php?type=projet">Supprimer un projet</a></li> 
                            </ul>
                        </li>

                    </ul>
                </li>

                <li class="dropdown" id="CPT">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Gestion comptable<b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                        <li id="CPT_PAI_edi"><a href="chx_date.php?type=edit_prepaie">Editer une fiche Pr&eacute-Paie</a></li>
                        <li id="CPT_PAI_vis"><a href="chx_date.php?type=visu_prepaie">Visualiser une fiche Pr&eacute-Paie</a></li>
                        <li id="CPT_FAC_pre"><a href="chx_date.php?type=prefacturation">Editer une fiche Pr&eacute-Facturation</a></li>
                        <li id="CPT_FAC_pro"><a href="chx_date.php?type=proforma">Visualiser/Imprimer un Proforma</a></li>
                        <li id="CPT_TR"><a href="chx_date.php?type=tr">R&eacutecapitulatif Tickets Restaurant</a></li> 
                        <li id="CPT_enscol"><a href="tous_les_collab.php">Ensemble des collaborateurs</a></li> 
                        <li id="CPT_moisprime"><a href="param_mois_prime.php">Param&eacutetrage prime anciennet&eacute</a></li>
                    </ul>
                </li>

                <li class="dropdown" id="REP">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Gestion reporting<b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                        <li id="REP_REE"><a href="choix_reimpression.php">Ré-imprimer un document</a></li>
                        <li id="REP_GDC"><a href="chx_annee.php?type=graph_col">Graphique des collaborateurs</a></li>
                        <li id="REP_GCA"><a href="chx_annee.php?type=graph_ca">Graphique du chiffre d'affaire</a></li>
                    </ul>
                </li>

                <li class="dropdown" id="PAR">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Paramètrage<b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                        <li id="PAR_NUM_DEF"><a href="num_def.php">Facture : Numéro par défaut</a></li>
                        <li id="PAR_CSV"><a href="chx_date.php?type=csv">Export CSV</a></li>
                        <li id="PAR_COU"><a href="couleur.php">Param&eacutetrage des couleurs</a></li>
                        <li id="PAR_MAI"><a href="config_email.php">Param&eacutetrage de l'e-mail</a></li>
                        <li id="PAR_SCP"><a href="config_annee_CP.php">Param&eacutetrage de l'année de départ pour les CP</a></li>
                        <li class="dropdown-submenu" id="PAR_ENT">
                            <a href="#">Entreprise<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="PAR_ENT_cre"><a href="ajout.php?type=entreprise">Ajouter une entreprise</a></li>
                                <li id="PAR_ENT_mod"><a href="recherche.php?type=entreprise&action=affichage">Visualiser/Modifier une entreprise</a></li>
                                <li id="PAR_ENT_sup"><a href="suppression.php?type=entreprise">Supprimer une entreprise</a></li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu" id="PAR_BAN">
                            <a href="#">Banque<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="PAR_BAN_cre"><a href="ajout.php?type=banque">Ajouter une banque</a></li>
                                <li id="PAR_BAN_mod"><a href="recherche.php?type=banque&action=affichage">Visualiser/Modifier une banque</a></li>
                                <li id="PAR_BAN_sup"><a href="suppression.php?type=banque">Supprimer une banque</a></li>
                            </ul>
                        </li>
                        <li id="PAR_FNC" class="dropdown-submenu">
                            <a href="#">Fonction<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="PAR_FNC_cre"><a href="ajout.php?type=fonction">Ajouter une fonction</a></li>
                                <li id="PAR_FNC_vis"><a href="recherche.php?type=fonction&action=affichage">Visualiser une fonction</a></li> 
                                <li id="PAR_FNC_mod"><a href="recherche.php?type=fonction&action=modification">Modifier une fonction</a></li>
                                <li id="PAR_FNC_sup"><a href="suppression.php?type=fonction">Supprimer une fonction</a></li> 
                            </ul>
                        </li>
                        <li id="PAR_MRG" class="dropdown-submenu">
                            <a href="#">Mode de règlement<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="PAR_MRG_cre"><a href="ajout.php?type=modereglement">Ajouter un mode de règlement</a></li>
                                <li id="PAR_MRG_vis"><a href="recherche.php?type=modereglement&action=affichage">Visualiser un mode de règlement</a></li> 
                                <li id="PAR_MRG_mod"><a href="recherche.php?type=modereglement&action=modification">Modifier un mode de règlement</a></li>
                                <li id="PAR_MRG_sup"><a href="suppression.php?type=modereglement">Supprimer un mode de règlement</a></li> 
                            </ul>
                        </li>
                        <li id="PAR_DOC"><a href="rechercheLibDoc.php">Gestion des documents</a></li>

                    </ul>
                </li>
            </ul>
<?php
}
if ($accred < 5){
?>

            <ul class="nav pull-right">
                <li id="ADM_aide"><a href="#"><i class="icon-question-sign"></i>Aide</a></li>
            </ul>
<?php
}
?>
            <ul class="nav pull-right">
                <li><a href="deconnexion.php"><i class="icon-remove"></i> D&eacuteconnexion</a></li>
            </ul>
            <ul class="nav pull-right">
                <li><a href="#" id="contact"><i class="icon-envelope"></i> Contact</a></li>
            </ul>
        </div>
    </div>
</div>

<div id="dialog-form" title="Contact">  
    <form id="contact-form" action="inc/contact.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <label for="contact_objet">Objet</label>
            <input type="text" name="objet" id="contact_objet" class="text ui-widget-content ui-corner-all">
            <label for="contact_message">Message</label>
            <textarea name="message" id="contact_message" class="text ui-widget-content ui-corner-all" rows="10"></textarea>
            <input type="file" id="contact_fichier" name="fichier">
        </fieldset>
    </form>
</div>

<div class="escape">
    <!-- -->
</div>

<script src="jquery.iframe-post-form.js"></script>
<script>
    // ouverture des documents perso
    function ouvre(fic){
        window.open(fic, '','');
    }

//    $("a[data-toggle='dropdown']").click(function() {
//        var titre = $(this).text();
//            $('#titremenu').html(titre);
//    });
    
    $(function() {
        var media_type = "screen";

        window.onbeforeprint = function() {
            media_type = "print";
            $('.').css('margin-top', '0px');
        };
        window.onafterprint = function() {
            media_type = "screen";
            $('.').css('margin-top', $('.header').height() + 5);
        };

        function listener(query) {
            if (query.matches) {
                media_type = "print";
                $('.').css('margin-top', '0px');
            }
            else {
                media_type = "screen";
                $('.first-element').css('margin-top', $('.header').height() + 5);
            }
        }

        var media_query = window.matchMedia("print");
        media_query.addListener(listener);
        listener(media_query);

        $(window).resize(function() {
            if (media_type != 'print') {
                $('.').css('margin-top', $('.header').height() + 5);
            }else{
                $('.').css('margin-top', '0px');
            }
        });

        $('#contact-form').iframePostForm({
            json: false,
            post: function() {
                if (!$('#contact-message').length) {
                    $(this).before('<div id="contact-message" style="display:none; padding:10px; text-align:center" />');
                }

                $('#contact-message')
                    .html('Envoi du mail...')
                    .css({
                        color: '#610000',
                        background: '#F0C8C8',
                        border: '2px solid #610000'
                })
                    .slideDown();
            },
            complete: function() {
                $('#contact-message')
                    .html('Mail envoyé')
                    .css({
                        color: '#006100',
                        background: '#c6efce',
                        border: '2px solid #006100'
                });
            }
        });

        $('#dialog-form').dialog({
            autoOpen: false,
            height: 530,
            width: 400,
            modal: true,
            buttons: {
                "Envoyer": function() {
                    $('#contact-form').submit();
                },
                "Annuler": function() {
                    $('#contact-form').find('input, textarea').each(function() {
                        $(this).val('');
                    });
                    $('#contact-message').remove();
                    $(this).dialog('close');
                }
            }
        });

        $("#contact").click(function() {
            $('#dialog-form').dialog('open');
        });
        $("#ADM_aide").click(function() {
            var wid = $(document).innerWidth() * 85 / 100;
            var hei = $(document).innerHeight() * 90 / 100;
            var map = window.open('aide.php', 'aide', 'width=' + wid + 'px, height=' + hei + 'px, top=5px, left=5px');
        });
    });
</script>