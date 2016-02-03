<?php
// Evite une erreur sous CHROME (Failed to load resource: net::ERR_CACHE_MISS) 
// insérer avant de lancer session_start().
session_cache_limiter('none');
session_start();
if (!isset($_SESSION['col_id'])) {
    header("Location: index.php");
    exit();
}

if(isset($_SESSION['mois']) && isset($_SESSION['annee']) && isset($_SESSION['PHP_SELF']) && $_SERVER['PHP_SELF'] != $_SESSION['PHP_SELF'])
{
    unset($_SESSION['mois']);
    unset($_SESSION['annee']);
    unset($_SESSION['PHP_SELF']);
}
?>
