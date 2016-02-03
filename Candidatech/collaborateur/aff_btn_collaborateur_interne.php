<?php
// Impressions à préparer en PDF (avec sauvegarde dans la base)
    echo '<img style="width:10px;"></img>';
    echo '<input type="button" value="EDITION CDI" onclick="javascript:impCDI();" class="btn btn-primary"></input>';
?>
<script>
    function impCDI(){
        document.forms[0].action = 'prepare_document.php?type=CDI&recherche=<?php echo $page['recherche']; ?>';
        document.forms[0].submit();
    }
</script>
