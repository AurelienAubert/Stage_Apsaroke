<?php include ("connection.php"); ?>
<?php
$q = strtoupper($_GET["q"]);
if (strlen($q)==3) {
    $query_Mnemo = "SELECT COL_MNEMONIC FROM COLLABORATEUR WHERE COL_MNEMONIC = '" . $q ."'";
    $result = $GLOBALS['connexion']->query($query_Mnemo);
    if ($result->num_rows != 0) {
        echo "Mnémonique déjà utilisé";
    }
}

?>