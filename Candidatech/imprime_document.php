<?php
require "inc/verif_session.php"; 
require "inc/def_rep_document.php"; 

$page = array(
    'titre'     => 'Affichage d\'un ',
    'recherche' => '',
    'message'   => ''
);

if (isset($_POST['recherche'])) {
    if(isset($_POST['PRO_NO']))
        $page['recherche'] = htmlspecialchars (addslashes (trim (strtoupper ($_POST['PRO_NO']))));
    else
        $page['recherche'] = htmlspecialchars (addslashes (trim (strtoupper ($_POST['recherche']))));
    if (isset($_POST['type'])) {
        $type = $_POST['type'];
        if (isset($_POST['iddoc'])){
            $iddoc = $_POST['iddoc'];
        }else{
            include $repdocimp[$type] . '/sauve_' . $type . '.php';
            $iddoc = call_user_func('sauver_' . $type, $page['recherche']);
        }
        $url = $repdocimp[$type] . '/imprime_' . $type . '.php?iddoc=' . $iddoc;
        $dossier = $repdocimp[$type];
        if ($type == 'CDI') $dossier .= "_interne";
        $urlret = 'affichage.php?type=' . $dossier . '&recherche=' . $page['recherche'];
    ?>
    <script>
        document.location.href = '<?php echo $urlret; ?>';
        window.open('<?php echo $url; ?>', 'imp<?php echo $type; ?>', 'height=900px; width=850px');
    </script>
    <?php
    } else {
        $page['message']='Type d\'ajout manquant';
        include 'inc/page_prepare.php';
    }
} else {
    $page['message']='Aucune recherche demandée';
    include 'inc/page_prepare.php';
}
