<?php
include_once 'inc/connection.php';

// Impressions à préparer en PDF (avec sauvegarde dans la base)
    echo '<img style="width:10px;"></img>';
    echo '<input type="button" value="ORDRE DE MISSION" onclick="javascript:impOMI();" class="btn btn-primary"></input>';

// Liste des impressions déja validées sur le projet
    $q1 = "SELECT * FROM HISTDOC WHERE HID_TYPE='OMI' AND HID_IDDOC=" . $page['recherche'];
    $r1 = $GLOBALS['connexion']->query($q1);
    if($r1->num_rows > 0){
        echo '<img style="width:10px;"></img>';
        echo '<select id="iddoc" onchange="reimpOMI();"><option value="0"></option>';
        while ($row = $r1->fetch_assoc()) {
            echo '<option value="' . $row['HID_NO'] . '">' . $row['HID_NOMDOC'] .'</option>';
        }
    }
?>
<script>
    function impOMI(){
        document.forms[0].action = 'prepare_document.php?type=OMI&recherche=<?php echo $page['recherche']; ?>';
        document.forms[0].submit();
    }
    function reimpOMI(){
        var id = $('#iddoc').val();
        document.forms[0].action = 'reimprime_document.php?type=OMI&recherche=<?php echo $page['recherche']; ?>&iddoc=' + id;
        document.forms[0].submit();
    }
</script>
