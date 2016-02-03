<?php
include_once 'inc/connection.php';

if (!isset($_GET['action'])){
    echo '<img style="width:10px;"></img>';
    echo '<input type="button" value="Nouvelle mission" onclick="javascript:nouvelle();" class="btn btn-primary"></input>';
}
?>
<script>
    function impOMI(id){
        document.forms[0].action = 'prepare_document.php?type=OMI&recherche=' + id;
        document.forms[0].submit();
    }
    function reimpOMI(idpro, iddoc){
        document.forms[0].action = 'reimprime_document.php?type=OMI&recherche=' + idpro + '&iddoc=' + iddoc;
        document.forms[0].submit();
    }
    function nouvelle(){
        var idpro = $('[name="recherche"]').val();
        document.forms[0].action = 'modification.php?type=projet&recherche=' + idpro + '&action=creer';
        document.forms[0].submit();
    }
    $(document).ready(function() {
        $('[name="MISSION"]').on('change', function(){
            trouvemission($('[name="MISSION"]').val());
        });
    });
    function trouvemission(id){
        $('[name="idmission"]').val(id);
        var url = 'affichage.php?type=projet&recherche=' + $('[name="recherche"]').val();
        document.forms[0].action = url;
        document.forms[0].submit();
    }
</script>
