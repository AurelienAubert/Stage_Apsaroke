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
        <p class="menuaide"><A class="menuaide" HREF="#conge">Demander/modifier un cong�</p></A>
        <p class="menuaide"><A class="menuaide" HREF="#ram">Saisir/modifier un RAM</p></A>
        <p class="menuaide"><A class="menuaide" HREF="#frais">Saisir les frais mensuels</p></A>

<?php
if ($accred < 4) {
?>
        <p class="menuaide"><A class="menuaide" HREF="#invconge">Invalider les cong�s valid�s d'un collaborateur</p></A>
        <p class="menuaide"><A class="menuaide" HREF="#modconge">Modifier les cong�s valid�s d'un collaborateur</p></A>
<?php
}
?>
        <p><A NAME="conge"></A><h3>Proc�dure pour saisir les cong�s d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <ul>
        <li>
            <h4>Pour saisir vos cong�s, il faut :</h4>
            <ol>
                <li>Se rendre dans l'onglet "Cong�s" puis choisir l'option "Demander/modifier un cong�".</li>
                <li>Choisir une ann�e et un mois (date courante par d�faut) puis cliquer sur "Continuer".</li>
                <li>Le tableau central affiche les jours du mois et l'�tat des cong�s d�j� saisi <em id="jrsmois" class="lienimage">(voir le tableau)</em>.<br />
                Les samedis, dimanches et jours f�ri�s sont gris�s et non disponibles.<br />
                Le tableau � droite affiche les options couleurs de chaque type de cong� et de leur �tat de validation.<br />
                Une liste "Choix de type de cong�s" permet de s�lectionner le type de cong� � saisir <em id="choixconge" class="lienimage">(voir le tableau)</em>.<br />
                <li>Pour faire la demande, il faut cliquer sur le jour concern� :</li>
                    <ul>
                        <li>Un clic s�lectionne la journ�e compl�te,</li>
                        <li>Un 2�me clic s�lectionne une demi-journ�e,</li>
                        <li>Un 3�me clic d�-s�lectionne la journ�e.</li>
                    </ul>
                <li>Vous pouvez modifier les jours s'ils sont encore � l'�tat de demande.</li>
                <li>Pour finir, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra un mail d'avertissement.</li>
            </ol>
            </li>
        </ul>

        <p><A NAME="ram"></A><h3>Proc�dure pour saisir/modifier un RAM</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <ul>
            <li>
            <h4>Si vous n'avez pas encore saisi votre RAM</h4>
                <ol>
                    <li>Se rendre dans l'onglet "RAM" puis choisir "Voir/Modifier un RAM".</li>
                    <li>Choisir une ann�e et un mois (date courante par d�faut) puis cliquer sur "Continuer".</li>
                    <li>Deux choix sont possibles ici :
                        <ul>
                            <li>Vous n'avez besoin de choisir qu'un seul client et un seul projet.<br />
                            Vous cliquez donc sur le bouton "Continuer" une fois votre choix fait.</li>
                            <li>Vous avez besoin de saisir plusieurs clients et projets.<br />
                            Vous devez donc cliquez sur "Ajouter un client", une fois que vous avez s�lectionnez un premier client et projet.<br />
                            Deux nouvelles listes d�roulantes (client et projet) sont disponibles � chaque fois que vous appuyez sur 
                            "Ajouter un client"<br /> (Si vous avez choisis un client et un projet �videmment).<br />
                            Cliquez sur le bouton "Continuer" une fois votre choix fait.</li>
                            <li>NB : Vous pouvez ajouter autant de client et projet que vous voulez � votre RAM.</li>
                        </ul>
                    </li>
                    <li>Vous voil� sur votre RAM. Ce dernier dispose d'autant de ligne que de client/projet choisi. Si vous avez plus d'un
                    client,<br /> vous pouvez modifier les valeurs de jours travaill�s pour qu'ils correspondent � votre travail effectu�. </li>
                    <li>Pour finir, une fois que tout vous semble en ordre, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra vore RAM.</li>
                </ol>

            </li>
            <li>
            <h4>Si vous avez d�j� saisi votre RAM</h4>
                 <ol>
                    <li>Se rendre dans l'onglet "RAM" puis choisir "Voir/Modifier un RAM".</li>
                    <li>Choisir une ann�e et un mois (date courante par d�faut) puis cliquer sur "Continuer".</li>
                    <li>Deux cas sont possibles ici :
                        <ul>
                            <li>Votre RAM a d�j� �t� valid� par l'administration<br />
                            Vous ne pouvez que modifier votre RAM si vous avez plus d'un client dans votre RAM.
                            <br />Si ce n'est pas le cas et qu'une erreur est pr�sente. Contactez l'administration</li>
                            <li>Votre RAM n'a pas encore �t� valid� par l'administration<br />
                            Suivez la proc�dure standard (choix d'une ann�e, d'un mois).
                            Vous arriverez sur une page o� le(s) client(s)/projet(s) sont d�j� pr�s�lectionn�s dans les listes d�roulantes.
                            Une fois de plus, suivez la proc�dure standard.
                        </ul>
                    </li>
                    <li>Vous voil� sur votre RAM. Ce dernier dispose d'autant de ligne que de client/projet choisi. Si vous avez plus d'un
                    client,<br /> vous pouvez modifier les valeurs de jours travaill�s pour qu'ils correspondent � votre travail effectu�. </li>
                    <li>Pour finir, une fois que tout vous semble en ordre, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra un mail d'avertissement.</li>
                </ol>
            </li>
        </ul>
        
        <p><A NAME="frais"></A><h3>Proc�dure pour saisir les frais mensuels d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <ul>
        <li>
            <h4>Pour saisir vos frais, il faut :</h4>
            <ol>
                <li>Se rendre dans l'onglet "Frais" puis s�lectionner une options :
                    <ul>
                        <li>Frais urbains : les frais au forfait suivant le nombre de jours travaill�s,</li>
                        <li>Frais r�els : vos notes de frais avec justificatifs,</li>
                        <li>Frais grand d�placement : les frais occasionn� par un grand d�placement,</li>
                        <li>Frais kilom�triques : les frais pour une distance parcourue.</li>
                    </ul>
                </li>
                <li>Choisir une ann�e et un mois (date courante par d�faut) puis cliquer sur "Continuer".</li>
                <h4>Si vous saisissez des frais urbains</h4>
                <ol>
                    <li>Vous devez d�j� avoir saisi votre RAM indiquant le nombre de jours travaill�s.</li>
                    <li>Un tableau affiche ce nombre de jours, votre forfait journalier, et le montant total de vos frais mensuel.</li>
                </ol>
                <h4>Si vous saisissez des frais r�els</h4>
                <ol>
                    <li>Un tableau permet de saisir vos frais par poste <em id="fraisreels" class="lienimage">(voir le tableau)</em>.</li>
                    <li>Pour un jour donn�, vous devez saisir l'objet, le montant de TVA et le montant total obligatoirement.<br />
                        Le no de facture est facultatif.
                        Le montant HT est calcul� automatiquement.</li>
                    <li>Vous pouvez ajouter des lignes de frais (bouton "Ajouter une ligne") et supprimer une ligne d�j� saisie.</li>
                </ol>
                <h4>Si vous saisissez des frais grand d�placement</h4>
                <ol>
                    <li>Un tableau permet de saisir vos frais par d�placement <em id="fraisGD" class="lienimage">(voir le tableau)</em>.</li>
                    <li>La saisie de l'objet, du nombre de jours et du montant total est obligatoire.<br />
                        Le jour et le d�tail sont facultatifs.</li>
                    <li>Vous pouvez ajouter des lignes de frais (bouton "Ajouter une ligne") et supprimer une ligne d�j� saisie.</li>
                </ol>
                <h4>Si vous saisissez des frais kilom�triques</h4>
                <ol>
                    <li>Un tableau permet de saisir vos frais de parcours <em id="fraiskm" class="lienimage">(voir le tableau)</em>.</li>
                    <li>La saisie du client, de la ville, des kilom�tres parcourus et du taux est obligatoire.<br />
                        La date est facultative.</li>
                    <li>Vous pouvez ajouter des lignes de frais (bouton "Ajouter une ligne") et supprimer une ligne d�j� saisie.</li>
                </ol>
                <li>Pour finir, cliquez sur "Envoyer" et l'administration d'Apsaroke recevra un mail d'avertissement.</li>
            </ol>
        </li>
        </ul>
<?php
if ($accred < 4) {
?>
        <p><A NAME="invconge"></A><h3>Proc�dure pour invalider les cong�s valid�s d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <p>Pour invalider des cong�s, il faut :
            <ol>
                <li>Se rendre dans l'onglet "Administration" puis choisir l'onglet "Invalider les cong�s".</li>
                <li>Une fois sur cet onglet, on s�lectionne le collaborateur voulu, l'ann�e et le mois.</li>
                <li>Cliquer sur le bouton "Continuer" pour invalider tous les cong�s pour la date donn�e.</li>
            </ol>
        </p>
        <p><A NAME="modconge"></A><h3>Proc�dure pour modifier les cong�s valid�s d'un collaborateur</h3>
            <A HREF="#haut"><h5 class="nav pull-right">Haut de page</h5></A>
        </p>
        <p>Pour modifier les cong�s, il faut :
            <ol>
                <li>Se rendre dans l'onglet "Visualiser les cong�s des collaborateurs".</li>
                <li>Choisir une ann�e, un mois et cliquer sur le bouton "Valider".</li>
                <li>Sur la page des collaborateurs, on choisit un collaborateur en cliquant sur le bouton "G�r�r".</li>
                <li>Il suffit d�sormais de choisir les jours de cong�s en cliquant sur les cases du calendrier.</li>
            </ol>
        </p>
        <h3>NB : Sigle "AM" signifie demi-journ�e</h3>
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