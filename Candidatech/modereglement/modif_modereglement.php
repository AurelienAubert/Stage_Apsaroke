<?php
    include_once 'inc/connection.php';
    include_once 'inc/verif_champs_formulaire.php';
    
    function recuperer_modereglement($recherche) {
        $query = "SELECT * FROM MODEREGLEMENT WHERE MOR_NO=" . $recherche;
        $results = $GLOBALS['connexion']->query($query)->fetch_assoc();
        $_POST = array();
        foreach($results as $key => $result) {
            $_POST[substr($key, 4)] = $result;
        }
    }
    
    function update_modereglement($recherche) {
        $champs = array(    
            'CODE'          => 1,
            'LIBELLE'       => 1,
        );

        $vars = verif_champs($champs, 'MOR_');
        if (is_array($vars)) {
            $query = creer_update($vars, 'MODEREGLEMENT', "MOR_NO=" . $recherche);
            $GLOBALS['connexion']->query($query);
            $url = str_replace("modification", "affichage", $_POST['urlRetourMAJ']) . "&recherche=" . $recherche . "&message=MAJOK";
            unset($_POST);
            $_POST['recherche'] = $recherche;
        }
        else {
            return $vars;
        }
        header('Location:' . $url);
    }
?>