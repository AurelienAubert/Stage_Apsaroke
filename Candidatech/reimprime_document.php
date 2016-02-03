<?php
require "inc/verif_session.php";
require "inc/def_rep_document.php";

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $iddoc = $_GET['iddoc'];
    $recherche = $_GET['recherche'];

    $url = $repdocimp[$type] . '/imprime_' . $type . '.php?iddoc=' . $iddoc;
    $dossier = $repdocimp[$type];
    if ($type == 'CDI') $dossier .= "_interne";
    $urlret = 'affichage.php?type=' . $dossier . '&recherche=' . $recherche;
?>
<script>
    document.location.href = '<?php echo $urlret; ?>';
    window.open('<?php echo $url; ?>', 'imp<?php echo $_POST['type']; ?>', 'height=900px; width=850px');
</script>
<?php
}
?>
