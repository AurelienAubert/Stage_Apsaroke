<?php
/**
 * variable nécéssaire : $page, tableau contenant :
 * - titre
 * - action
 * - contenu
 * - message
 */
include 'inc/verif_session.php';
include_once "inc/connection.php";
require "inc/def_rep_document.php"; 

// Lecture des types de documents.
//$q1 = "SELECT * FROM DOCUMENT ORDER BY DOC_NOM";
//$r1 = $GLOBALS['connexion']->query($q1);
$query = "SELECT * FROM DOCUMENT ORDER BY DOC_NOM";
$result = $GLOBALS['connexion']->query($query);

if (isset($_POST['iddoc']) && $_POST['iddoc'] > 0){
    $iddoc = $_POST['iddoc'];
    $type = $_POST['type'];
    $query3 = "SELECT * FROM HISTDOC WHERE HIS_NO=" . $iddoc;
    $result3 = $GLOBALS['connexion']->query($query3);
    $_POST['recherche'] = $result3['HID_IDDOC'];
    $url = $repdocimp[$type] . '/imprime_' . $type . '.php?iddoc=' . $iddoc;
    ?>
    <script>
        document.location.href = 'choix_reimpression.php';
        window.open('<?php echo $url; ?>', 'imp<?php echo $type; ?>', 'height=900px; width=850px');
    </script>
    <?php
    $type = '';
    $iddoc = '';
}else{
    unset($_POST['iddoc']);
}

if (isset($_POST['type']) && $_POST['type'] != ''){
    $type = $_POST['type'];
    $query2 = "SELECT * FROM HISTDOC WHERE HID_TYPE = '" . $type . "' ORDER BY HID_NOMDOC";
    $result2 = $GLOBALS['connexion']->query($query2);
}else{
    unset($_POST['type']);
}


echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title><?php echo 'Ré-impression de document'; ?></title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php
        $GLOBALS['titre_page'] = '<div class="autre">Ré-impression de document</div>';
        include ("menu/menu_global.php");
        ?>

        <div class="container-fluid ">
            <form action="choix_reimpression.php" method="post" class="form-horizontal well">
                <div class="row-fluid">
                    <div class="offset2">
                        <p>Sélectionner le type de document :</p>
                        <div class='offset3'>
                            <select name="type" style="margin-top: -30px;" required>
                                <option> </option>
                <?php
                    while ($row = $result->fetch_assoc()) {

                ?>
                                <option value="<?php echo $row['DOC_CODE']; ?>" <?php if($type == $row['DOC_CODE']) echo "selected"; ?>><?php echo $row['DOC_NOM']; ?></option>
                <?php
                }
                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row"><div class="span12"><br></div></div>
<!--                    <div class="offset5 span3">
                        <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i> </button>
                    </div>-->
                </div>
                <?php
                if ($type)
                {
                ?>
                <div class="row-fluid">
                    <div class="offset2">
                        <p>Sélectionner le No de document :</p>
                        <div class='offset3'>
                            <select name="iddoc" style="margin-top: -30px;" required>
                                <option> </option>
                <?php
                    while ($ro2 = $result2->fetch_assoc()) {

                ?>
                                <option value="<?php echo $ro2['HID_NO']; ?>" <?php if($iddoc == $ro2['HID_NO']) echo "selected"; ?>><?php echo $ro2['HID_NOMDOC']; ?></option>
                <?php
                }
                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row"><div class="span12"><br></div></div>
<!--                    <div class="offset5 span3">
                        <button class="btn btn-primary" type="submit">Valider <i class="icon-ok"></i> </button>
                    </div>-->
                </div>
                <?php
                }
                ?>
                <script>
                    $(document).on("change", "select[name='type']", function () {
                        document.forms[0].submit();
                    });
                    $(document).on("change", "select[name='iddoc']", function () {
                        document.forms[0].submit();
                    });
                </script>
            </form>
        </div>
    </body>
</html>


