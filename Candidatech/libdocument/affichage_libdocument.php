<?php
    include_once "inc/connection.php";
    include_once 'inc/creer_input.php';

    function afficher_libdocument($recherche) {
        $query = "SELECT * FROM LIBDOCUMENT L LEFT JOIN COLLABORATEUR COL ON COL.COL_NO=L.COL_NO WHERE L.DOC_NO=" . $recherche;
        $row = $GLOBALS['connexion']->query($query)->fetch_assoc();

        return afficher('Document :', $row['LDO_DOCNO'], 'span2', 'span3')
            . afficher('No d\'ordre :', $row['LDO_ORDRE'], 'offset1 span2', 'span3')
            . sautLigne()
            . afficher('Intitulé :', $row['LDO_NOM'], 'span2', 'span3')
            //. afficher('Collaborateur :', $row['COL_NOM'], 'offset1 span2', 'span3')
            . sautLigne()
            . afficher_textarea('Contenu :', $row['LDO_CONTENU'], 2, 8, 6, 80, 'textarea800')
       ;
        return $result;
    }
?>
