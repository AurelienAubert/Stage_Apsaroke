<?php
include 'inc/connection.php';
include_once 'calendrier/fonction_nomMois.php';
include_once 'calendrier/fonction_nbjoursMois.php';

function bouton2($texte, $classe, $classeCouleur, $name, $fonction) {
    return '<input type="button" class="' . $classe . ' ' . $classeCouleur . '" name="' . $name . '" onclick="' . $fonction . '" value="' . $texte . '"/>';
}
$action = '';
if(isset($_GET['action']) && $_GET['action'] == 'avoir'){
    $action = $_GET['action'];
}
// Si préparation d'un proforma
if(!isset($_POST['recherche'])){
    // No de Proforma : année (sur 2) + Mnemo du mois(1ère et dernière lettre du mois en majuscule)
    $FAC_DEV = date('y') . MnemoMois($_POST['mois']);

    //echo $FAC_DEV . " " . $_POST['COL_NO'];
    //$_POST['COL_NO'] contient le numéro de collaborateur et le numéro de projet
    $num_col_pro = explode(".", $_POST['COL_NO']);        

    //Requête permettant d'afficher toutes les informations relatives du projet concerné
    $query_projet = 'SELECT * FROM PROJET P, MISSION M WHERE P.PRO_NO = M.PRO_NO AND MIS_ARCHIVE = 0 AND P.PRO_NO = '.$num_col_pro[1];
    $pro_row = $GLOBALS['connexion']->query($query_projet)->fetch_assoc();

    //Requête permettant d'afficher toutes les informations relatives au client concerné
    $query_client = 'SELECT * FROM CLIENT WHERE CLI_NO = ' . $pro_row['CLI_NO'];
    $cli_row = $GLOBALS['connexion']->query($query_client)->fetch_assoc();

    //Requête permettant d'afficher toutes les informations du contact client s'occupant de ce projet
    $query_contact = 'SELECT * FROM CONTACT_CLIENT WHERE CTC_NO = ' . $pro_row['CTC_NO'];
    $ctc = $GLOBALS['connexion']->query($query_contact)->fetch_assoc();

    //Requête permettant de savoir quelle personne suit ce projet
    $query_affaire_suivi = 'SELECT * FROM COLLABORATEUR WHERE COL_NO = "'.$pro_row['PRO_SUIVIPAR'].'"';
    $com_row = $GLOBALS['connexion']->query($query_affaire_suivi)->fetch_assoc();

    //Requête pour le collaborateur ayant travaillé sur ce projet
    $query_col = 'SELECT * FROM COLLABORATEUR WHERE COL_NO = "'.$num_col_pro[0].'"';
    $col_row = $GLOBALS['connexion']->query($query_col)->fetch_assoc();

    $tva_row = $GLOBALS['connexion']->query('SELECT * FROM PARAMETRE WHERE PAR_LIBELLE ="TVA"')->fetch_assoc();

    $FAC_DEV .= $col_row['COL_MNEMONIC'] . strtoupper(substr(str_replace('é', 'e', str_replace('è', 'e', $cli_row['CLI_NOM'])), 0, 3));
    $FAC_PERIODE = nomMois($_POST['mois']);
    $FAC_ANNEE = $_POST['annee'];
    $FAC_MOIS = $_POST['mois'];
    $FAC_ELEM = $_POST['PRO_PREFAC'];
    $FAC_TOTAL_HT = $_POST['PRO_PREFAC'];
    $ENT_NO = 0;
    $BAN_NO = 0;

    $COL_NO = $col_row['COL_NO'];
    $COL_NOM = $col_row['COL_PRENOM'] . " " . $col_row['COL_NOM'];
    $CLI_NO = $cli_row['CLI_NO'];
    $ENT_NO = 0;
    $BAN_NO = 0;
    $FAC_CODFOU = $cli_row['CLI_CODE_FOUR'];
    $PRO_NO = $pro_row['PRO_NO'];
    $CTC_NO = $ctc['CTC_NO'];
    $FAC_ANNNE = $_POST['annee'];
    $FAC_MOIS = $_POST['mois'];
    
    $date = $_POST['annee'].'-'.$_POST['mois'].'-';
    
    $nbJour = nbjoursMois($_POST['mois'], $_POST['annee']);
    //Obtenir le dernier vendredi d'un mois (cas non pris en compte : Jour férié)
    $t = 0;
    do{
        $t = check_jour($_POST['mois'], $nbJour, $_POST['annee']);
        echo $nbJour;
        echo $t;
        if($t == 0 || $t == 6){
            $nbJour--;
        }
    }while($t == 0 || $t== 6);
    
    $FAC_DATE = $date.$nbJour;
    $FAC_PERIODE = nomMois($_POST['mois']);
    $FAC_SUIVIPAR = $com_row['COL_NO'];
    $FAC_MODE_REG = 0;
    $FAC_NOMCOM = $com_row['COL_PRENOM'] . " " . $com_row['COL_NOM'];
    $FAC_CODCLI = $cli_row['CLI_CODE'];
    $FAC_NOMCLI = $cli_row['CLI_NOMFAC'];
    $FAC_ADR1 = $cli_row['CLI_ADRFAC_1'];
    $FAC_ADR2 = $cli_row['CLI_ADRFAC_2'];
    $FAC_CP = $cli_row['CLI_CPFAC'];
    $FAC_VILLE = $cli_row['CLI_VILLEFAC'];
    $FAC_NOMCTC = $ctc['CTC_PRENOM'] . " " . $ctc['CTC_NOM'];
    $FAC_NOMPRO = $pro_row['PRO_NOM'];
    $FAC_PRODETAIL = $pro_row['PRO_DETAIL'];
    $FAC_NUMCMDE = $pro_row['PRO_NUMCMDE'];

    $TAUX_TVA = $tva_row['PAR_VALEUR'] * 100 ;
   
} else {
    
    $query = "SELECT * FROM FACTURE WHERE FAC_NO = '" . $_POST['recherche'] . "'";
    $row = $GLOBALS['connexion']->query($query)->fetch_assoc();
    $array_sup = array();
    $i = 1;
    while (isset($row['FAC_ELEM_SUP_' . $i]) && $row['FAC_ELEM_SUP_'.$i] !== "") {
        array_push($array_sup, $row['FAC_ELEM_SUP_' . $i]);
        $i++;
    }
    $col_row = $GLOBALS['connexion']->query('SELECT * FROM COLLABORATEUR WHERE COL_NO = ' . $row['COL_NO'])->fetch_assoc();
    
    
    $FAC_DEV = $row['FAC_DEV'];
    $COL_NO = $row['COL_NO'];
    $CLI_NO = $row['CLI_NO'];
    $ENT_NO = $row['ENT_NO'];
    $BAN_NO = $row['BAN_NO'];
    $FAC_CODFOU = $row['FAC_CODFOU'];
    $PRO_NO = $row['PRO_NO'];
    $CTC_NO = $row['CTC_NO'];
    $FAC_ANNEE = $row['FAC_ANNEE'];
    $FAC_MOIS = $row['FAC_MOIS'];
    $FAC_PERIODE = $row['FAC_PERIODE'];
    $FAC_DATE = $row['FAC_DATE'];
    $FAC_SUIVIPAR = $row['FAC_SUIVIPAR'];
    $FAC_MODE_REG = $row['FAC_MODE_REG'];
    $FAC_NOMCOM = $row['FAC_NOMCOM'];
    $FAC_NOMCLI = $row['FAC_NOMCLI'];
    $FAC_CODCLI = $row['FAC_CODCLI'];
    $FAC_ADR1 = $row['FAC_ADR1'];
    $FAC_ADR2 = $row['FAC_ADR2'];
    $FAC_CP = $row['FAC_CP'];
    $FAC_VILLE = $row['FAC_VILLE'];
    $FAC_NOMCTC = $row['FAC_NOMCTC'];
    $FAC_NOMPRO = $row['FAC_NOMPRO'];
    $FAC_PRODETAIL = $row['FAC_PRODETAIL'];
    $FAC_NUMCMDE = $row['FAC_NUMCMDE'];
    $FAC_ELEM = $row['FAC_ELEM'];
    $FAC_TOTAL_HT = $row['FAC_TOTAL_HT'];
    $TAUX_TVA = $row['FAC_TAUXTVA'];
    $COL_NOM = $col_row['COL_PRENOM'] . " " . $col_row['COL_NOM'];
}



$all_ent_row = $GLOBALS['connexion']->query('SELECT * FROM ENTREPRISE');
$all_ban_row = $GLOBALS['connexion']->query('SELECT * FROM BANQUE');
$all_reg_row = $GLOBALS['connexion']->query('SELECT * FROM MODEREGLEMENT');

$placeholder = 'placeholder = "Champ non renseigné"';

?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
<?php include 'head.php';
        if(isset($page))
            echo '<title>'.$page['titre'].'</title>';?>
    </head>
    <body>
<?php
if(isset($page)){
    if (is_string($page['message']) && !empty($page['message'])) {
        echo '<script type="text/javascript">alert("' . $page['message'] . '");</script>';
    }
}
?>
        <!-- Barre de menu-->
        <?php
        if(isset($page))
            $GLOBALS['titre_page'] = '<div class="ram">' . $page['titre'] . '</div>';
        include ("menu/menu_global.php");
        $act = $action;
        ?>
        <div class="container-fluid " style="margin-top: 0px;">
            <form id="formFac" action="validation_proforma.php" method="post" class="form-horizontal well">
                <?php   if($action == 'avoir'){
                            echo '<legend>Facture à avoirer</legend>';
                        }
                        else if(isset($_POST['recherche']))
                            echo '<legend>Modifiez le proforma choisi puis validez</legend>';
                        else
                            echo '<legend>Créer votre proforma puis validez</legend>';
                ?>
                <div class="row"><div class="span2 ">Nom de l'entreprise :</div>
                    <div class="span2">
                        <select name="ENT_NO" style="width:200px;">
                            <?php
                            while ($ent = $all_ent_row->fetch_assoc()) {
                                if($ent['ENT_NO'] == $ENT_NO)
                                    echo '<option value="' . $ent['ENT_NO'] . '" selected="selected">' . $ent['ENT_NOM'] . '</option>';
                                else
                                    echo '<option value="' . $ent['ENT_NO'] . '">' . $ent['ENT_NOM'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="span2 offset2">Nom de la banque :</div>
                    <div class="span2">
                        <select name="BAN_NO" style="width:300px;">
                            <?php
                            while ($ban = $all_ban_row->fetch_assoc()) {
                                if($ban['BAN_NO'] == $BAN_NO)
                                    echo '<option value="' . $ban['BAN_NO'] . '" selected="selected">' . $ban['BAN_NOM'] . '</option>';
                                else                                        
                                    echo '<option value="' . $ban['BAN_NO'] . '">' . $ban['BAN_NOM'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Numéro de commande :</div>
                    <div class="span2"><input name="FAC_NUMCMDE" required="required" value="<?php echo $FAC_NUMCMDE; ?> " <?php echo $placeholder ?> type="text"></div>

                    <div class="span2 offset2">Numéro de proforma :</div>
                    <div class="span2"><input name="FAC_DEV" required="required" value="<?php echo $FAC_DEV; ?>" <?php echo $placeholder ?> readonly="readonly" type="text"></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Affaire suivi par :</div>
                    <div class="span2"><input name="FAC_NOMCOM" required="required" value="<?php echo $FAC_NOMCOM; ?>" <?php echo $placeholder ?> type="text" style="width:300px;"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Date de facturation :</div>
                    <div class="span2"><input name="FAC_DATE" value="<?php echo $FAC_DATE; ?>" required="required" placeholder="AAAA-MM-JJ" type="date"></input></div>

                    <div class="span2 offset2">Période :</div>
                    <div class="span2"><input name="FAC_PERIODE" value="<?php echo $FAC_PERIODE; ?>" <?php echo $placeholder ?> readonly="readonly" type="text"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Nom client :</div>
                    <div class="span2"><input name="FAC_NOMCLI" required="required" value="<?php echo $FAC_NOMCLI; ?>" <?php echo $placeholder ?> type="text"></input></div>

                    <div class="span2 offset2">Code client :</div>
                    <div class="span2"><input name="FAC_CODCLI" value="<?php echo $FAC_CODCLI; ?>" readonly="readonly" type="text"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Adresse client 1 :</div>
                    <div class="span2"><input name="FAC_ADR1" value="<?php echo $FAC_ADR1; ?>" <?php echo $placeholder ?> type="text"></input></div>

                    <div class="span2 offset2">Adresse client 2 :</div>
                    <div class="span2"><input name="FAC_ADR2" value="<?php echo $FAC_ADR2; ?>" type="text"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Code postal client :</div>
                    <div class="span2"><input name="FAC_CP" value="<?php echo $FAC_CP; ?>" <?php echo $placeholder ?> type="text"></input></div>

                    <div class="span2 offset2">Ville client :</div>
                    <div class="span2"><input name="FAC_VILLE" value="<?php echo $FAC_VILLE; ?>" <?php echo $placeholder ?> type="text"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Nom du contact :</div>
                    <div class="span2"><input name="FAC_NOMCTC" required="required" value="<?php echo $FAC_NOMCTC; ?>" <?php echo $placeholder ?> type="text" style="width:300px;"></input></div>

                    <div class="span2 offset2">Code fournisseur :</div>
                    <div class="span2"><input name="FAC_CODFOU" value="<?php echo $FAC_CODFOU; ?>" <?php echo $placeholder ?> type="text" style="width:300px;"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Nom du projet :</div>
                    <div class="span2"><input name="FAC_NOMPRO" value="<?php echo $FAC_NOMPRO; ?>" <?php echo $placeholder ?> type="text"></input></div>

                    <div class="span2 offset2">Détails du projet :</div>
                    <div class="span2"><input name="FAC_PRODETAIL" value="<?php echo $FAC_PRODETAIL; ?>" <?php echo $placeholder ?> type="text" style="width:400px;"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Prix de base :</div>
                    <div id="facElemId" class="span2"><input name="FAC_ELEM" value="<?php echo $FAC_ELEM; ?>" <?php echo $placeholder ?> type="text"></input></div>

                    <div class="span2 offset2">Collaborateur du projet :</div>
                    <div class="span2"><input name="COL_NOM" value="<?php echo $COL_NOM; ?>" <?php echo $placeholder ?> readonly="readonly" type="text" style="width:300px;"></input></div>

                    <div class="row"><div class="span12"><br></div></div>
                    <div class="span2 ">Mode de règlement :</div>
                   
                    <div class="span2">
                        <select name="FAC_MODE_REG" style="width: 350px;">
                            <?php
                            while ($mor = $all_reg_row->fetch_assoc()) {
                                if($mor['MOR_NO'] == $FAC_MODE_REG){
                                    echo '<option value="' . $mor['MOR_NO'] . '" selected="selected">' . $mor['MOR_LIBELLE'] . '</option>';
                                }else {                                       
                                    echo '<option value="' . $mor['MOR_NO'] . '">' . $mor['MOR_LIBELLE'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="span2 offset2">Prix du projet <?php echo $_GET['action'] == 'avoir' ? 'restant après avoir' : ''?>:</div>
                    <div class="span2"><input id="facTotalHt" name="FAC_TOTAL_HT" value="<?php echo $FAC_TOTAL_HT?>" <?php echo $placeholder ?> readonly="readonly" type="text" style="width:300px;"></input></div>
                    
                    <?php
                    if(isset($_GET['action']) && $_GET['action'] == 'avoir'){
                        echo '<div class="row"><div class="span12"><br></div></div>
                            <div class="span2">Montant de la facture avoirée :</div>
                            <div class="span2"><input id="OLD_MT_FAC" type="text" name="OLD_FAC_TOTAL_HT" value="'.$FAC_TOTAL_HT.'"></input></div>
                            <div class="span2 offset2">Montant a avoiré : </div>
                            <div class="span2"><input id="facTotalAvo" type="text" name="FAC_MT_AVO" value="0"></input></div>';
                    }
                    ?>
                        <!--echo '<input type="hidden" name="FAC_MT_AVO" value="0"></input></div>';-->
                    <div class="row"><div class="span12"><br></div></div>
                    <div>
                    <fieldset>
                        <legend class="offset1">Element(s) supplémentaire(s) à facturer</legend>
                        <table id='tableau0' border="1" class="table-bordered table-condensed" width="85%" align="center">
                            <tr>
                                <th>Désignation</th>
                                <th>Détail projet</th>
                                <th>Montant à facturer</th>
                                <th><?php echo bouton2('Ajouter une ligne', 'btn', 'primary', 'ajoutElement', 'add_element()'); ?></th>
                            </tr>
                        </table>
                    </fieldset>
                    <input type="hidden" name="type" value="<?php echo $_GET['type']; ?>"/>
                    <?php   if($_GET['type'] == "prefacturation")
                                echo '<input type="hidden" name="type" value="creation" />';
                            if($_GET['type'] == "proforma")
                                echo '<input type="hidden" name="type" value="proforma" />';
                            if($_GET['type'] == "facture")
                            echo '<input type="hidden" name="type" value="facture" />';
                    ?>
                    <input id="actAvoir" type="hidden" name="" value="<?php echo $action; ?>"/>
                    <input type="hidden" name="FAC_SUIVIPAR" value="<?php echo $FAC_SUIVIPAR; ?>"/>
                    <input type="hidden" name="TAUX_TVA" value="<?php echo $TAUX_TVA; ?>"/>
                    <input type="hidden" name="CLI_NO" value="<?php echo $CLI_NO; ?>"/>
                    <input type="hidden" name="CTC_NO" value="<?php echo $CTC_NO; ?>"/>
                    <input type="hidden" name="PRO_NO" value="<?php echo $PRO_NO; ?>"/>
                    <input type="hidden" name="COL_NO" value="<?php echo $COL_NO; ?>"/>
                    <input type="hidden" name="FAC_ANNEE" value="<?php echo $FAC_ANNEE; ?>"/> 
                    <input type="hidden" name="FAC_MOIS" value="<?php echo $FAC_MOIS; ?>"/>
                    <input type="hidden" name="mois" value="<?php echo $_POST['mois']; ?>" />
                    <input type="hidden" name="annee" value="<?php echo $_POST['annee']; ?>" />
                    <?php echo isset($row['FAC_NO']) ? '<input type="hidden" name="FAC_NO" value="'.$row['FAC_NO'].'"/>' : ""; ?>
<?php
echo '<div id="containerSup">';
if(isset($array_sup)){
    $i = 0;
    while ($i < count($array_sup)) {
        echo '<input id="FAC_ELEM_SUP'.$i.'" class="factureElemSup" type="hidden" value="' . $array_sup[$i] . '"/>';
        $i++;
    }
}
echo '</div>';
?>
                </div>
                <div class="row"><div class="span12"><br></div></div>
                <div class="row-fluid">
                    <div class="offset5 span7">
                        <button class="btn btn-primary" style="margin-bottom:-6.2em;" type="submit" onclick="check()">Valider <i class="icon-ok"></i></button>
                    </div>
                </div>
                <input type="hidden" name="recherche" value="<?php echo isset($_POST['recherche']) ? $_POST['recherche'] : ""; ?>"></input>
                </div>
            </form>
        </div>
<?php
include ('inc/regex.php');
include ('inc/regex_javascript.php');
?>
        <script type="text/javascript">
            var action = document.getElementById('actAvoir').value;

            if(action == 'avoir'){
                $.each($('form').serializeArray(), function(index, value){
                    if(value.name != 'FAC_ELEM'){
                        $('[name="' + value.name + '"]').attr('readonly', 'readonly');
                    }
                });
            }
            

            //Fonction permettant de vérifier qu'au moins un mode de règlement à été choisi
            function validate_form( )
            {
                valid = true;

                return valid;
            }

            nbAjout = 0;
            onload = function ajout_element()
            {
                var arrayCheck = document.querySelectorAll('.factureElemSup');

                var nbCell = 4;

                var j = 0;
                while (j < arrayCheck.length) {

                    var numRow = document.getElementById('tableau0').rows.length;

                    var ajoutTable = document.getElementById('tableau0').insertRow(numRow);
        //            //Mise à jour du nombre de ligne
                    numRow++;

                    var i = 0;
                    //Cas d'erreur à gérer : Caractère spéciaux.
                    //Exemple : Le 1er caractère qui suit le "&shy" est un "ç". Les deux éléments vont rester ensemble. On aura un champs à undefined
                    var test = arrayCheck[j].value.split("&shy");
                    nbAjout++;

                    while (i < nbCell)
                    {
                        var cell = ajoutTable.insertCell(i);
                        switch (i)
                        {
                            case 0:
                                cell.innerHTML = '<td><input type="textbox" name="FAC_CELL' + numRow + i + '" value="' + test[0] + '" /></td>';
                                break;
                            case 1:
                                cell.innerHTML = '<td><input type="textbox" name="FAC_CELL' + numRow + i + '" value="' + test[1] + '" /></td>';
                                break;
                            case 2:
                                cell.innerHTML = '<td><input type="textbox" name="FAC_CELL' + numRow + i + '" value="' + test[2] + '" /></td>';
                                break;
                        }
                        i += 1;
                    }
                    j += 1;
                }
                calcul();
            }


            function add_element()
            {
                nbAjout++;
                if(nbAjout > 5)
                {
                    alert('Vous ne pouvez pas ajouter de ligne supplémentaire.');
                }
                else
                {
                    var numRow = document.getElementById('tableau0').rows.length;

                    var ajoutTable = document.getElementById('tableau0').insertRow(numRow);
                    //Mise à jour du nombre de ligne
                    numRow++;

                    var nbCell = 4;
                    var i = 0;

                    while(i < nbCell)
                    {
                        var cell = ajoutTable.insertCell(i);
                        switch(i)
                        {
                            case 0:
                                cell.innerHTML = '<td><input type="textbox" name="FAC_CELL'+numRow+i+'" value="" /></td>';
                                break;
                            case 1:
                                cell.innerHTML = '<td><input type="textbox" name="FAC_CELL'+numRow+i+'" /></td>';
                                break;
                            case 2:
                                cell.innerHTML = '<td><input type="textbox" name="FAC_CELL'+numRow+i+'" /></td>';
                                break;
                        }
                        i+=1;
                    }
                }
            }

            //Calcul le total HT
            function calcul(){
                var total = parseFloat(document.getElementById('facElemId').childNodes[0].value);
                var table = document.getElementById('tableau0');
                i = 1;

                //Boucle permettant de récupérer la valeur des éléments supplémentaire à facturer
                while(table.getElementsByTagName('input')[i] !== undefined){
                    if(i%3 == 0 && !isNaN(parseFloat(table.getElementsByTagName('input')[i].value))){
                        total += parseFloat(table.getElementsByTagName('input')[i].value);
                    }
                    i++;
                }
                document.getElementById('facTotalHt').value = total;
                var oldFacTot = document.getElementById('OLD_MT_FAC').value;
                document.getElementById('facTotalAvo').value = oldFacTot - total;
            }

            function getEventTarget(e) {
                e = e || window.event;
                return e.target || e.srcElement;
            }

            //Permet de sécuriser la saisie des nombres
            //On ne peut saisir que des nombres sur l'input "prix du projet"
            //Les deux autres reste libre
            $(document).ready( function () {
                // Supprime le dernier caractère s'il n'est pas alphanumérique sur evt keyup
                $('#facElemId').delegate("input", "keyup", function(){
                    if(!$(this).val().match(/^[0-9]*$/i)){ // 0-9 uniquement
                      supprimer_dernier_caractere(this);
                    }
                    calcul();
                });
            });

            //Permet de sécuriser la saisie des nombres
            //On ne peut saisir que des nombres sur l'input "montant à facturer"
            //Les deux autres reste libre
            $(document).ready( function () {
                    // Supprime le dernier caractère s'il n'est pas alphanumérique sur evt keyup
                    $('#tableau0').delegate("input","keyup",function(e){
                        var target = getEventTarget(e);
                        var lastCar = $(this).attr('name').substring(9,10);
                        if(lastCar == '2'){
                            if(!$(this).val().match(/^[0-9]*$/i)) // 0-9 uniquement
                              supprimer_dernier_caractere(this);
                      }
                      calcul();
                });
            });

            function supprimer_dernier_caractere(elm) {
                var val = $(elm).val();
                var cursorPos = elm.selectionStart;
                $(elm).val(
                    val.substr(0,cursorPos-1) + // before cursor - 1
                    val.substr(cursorPos,val.length) // after cursor
                )
                elm.selectionStart = cursorPos-1; // replace the cursor at the right place
                elm.selectionEnd = cursorPos-1;
            }
            
            function check(){
                document.getElementById('formFac').action +='?action=avoir';
            }
            
        </script>
    </body>
</html>