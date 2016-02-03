<?php 
require "inc/verif_session.php"; 
$accred = $_SESSION['accreditation'];

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Aide</title>
        <style>
            h1
            {
                text-align: center;
                color: #FFAA25;
            }
            h2,h3
            {
                color: #FF4400;
            }
            .menuaide{
                line-height: 10px;
                color: darkorchid;
            }
            .lienimage{
                color: navy;
                text-decoration: underline;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div id="corps" style="margin-left: 20px;">

        <A NAME="haut"></A><h1>Fiche d'aide</h1>
        <p></p>
        <p class="menuaide"><A class="menuaide" HREF="#conge">Demander/modifier un congé</p></A>
        <p class="menuaide"><A class="menuaide" HREF="#ram">Saisir/modifier un RAM</p></A>
        <p class="menuaide"><A class="menuaide" HREF="#frais">Saisir les frais mensuels</p></A>

<?php
if ($accred < 4) {
?>
        <p class="menuaide"><A class="menuaide" HREF="#invconge">Invalider les congés validés d'un collaborateur</p></A>
        <p class="menuaide"><A class="menuaide" HREF="#modconge">Modifier les congés validés d'un collaborateur</p></A>
<?php
}
?>
        <p><A NAME="conge"></A><h3>Procédure pour saisir les congés d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <ul>
        <li>
            <h4>Pour saisir vos congés, il faut :</h4>
            <ol>
                <li>Se rendre dans l'onglet "Congés" puis choisir l'option "Demander/modifier un congé".</li>
                <li>Choisir une année et un mois (date courante par défaut) puis cliquer sur "Continuer".</li>
                <li>Le tableau central affiche les jours du mois et l'état des congés déjà saisi <em id="jrsmois" class="lienimage">(voir le tableau)</em>.<br />
                Les samedis, dimanches et jours fériés sont grisés et non disponibles.<br />
                Le tableau à droite affiche les options couleurs de chaque type de congé et de leur état de validation.<br />
                Une liste "Choix de type de congés" permet de sélectionner le type de congé à saisir <em id="choixconge" class="lienimage">(voir le tableau)</em>.<br />
                <li>Pour faire la demande, il faut cliquer sur le jour concerné :</li>
                    <ul>
                        <li>Un clic sélectionne la journée complète,</li>
                        <li>Un 2ème clic sélectionne une demi-journée,</li>
                        <li>Un 3ème clic dé-sélectionne la journée.</li>
                    </ul>
                <li>Vous pouvez modifier les jours s'ils sont encore à l'état de demande.</li>
                <li>Pour finir, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra un mail d'avertissement.</li>
            </ol>
            </li>
        </ul>

        <p><A NAME="ram"></A><h3>Procédure pour saisir/modifier un RAM</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <ul>
            <li>
            <h4>Si vous n'avez pas encore saisi votre RAM</h4>
                <ol>
                    <li>Se rendre dans l'onglet "RAM" puis choisir "Voir/Modifier un RAM".</li>
                    <li>Choisir une année et un mois (date courante par défaut) puis cliquer sur "Continuer".</li>
                    <li>Deux choix sont possibles ici :
                        <ul>
                            <li>Vous n'avez besoin de choisir qu'un seul client et un seul projet.<br />
                            Vous cliquez donc sur le bouton "Continuer" une fois votre choix fait.</li>
                            <li>Vous avez besoin de saisir plusieurs clients et projets.<br />
                            Vous devez donc cliquez sur "Ajouter un client", une fois que vous avez sélectionnez un premier client et projet.<br />
                            Deux nouvelles listes déroulantes (client et projet) sont disponibles à chaque fois que vous appuyez sur 
                            "Ajouter un client"<br /> (Si vous avez choisis un client et un projet évidemment).<br />
                            Cliquez sur le bouton "Continuer" une fois votre choix fait.</li>
                            <li>NB : Vous pouvez ajouter autant de client et projet que vous voulez à votre RAM.</li>
                        </ul>
                    </li>
                    <li>Vous voilà sur votre RAM. Ce dernier dispose d'autant de ligne que de client/projet choisi. Si vous avez plus d'un
                    client,<br /> vous pouvez modifier les valeurs de jours travaillés pour qu'ils correspondent à votre travail effectué. </li>
                    <li>Pour finir, une fois que tout vous semble en ordre, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra vore RAM.</li>
                </ol>

            </li>
            <li>
            <h4>Si vous avez déjà saisi votre RAM</h4>
                 <ol>
                    <li>Se rendre dans l'onglet "RAM" puis choisir "Voir/Modifier un RAM".</li>
                    <li>Choisir une année et un mois (date courante par défaut) puis cliquer sur "Continuer".</li>
                    <li>Deux cas sont possibles ici :
                        <ul>
                            <li>Votre RAM a déjà été validé par l'administration<br />
                            Vous ne pouvez que modifier votre RAM si vous avez plus d'un client dans votre RAM.
                            <br />Si ce n'est pas le cas et qu'une erreur est présente. Contactez l'administration</li>
                            <li>Votre RAM n'a pas encore été validé par l'administration<br />
                            Suivez la procédure standard (choix d'une année, d'un mois).
                            Vous arriverez sur une page où le(s) client(s)/projet(s) sont déjà présélectionnés dans les listes déroulantes.
                            Une fois de plus, suivez la procédure standard.
                        </ul>
                    </li>
                    <li>Vous voilà sur votre RAM. Ce dernier dispose d'autant de ligne que de client/projet choisi. Si vous avez plus d'un
                    client,<br /> vous pouvez modifier les valeurs de jours travaillés pour qu'ils correspondent à votre travail effectué. </li>
                    <li>Pour finir, une fois que tout vous semble en ordre, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra un mail d'avertissement.</li>
                </ol>
            </li>
        </ul>
        
        <p><A NAME="frais"></A><h3>Procédure pour saisir les frais mensuels d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <ul>
        <li>
            <h4>Pour saisir vos frais, il faut :</h4>
            <ol>
                <li>Se rendre dans l'onglet "Frais" puis sélectionner une options :
                    <ul>
                        <li>Frais urbains : les frais au forfait suivant le nombre de jours travaillés,</li>
                        <li>Frais réels : vos notes de frais avec justificatifs,</li>
                        <li>Frais grand déplacement : les frais occasionné par un grand déplacement,</li>
                        <li>Frais kilométriques : les frais pour une distance parcourue.</li>
                    </ul>
                </li>
                <li>Choisir une année et un mois (date courante par défaut) puis cliquer sur "Continuer".</li>
                <h4>Si vous saisissez des frais urbains</h4>
                <ol>
                    <li>Vous devez déjà avoir saisi votre RAM indiquant le nombre de jours travaillés.</li>
                    <li>Un tableau affiche ce nombre de jours, votre forfait journalier, et le montant total de vos frais mensuel.</li>
                </ol>
                <h4>Si vous saisissez des frais réels</h4>
                <ol>
                    <li>Un tableau permet de saisir vos frais par poste <em id="fraisreels" class="lienimage">(voir le tableau)</em>.</li>
                    <li>Pour un jour donné, vous devez saisir l'objet, le montant de TVA et le montant total obligatoirement.<br />
                        Le no de facture est facultatif.
                        Le montant HT est calculé automatiquement.</li>
                    <li>Vous pouvez ajouter des lignes de frais (bouton "Ajouter une ligne") et supprimer une ligne déjà saisie.</li>
                </ol>
                <h4>Si vous saisissez des frais grand déplacement</h4>
                <ol>
                    <li>Un tableau permet de saisir vos frais par déplacement <em id="fraisGD" class="lienimage">(voir le tableau)</em>.</li>
                    <li>La saisie de l'objet, du nombre de jours et du montant total est obligatoire.<br />
                        Le jour et le détail sont facultatifs.</li>
                    <li>Vous pouvez ajouter des lignes de frais (bouton "Ajouter une ligne") et supprimer une ligne déjà saisie.</li>
                </ol>
                <h4>Si vous saisissez des frais kilométriques</h4>
                <ol>
                    <li>Un tableau permet de saisir vos frais de parcours <em id="fraiskm" class="lienimage">(voir le tableau)</em>.</li>
                    <li>La saisie du client, de la ville, des kilomètres parcourus et du taux est obligatoire.<br />
                        La date est facultative.</li>
                    <li>Vous pouvez ajouter des lignes de frais (bouton "Ajouter une ligne") et supprimer une ligne déjà saisie.</li>
                </ol>
                <li>Pour finir, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra un mail d'avertissement.</li>
            </ol>
        </li>
        </ul>
<?php
if ($accred < 4) {
?>
        <p><A NAME="invconge"></A><h3>Procédure pour invalider les congés validés d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <p>Pour invalider des congés, il faut :
            <ol>
                <li>Se rendre dans l'onglet "Administration" puis choisir l'onglet "Invalider les congés".</li>
                <li>Une fois sur cet onglet, on sélectionne le collaborateur voulu, l'année et le mois.</li>
                <li>Cliquer sur le bouton "Continuer" pour invalider tous les congés pour la date donnée.</li>
            </ol>
        </p>
        <p><A NAME="modconge"></A><h3>Procédure pour modifier les congés validés d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <p>Pour modifier les congés, il faut :
            <ol>
                <li>Se rendre dans l'onglet "Visualiser les congés des collaborateurs".</li>
                <li>Choisir une année, un mois et cliquer sur le bouton "Valider".</li>
                <li>Sur la page des collaborateurs, on choisit un collaborateur en cliquant sur le bouton "Gérér".</li>
                <li>Il suffit désormais de choisir les jours de congés en cliquant sur les cases du calendrier.</li>
            </ol>
        </p>
        <h3>NB : Sigle "AM" signifie demi-journée</h3>
<?php
}
?>
        </div>
    </body>
    <script>
        $("#jrsmois").click(function() {
            window.open('aide/jrsmois.png', 'jrsmois', 'width=400px, height=200px, top=30px, left=50px, location=no');
        });
        $("#choixconge").click(function() {
            window.open('aide/choixconge.png', 'choixconge', 'width=400px, height=200px, top=30px, left=50px, location=no');
        });
        $("#fraisreels").click(function() {
            window.open('aide/fraisreels.png', 'fraisreels', 'width=1000px, height=200px, location=no');
        });
        $("#fraisGD").click(function() {
            window.open('aide/fraisGD.png', 'fraisGD', 'width=1000px, height=200px, location=no');
        });
        $("#fraiskm").click(function() {
            window.open('aide/fraiskm.png', 'fraiskm', 'width=1000px, height=200px, location=no');
        });
    </script>
</html>