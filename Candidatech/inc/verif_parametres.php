<?php

    /**
     * Ce fichier effectue l'int�gralit� des v�rifications � faire sur les champs pass�s en post ou en get
     */
    
    function verif_parametres(&$parametres) {
        foreach($parametres as &$parametre) {
            if (is_array($parametre)) {
                verif_parametres($parametre);
            }
            else {
                $parametre = htmlspecialchars(addslashes(trim($parametre)), ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
            }
        }
    }
    verif_parametres($_POST);
    verif_parametres($_GET);
?>
