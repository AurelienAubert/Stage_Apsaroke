<?php
/*
 * Unnamed
 * 1    | CREPU | 25/06/2014    |   Creation
*/

include ("menu/menu_global.php");
require_once 'calendrier/fonction_nbjoursMois.php';
include 'calendrier/fonction_nomMois.php';
include ('inc/connection.php');
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Facture proforma</title>
        <style>
            //Cette classe hidden permet de cacher les �l�ments autant aux visiteurs qu'aux lecteurs d'�cran
            //De plus, ceci permet de ne prend aucun place � l'�cran
            .hidden { 
                position: absolute; 
                left: 0; 
                top: -10000px; 
                overflow: hidden;
                 } 
        </style>
    </head>
    <?php
    
    if(isset($_POST['Stocker']))
    {
        echo '<form id="mon_form" action="stock_proforma.php" method="post">';
    }
    elseif(isset($_POST['Generer']))
    {
        echo '<form id="mon_form" action="facture/imprime_FAC.php" method="post">';
    }
    if(isset($_POST['Generer']) || isset($_POST['Stocker']))
    {
        echo '<input name="FACT_ENT_NO" type="hidden" value="'.$_POST['FACT_ENT_NO'].'"/>';
        echo '<input name="FACT_BAN_NO" type="hidden" value="'.$_POST['FACT_BAN_NO'].'"/>';
        
        
        $tabNomVisible = explode('&shy', $_POST['ensNomVisible']);
        $tabNomInvisible = explode('&shy', $_POST['ensNomInvisible']);
                
        foreach($tabNomVisible as $nom)
        {
            echo '<input name="'.$nom.'" type="hidden" value="'.$_POST[$nom].'"/>';
        }
        
        foreach($tabNomInvisible as $nom)
        {
            echo '<input name="'.$nom.'" type="hidden" value="'.$_POST[$nom].'"/>';
        }
        if(isset($_POST['FACT_MODE_REG_1']) && isset($_POST['FACT_MODE_REG_2']))
        {
            echo '<input name="FACT_MODE_REG" type="textbox" value="0"/>';
        }
        elseif(isset($_POST['FACT_MODE_REG_1']))
        {
            echo '<input name="FACT_MODE_REG" type="textbox" value="1"/>';
        }
        elseif(isset($_POST['FACT_MODE_REG_2']))
        {
            echo '<input name="FACT_MODE_REG" type="textbox" value="2"/>';
        }
        
        $i = 2;
        while (isset($_POST['CELL'.$i.'0']))
        {
           echo '<input name="CELL'.$i.'0" type="hidden" value="'.$_POST['CELL'.$i.'0'].'"></input>';
           echo '<input name="CELL'.$i.'1" type="hidden" value="'.$_POST['CELL'.$i.'1'].'"></input>';
           echo '<input name="CELL'.$i.'2" type="hidden" value="'.$_POST['CELL'.$i.'2'].'"></input>';
            $i++;
        }
           
        echo '<input type="hidden" name="FACT_PENALITE" value="'.$_POST['FACT_PENALITE'].'"/>';
        echo '<input type="hidden" name="FACT_CON_VENTE" value="'.$_POST['FACT_CON_VENTE'].'"/>';
    ?>
    </form>
    <script type="text/javascript">
        document.getElementById('mon_form').submit();
    </script>
    <?php
    }
    else {
        //On r�cup�re la date courante
        $date = date('Y-m-');
        //On s�pare le mois et l'ann�e
        $tab = explode("-", $date);
        //$tab[1] == mois $tab[0] == annee
        //floatval == cast en float
        $nbJour = nbjoursMois(floatval($tab[1]), floatval($tab[0]));
        $date .= $nbJour;
        
        $moisCourant = date('m');
        $anneeCourant = date('Y');

        $num_col_pro = explode(".",$_POST['COL_NO']);
        //$num_col_pro[0] == collaborateur
        //$num_col_pro[1] == projet

        //Requ�te permettant d'afficher toutes les informations relatives au client concern�
        $query_client = 'SELECT * FROM CLIENT WHERE CLI_NO = ('
            .' SELECT CLI_NO FROM PROJET WHERE PRO_NO ="'.$num_col_pro[1].'");';
        $stmtCli = $GLOBALS['connexion']->query($query_client)->fetch_assoc();

        //Requ�te permettant d'afficher toutes les informations relatives du projet concern�
        $query_projet = 'SELECT * FROM PROJET WHERE PRO_NO = '.$num_col_pro[1];
        $stmtPro = $GLOBALS['connexion']->query($query_projet)->fetch_assoc();

        //Requ�te permettant d'afficher toutes les inforamtions du contact client s'occupant de se projet
        $query_contact = 'SELECT * FROM CONTACT_CLIENT WHERE CTC_NO = '.$stmtPro['CTC_NO'];
        $stmtContact = $GLOBALS['connexion']->query($query_contact);

        while($row_contact = $stmtContact->fetch_assoc())
        {
            //On r�cup�re le bon num�ro de contact client
            if($row_contact['CTC_NO'] == $stmtPro['CTC_NO'])
                break;
        }


        //On veut r�cup�rer la 1�re et derni�re lettre du mois et de l'ann�e courante pour le num�ro de facture
        $num�roFacture = $tab[0];
        $num�roFacture = substr($num�roFacture, 2,3);

        $leMois = nomMois(floatval($tab[1]));
        $length = strlen($leMois);
        $num�roFacture .= substr($leMois,0,1);
        $num�roFacture .= substr($leMois,$length-1,$length);
        $num�roFacture = strtoupper($num�roFacture);

        
        //On ne conserve que les 4�re lettres du nom du client avec son num�ro de client
        $codeClient = strtoupper(substr($stmtCli['CLI_CODE'],0,4).$stmtCli['CLI_NO']);

        //Requ�te permettant de savoir quelle personne suis ce projet
        $query_affaire_suivi = 'SELECT * FROM COLLABORATEUR WHERE COL_MNEMONIC = "'.$stmtPro['PRO_SUIVIPAR'].'"';
        $affaire_suivi = $GLOBALS['connexion']->query($query_affaire_suivi)->fetch_assoc();

        //On stock la civilit�, le prenom et le nom dans une variable
        $suivi_par = $affaire_suivi['COL_CIVILITE'].$affaire_suivi['COL_PRENOM'].' '.$affaire_suivi['COL_NOM'];

        //Requ�te permettant de savoir si un num�ro de facture existe ou non
        //Si ce n'est pas le cas, on met � 1 le num�ro de facture
        //Ceci n'est plus utile d�s lors qu'au moins 1 facture existe
        $query_facture = 'SELECT COUNT(*) FROM FACTURE WHERE FACT_ANNEE="'.$anneeCourant.'" AND FACT_MOIS="'.$moisCourant.'"';
        $stmtFac = $GLOBALS['connexion']->query($query_facture)->fetch_assoc();
        
        if($stmtFac['COUNT(*)'] == 0)
        {
            $num�roFacture .= '0';
        }
        else 
        {
            $num�roFacture .= $stmtFac['COUNT(*)'];
        }

        $query_entreprise = "SELECT * FROM ENTREPRISE";
        $stmtEnt = $GLOBALS['connexion']->query($query_entreprise);

        $query_banque = "SELECT * FROM BANQUE";
        $stmtBan = $GLOBALS['connexion']->query($query_banque);

        $query_conditon_general = "SELECT * FROM CONDITION_GENERAL";
        $stmtConGen = $GLOBALS['connexion']->query($query_conditon_general)->fetch_assoc();

        //Ordre : NOM | PRENOM | Facturable | Jour travaill� | Jour absent | 
        //jw we | jw samedi | jw dimanche | jw f�ri� | client | projet | tjm | 
        //pr�facturation | autre item(oui/non) | commentaire
        
        $tableau_designation = array(
        'Num�ro de commande :',
        'Num�ro de facture :',
        'Affaire suivi par :',
        'Date de facturation :',
        'P�riode :',
        'Code fournisseur :',
        'Nom client :',
        'Num�ro client',
        'Code client :',
        'Adresse client 1 :',
        'Adresse client 2 :',
        'Nom contact client :',
        'Pr�nom contact client :',
        'Nom du projet :',
        'D�tail du projet :',
        'Pr�facturation :',
        'Num�ro de contrat'
    );
        
        $listeVisible = array(
            $stmtPro['PRO_NUMCMDE'],
            $num�roFacture,
            $stmtPro['PRO_SUIVIPAR'],
            $date,
            $leMois,
            $stmtCli['CLI_CODE_FOUR'],
            $stmtCli['CLI_NOM'],
            $stmtCli['CLI_NO'],
            $codeClient,
            $stmtCli['CLI_ADRESSE_1'],
            $stmtCli['CLI_ADRESSE_2'],
            $row_contact['CTC_NOM'],
            $row_contact['CTC_PRENOM'],
            $stmtPro['PRO_NOM'],
            $stmtPro['PRO_DETAIL'],
            $_POST['PRO_TJM'],
            $stmtPro['PRO_NUM_CONTRAT']
        );
        
        $ensNomVisible = array(
            'FACT_PRO_NUMCMDE',
            'FACT_NUM',
            'FACT_PRO_SUIVIPAR',
            'FACT_DATE',
            'FACT_PERIODE',
            'FACT_CLI_CODE_FOUR',
            'FACT_CLI_NOM',
            'FACT_CLI_NO',
            'FACT_CLI_CODE',
            'FACT_CLI_ADRESSE_1',
            'FACT_CLI_ADRESSE_2',
            'FACT_CTC_NOM',
            'FACT_CTC_PRENOM',
            'FACT_PRO_NOM',
            'FACT_PRO_DETAIL',
            'FACT_PRO_TJM',
            'FACT_PRO_NUM_CONTRAT'
        );
           
        $listeInvisible = array(
            $stmtPro['PRO_SUIVIPAR'],
            $anneeCourant,
            $moisCourant,
            $stmtPro['PRO_NO'],
            $num_col_pro[0],
            $row_contact['CTC_NO'],
            'A modifier'
        );
        
        $ensNomInvisible = array(
            'FACT_MNEMO_COLLAB',
            'FACT_ANNEE',
            'FACT_MOIS',
            'FACT_PRO_NO',
            'FACT_COL_NO',
            'FACT_CTC_NO',
            'FACT_FOU_NO'
        );
        
        $listeNomVisible = '';
        $c = count($ensNomVisible);
        $i=1;
        foreach($ensNomVisible as $nom)
        {
            if($i == $c)
            {
                //On n'ajoute pas de "&shy" au dernier �l�ment, cela pose probl�me sinon
                $listeNomVisible .= $nom;
            }
            else
            {
                $listeNomVisible .= $nom.'&shy';
            } 
            $i++;
        }
        
        $listeNomInvisible = '';
        
        $c = count($ensNomInvisible);
        $i=1;
        foreach($ensNomInvisible as $nom)
        {
            if($i == $c)
            {
                //On n'ajoute pas de "&shy" au dernier �l�ment, cela pose probl�me sinon
                $listeNomInvisible .= $nom;
            }
            else
            {
                $listeNomInvisible .= $nom.'&shy';
            } 
            $i++;
        }
        //Idem
        
        ?>
        <body>
            <form name="pro_format_form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" onsubmit="return validate_form()">
                <fieldset>
                    <legend>Facture pro-format</legend>
                     <div class="container-fluid ">
                         <div class="row"><div class="span12"></br></div></div>
                            <div class="span2">Choix de l'entreprise :</div>
                                <div class="span2">
                                    <select name="FACT_ENT_NO">
                                        <?php
                                        while($row_entreprise = $stmtEnt->fetch_assoc())
                                        {
                                            echo '<option value="'.$row_entreprise['ENT_NO'].'">'.$row_entreprise['ENT_NOM'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                        <div class="span2 offset2">Choix de la banque :</div>
                           <div class="span2"> 
                                <select name="FACT_BAN_NO">
                                    <?php
                                    while($row_banque = $stmtBan->fetch_assoc())
                                    {
                                        echo '<option value="'.$row_banque['BAN_NO'].'">'.$row_banque['BAN_NOM'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php
                             $j=0;
                             foreach($listeVisible as $ligne)
                             {
                                 if($j%2 == 0){ 
                                     echo '<div class="row"><div class="span12"></br></div></div>'
                                     .'<div class="span2">'.$tableau_designation[$j].'</div>';
                                 }else{ 
                                     echo '<div class="span2 offset2">'.$tableau_designation[$j].'</div>';
                                 }
                                 
                                 echo '<div class="span2"><input name="'.$ensNomVisible[$j].'" value="'.$ligne.'"/></div>';
                                 $j++;
                             }
                             ?>

                            <div class="row"><div class="span12"><br></div></div>
                            <div class="span2">Condition de vente :</div>
                            <div class="span2"><textarea rows="10" type="text" name="FACT_CON_VENTE"><?php echo $stmtConGen['CON_VENTE'] ?></textarea></div>
                           
                            <div class="span2 offset2">P�nalit� :</div>
                            <div class="span2"><textarea rows="10" type="text" name="FACT_PENALITE"><?php echo $stmtConGen['CON_RETARD'] ?></textarea></div>
                            
                            
                            <div class="row"><div class="span12"><br></div></div>
                            <div class="span2">Mode de r�glement :</div>
                            <div class="span2"><input name="FACT_MODE_REG_1" type="checkbox">Virement</input></div>
                            <div class="span2"><input name="FACT_MODE_REG_2" type="checkbox">Ch�que</input></div>
                        </div>
                    <?php
                    $j=0;
                    foreach($listeInvisible as $ligne)
                    {
                        echo '<input class="hidden" name="'.$ensNomInvisible[$j].'" value="'.$ligne.'"/>';
                        $j++;
                    }
                    echo '<input class="hidden" name="ensNomVisible" value="'.$listeNomVisible.'"/>';
                    echo '<input class="hidden" name="ensNomInvisible" value="'.$listeNomInvisible.'"/>';
                    ?>
                    <fieldset>
                        <legend>Element(s) suppl�mentaire(s) � facturer</legend>
                        <table id='tableau0' border="1" class="table-bordered table-condensed" width="85%" align="center">
                            <tr>
                                <th>D�signation</th>
                                <th>D�tail projet</th>
                                <th>Montant � facturer</th>
                                <th><?php echo bouton('Ajouter une ligne', 'btn', 'primary','ajoutElement', 'ajout_element()'); ?></th>
                            </tr>
                        </table>
                    </fieldset>
                    </br>
                    <div class="text-center">
                        <input class="btn btn-primary" type="submit" value="Generer et stocker une facture officiel" name="Generer"/><i class="icon-ok"></i></input>
                        <input class="btn btn-primary" type="submit" value="Generer et stocker une facture au pro-format" name="Stocker"/><i class="icon-ok"></i></input>
                    </div>
                </fieldset>
            </form>
        <?php
        }
        function bouton($texte, $classe, $classeCouleur, $name, $fonction) {
                return '<input type="button" class="'.$classe.' '.$classeCouleur.'" name="'.$name.'" onclick="'.$fonction.'" value="'.$texte.'"/>';
            }
        ?>
        
    </body>
    <script type="text/javascript">
        function validate_form ( )
        {
            valid = true;
            
            if ( document.pro_format_form.FACT_MODE_REG_1.checked == false && document.pro_format_form.FACT_MODE_REG_2.checked == false)
            {
                alert ( "Veuillez cocher au moins un moyen de r�glement !" );
                valid = false;
            }


            return valid;
        } 
        
        var nbAjout = 0;
        
        function ajout_element()
        {
            nbAjout++;
            if(nbAjout > 5)
            {
                alert('Vous ne pouvez pas ajouter de ligne suppl�mentaire.');
            }
            else
            {
                var numRow = document.getElementById('tableau0').rows.length;

                var ajoutTable = document.getElementById('tableau0').insertRow(numRow);
    //            //Mise � jour du nombre de ligne
                numRow++;


                    var nbCell = 4;
                    var i = 0;

                    while(i < nbCell)
                    {
                        var cell = ajoutTable.insertCell(i);
                        switch(i)
                        {
                            case 0:
                                cell.innerHTML = '<td><input type="textbox" name="CELL'+numRow+i+'" /></td>';
                                break;
                            case 1:
                                cell.innerHTML = '<td><input type="textbox" name="CELL'+numRow+i+'" /></td>';
                                break;
                            case 2:
                                cell.innerHTML = '<td><input type="textbox" name="CELL'+numRow+i+'" /></td>';
                                break;
                        }
    //                    if(i == 13)
    //                    {
    //                        cell.innerHTML = '<input type="button" name"removeRow" onclick="supprimerLigne('+numRow+')" value="X" />';
    //                    }
                        i+=1;
                    }
                }
        }
    </script>
</html>