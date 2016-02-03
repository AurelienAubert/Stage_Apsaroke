<?php
if(!isset($_SESSION))
{
    session_start();
}

if ($_SESSION['accreditation'] == 4) {
    echo "<style>
    #CON{display: none}
    #RAM_edit{display: none}
    #RAM_print{display:none}
    #COL{display: none}
    #PRO{display: none}
    #CLI{display: none}
    #PAR{display: none}
    #CTA{display: none}
    #CTR{display: none}
    #AUT{display: none}
    #ADM_edit{display :none}
    #ADM_tr{display: none}
    #ADM_mod_conges{display: none}
    #ADM_suivi{display: none}
    #ADM_ens_collab{display: none}
    #ADM_prefac{display: none}
    #ADM_fac{display: none}
    #ADM_fac_pro_format{display: none}
    #ADM_export{display: none}
    #ADM_aide{display: none}
    #FRA{display: none}
    #AUT{display: none}
     </style>";
}
if ($_SESSION['accreditation'] == 3) {
    echo "<style>
    #RAM_tab{display: none}
    #RAM_imp{display: none}
    #RAM_sep{display: none}
    #CON_tab{display: none}
    #CON_val{display: none}
    #CON_mod{display: none}
    #CON_sep{display: none}
    #CON_imp{display: none}
    #RAM_tab{display: none}
    #COL{display: none}
    #PRO{display: none}
    #CLI{display: none}
    #PAR{display: none}
    #CTA{display: none}
    #CTR{display: none}
    #EBX{display: none}
    #AUT_cre{display: none}
    #CSV{display: none}
    #ADM{display: none}
    #AUT_moisprime{display: none}
    #FRA_val{display: none}
    #FRA_tab{display: none}
    #AUT_PRM{display: none}
    #AUT_EMAIL{display: none}
    #AUT_COUL{display: none}
    #AUT_LOGO{display: none}
    #AUT_DOCU{display: none}
    #LDO{display: none}
    </style>";
}
if ($_SESSION['accreditation'] == 2) {
    echo "<style>
    #AUT_cre{display: none}
    #AUT_moisprime{display: none}
    #ADM_mod_conges{display: none}
    #AUT_PRM{display: none}
    #AUT_EMAIL{display: none}
    #AUT_COUL{display: none}
    #AUT_LOGO{display: none}
    #AUT_DOCU{display: none}
    #ADM_export{display: none}
</style>";
}
if ($_SESSION['accreditation'] == 1) {
    echo "<style>
    #AUT_LOGO{display: none}
    #AUT_PRM{display: none}
</style>";
}
include 'menu/version.php';
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
        <h3 style="text-align:center;"><?php echo isset($GLOBALS['titre_page']) ? $GLOBALS['titre_page'] : '&nbsp;'; ?></h3>
    </div>

    <div class="navbar navbar-static not_printed">
        <div class="navbar-inner">
            <ul role="navigation" class="nav">
                <li><a href="accueil.php">Accueil</a></li>
                <li class="dropdown" id="CON">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Cong&eacutes<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="chx_date.php?type=dem_conge">Demander/Modifier un cong&eacutes</a></li> 
                        <li id="CON_val"><a href="choix_valid_conges.php">Valider une demande de cong&eacutes</a></li>
                        <li id="CON_listcol"><a href="chx_annee.php?type=tab_conges_coll">Liste des cong&eacutes</a></li>
                        <li id="CON_tab" ><a href="chx_date.php?type=tab_conges">Visualiser les cong&eacutes des Collaborateurs</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="RAM">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">RAM<b class="caret" ></b></a>
                    <ul class="dropdown-menu">
                        <li id="RAM_edit"><a href="chx_date.php?type=edit_ram">Voir/Modifier un RAM</a></li>
                        <li id="RAM_print"><a href="chx_date.php?type=print_ram">Imprimer un RAM</a></li>
                        <li id="RAM_tab"><a href="chx_date.php?type=tab_ram">Visualiser les RAM des Collaborateurs</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="FRA">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Frais<b class="caret" ></b></a>
                    <ul class="dropdown-menu">
                        <li id="FRA_urb"><a href="chx_date.php?type=frais_urb">Note de Frais Urbains</a></li>
                        <li id="FRA_reel"><a href="chx_date.php?type=frais_reel">Note de Frais R&eacuteels</a></li>
                        <li id="FRA_gd"><a href="chx_date.php?type=frais_gd">Note de Frais Grand D&eacuteplacement</a></li>
                        <li id="FRA_km"><a href="chx_date.php?type=frais_km">Note de Frais Kilom&eacutetriques</a></li>
                        <li id="FRA_val"><a href="choix_valid_frais.php">Valider une demande de frais</a></li>
                        <li id="FRA_tab"><a href="chx_date.php?type=tab_frais">Tableau des Frais par Collaborateur</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="COL">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Collaborateur<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="choix_reimpression.php">Ré-imprimer un document</a></li>
                        <li><a href="chx_type_collaborateur.php?action=ajout">Ajouter un collaborateur</a></li>
                        <li><a href="chx_type_collaborateur.php?action=affichage">Visualiser un collaborateur</a></li>
                        <li><a href="chx_type_collaborateur.php?action=modification">Modifier un collaborateur</a></li> 
                        <li><a href="chx_type_collaborateur.php?action=suppression">Supprimer un collaborateur</a></li> 
                    </ul>
                </li>
                <li class="dropdown" id="PRO">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Projet<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="ajout.php?type=projet">Saisir un projet</a></li>
                        <li><a href="recherche.php?type=projet&action=affichage">Afficher une fiche projet</a></li> 
                        <li><a href="recherche.php?type=projet&action=modification">Modifier une fiche projet</a></li>
                        <li><a href="suppression.php?type=projet">Supprimer un projet</a></li> 
                    </ul>
                </li>
                <li class="dropdown" id="CLI">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Client<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="ajout.php?type=client">Saisir un client</a></li>
                        <li><a href="recherche.php?type=client&action=affichage">Afficher une fiche client</a></li> 
                        <li><a href="recherche.php?type=client&action=modification">Modifier une fiche client</a></li>
                        <li><a href="suppression.php?type=client">Supprimer une fiche client</a></li> 
                        <li id="CTA" class="dropdown-submenu">
                            <a href="#">Contact client<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="ajout.php?type=contact_client">Saisir un contact</a></li>
                                <li><a href="recherche.php?type=contact_client&action=affichage">Afficher un contact</a></li> 
                                <li><a href="recherche.php?type=contact_client&action=modification">Modifier un contact</a></li>
                                <li><a href="suppression.php?type=contact_client">Supprimer un contact</a></li> 
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown" id="PAR">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Fournisseurs<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="ajout.php?type=fournisseur">Saisir un fournisseur</a></li>
                        <li><a href="recherche.php?type=fournisseur&action=affichage">Afficher une fiche fournisseur</a></li> 
                        <li><a href="recherche.php?type=fournisseur&action=modification">Modifier une fiche fournisseur</a></li>
                        <li><a href="suppression.php?type=fournisseur">Supprimer une fiche fournisseur</a></li>  
                        <li id="CTR" class="dropdown-submenu">
                            <a href="#">Contact fournisseur<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="ajout.php?type=contact_fournisseur">Saisir un contact</a></li>
                                <li><a href="recherche.php?type=contact_fournisseur&action=affichage">Afficher un contact</a></li> 
                                <li><a href="recherche.php?type=contact_fournisseur&action=modification">Modifier un contact</a></li>
                                <li><a href="suppression.php?type=contact_fournisseur">Supprimer un contact</a></li> 
                            </ul>
                        </li>
                    </ul>
                </li>
<!--                <li class="dropdown" id="CTA">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Contact client<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="ajout.php?type=contact_client">Saisir un contact</a></li>
                        <li><a href="recherche.php?type=contact_client&action=affichage">Afficher un contact</a></li> 
                        <li><a href="recherche.php?type=contact_client&action=modification">Modifier un contact</a></li>
                        <li><a href="suppression.php?type=contact_client">Supprimer un contact</a></li> 
                    </ul>
                </li>
                <li class="dropdown" id="CTR">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Contact fournisseur<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="ajout.php?type=contact_fournisseur">Saisir un contact</a></li>
                        <li><a href="recherche.php?type=contact_fournisseur&action=affichage">Afficher un contact</a></li> 
                        <li><a href="recherche.php?type=contact_fournisseur&action=modification">Modifier un contact</a></li>
                        <li><a href="suppression.php?type=contact_fournisseur">Supprimer un contact</a></li> 
                    </ul>
                </li>-->
                <li class="dropdown" id="ADM">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Administration<b class="caret"></b></a>
                    <ul class="dropdown-menu">
<!--                        <li id="ADM_edit"><a href="prepaie.php">Editer une fiche Pr&eacute-Paie</a></li>-->
                        <li id="ADM_edit"><a href="chx_date.php?type=edit_prepaie">Editer une fiche Pr&eacute-Paie</a></li>
                        <li id="ADM_visu"><a href="chx_date.php?type=visu_prepaie">Visualiser une fiche Pr&eacute-Paie</a></li>
                        <li id="ADM_prefac"><a href="chx_date.php?type=prefacturation">Editer une fiche de pr&eacutefacturation</a></li>
                        <li id="ADM_fac"><a href="chx_date.php?type=facturation">Afficher/Imprimer une facture</a></li>
                        <!--<li id="ADM_fac_pro_format"><a href="chx_date.php?type=facturation_proforma">Afficher/Imprimer une facture au proforma</a></li>-->
                        <li id="ADM_tr"><a href="chx_date.php?type=tr">R&eacutecapitulatif Tickets Restaurant</a></li> 
                        <li id="ADM_suivi"><a href="chx_date.php?type=suivi">Suivi mission</a></li> 
                        <li id="ADM_ens_collab"><a href="tous_les_collab.php">Ensemble des collaborateurs</a></li> 
                        <li id="ADM_mod_conges"><a href="modifier_conges.php?">Invalider des cong&eacutes</a></li>
                        <li id="ADM_export"><a href="aide/ExportBBD.pdf">Sauvegarde de la base</a></li>
                        <li id="ADM_aide"><a href="aide.php">Aide</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="AUT">
                    <a data-target="#" href="#" data-toggle="dropdown" class="dropdown-toggle">Param&egravetres<b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                        <li id="AUT_cre"><a href="creation_login_pwd.php">Cr&eacuteer un login/mot de passe pour un collaborateur</a></li>
                        <li id="AUT_mod"><a href="modification_mdp.php">Modifier un login/mot de passe pour un collaborateur</a></li>
                        <li id="CSV"><a href="chx_date.php?type=csv">Export CSV</a></li>
                        <li id="AUT_PRM"><a href="recapitulatif_parametre.php">Param&eacutetrage</a></li>
                        <li id="AUT_COUL"><a href="couleur.php">Param&eacutetrage des couleurs</a></li>
                        <li id="AUT_EMAIL"><a href="config_email.php">Param&eacutetrage de l'e-mail</a></li>
                        <li id="AUT_moisprime"><a href="param_mois_prime.php">Param&eacutetrage prime anciennet&eacute</a></li>
                        <li id="AUT_LOGO"><a href="param_logo.php">Param&eacutetrage des logo</a></li>
                        <li id="AUT_DOCU"><a href="rechercheLibDoc.php">Param&eacutetrage des documents</a></li>
                        <li class="dropdown-submenu" id="EBX">
                            <a href="#">Entreprise/Banque<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li id="EBX_ajout_entreprise"><a href="ajout.php?type=entreprise">Ajouter une entreprise</a></li>
                                <li id="EBX_modif_entreprise"><a href="recherche.php?type=entreprise&action=affichage">Afficher/Modifier une entreprise</a></li>
                                <li id="EBX_supp_entreprise"><a href="suppression.php?type=entreprise">Suppression d'une entreprise</a></li>
<!--                                <li id="EBX_ajout_banque"><a href="ajout_banque.php?">Ajouter une banque</a></li>
                                <li id="EBX_affich_modif_banque"><a href="affich_modif_banque.php">Afficher/Modifier une banque</a></li>-->
                                <li id="EBX_ajout_banque"><a href="ajout.php?type=banque">Ajouter une banque</a></li>
                                <li id="EBX_affich_modif_banque"><a href="recherche.php?type=banque&action=affichage">Afficher/Modifier une banque</a></li>
                                <li id="EBX_supp_entreprise"><a href="suppression.php?type=banque">Suppression d'une banque</a></li>
                            </ul>
                        </li>
                        <li id="AUT_FNCT" class="dropdown-submenu">
                            <a href="#">Gestion des fonctions<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="ajout.php?type=fonction">Saisir une fonction</a></li>
                                <li><a href="recherche.php?type=fonction&action=affichage">Afficher une fonction</a></li> 
                                <li><a href="recherche.php?type=fonction&action=modification">Modifier une fonction</a></li>
                                <li><a href="suppression.php?type=fonction">Supprimer une fonction</a></li> 
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="nav pull-right">
                <li><a href="deconnexion.php"><i class="icon-remove"></i> D&eacuteconnexion</a></li>
            </ul>
            <ul class="nav pull-right">
                <li><a href="#" id="contact"><i class="icon-envelope"></i> Contact</a></li>
            </ul>
        </div>
    </div>
<!--    <div id="reponseInfoMessage" style="display:block; text-align: center; color: green; ">
    </div>-->
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

<script src="jquery.iframe-post-form.js"></script>
<script>
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
                $('.').css('margin-top', $('.header').height() + 5);
            }
        }

        var media_query = window.matchMedia("print");
        media_query.addListener(listener);
        listener(media_query);

        $(window).resize(function() {
            if (media_type != 'print') {
                $('.').css('margin-top', $('.header').height() + 5);
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
    });
</script>