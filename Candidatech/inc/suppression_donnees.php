<?php
    include_once 'inc/connection.php';
    
    /**
     * exécute un delete pour chaque table passée en paramètre, en y ajoutant le where
     * 
     * @param string $where
     * @param array $tables
     */
    function supprimer($where, $tables) {
        foreach($tables as $table) {
            $query = 'DELETE FROM ' . $table . ' WHERE ' . $where;
            $GLOBALS['connexion']->query($query);
        }
    }
    
    /**
     * supprime un RAM
     * @param int $NO l'id à supprimer
     */
    function supprimer_ram($NO) {
        if (is_array($NO)) {
            $where = "RAM_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'RAM_NO='.$NO;
        }
        
        $query = "SELECT DISTINCT COM_NO_APSA, COM_NO_CLI FROM RAM WHERE " . $where;
        $vars = $GLOBALS['connexion']->query($query);
        $comm = "COM_NO IN ('";
        while ($var = $vars->fetch_assoc()) {
            $comm .= implode("', '", $var) . "', '";
        }
        
        supprimer($where, array(
            'RAM'
        ));

        supprimer(substr($comm, 0, -3) . ')', array(
            'COMMENTAIRE'
        ));
    }
    
    /**
     * supprime une note de frais
     * 
     * @param int $NO l'id à supprimer
     */
    function supprimer_note_frais($NO) {
        if (is_array($NO)) {
            $where = "NOF_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'NOF_NO='.$NO;
        }
        
        supprimer($where, array(
            'LIGNE_FRAIS',
            'NOTE_FRAIS'
        ));
    }
    
    /**
     * supprime un projet
     * @param int $NO l'id à supprimer
     */
    function supprimer_projet($NO) {
        if (is_array($NO)) {
            $where = "PRO_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'PRO_NO='.$NO;
        }
        
//        $query = "SELECT RAM_NO FROM RAM WHERE " . $where;
//        $vars = $GLOBALS['connexion']->query($query);
//        $ram = array();
//        while ($var = $vars->fetch_assoc()) {
//            $ram[] = $var['RAM_NO'];
//        }
//        
//        supprimer_ram($ram);
//        supprimer($where, array(
//            'CONTRAT',
//            'ASSIGNER',
//            'PROJET'
//        ));
        
        // On clos provisoirement le projet au lieu de la supprimer.
        $date = date('Y-m-d');
        $query = "UPDATE PROJET SET PRO_ARCHIVE=1, PRO_DTCLOTURE='" . $date . "' WHERE " . $where;
        $vars = $GLOBALS['connexion']->query($query);
    }
    
    /**
     * supprime un collaborateur
     * 
     * @param int $NO l'id à supprimer
     */
    function supprimer_collaborateur($NO) {
        if (is_array($NO)) {
            $where = "COL_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'COL_NO='.$NO;
        }

//        $query = "SELECT RAM_NO FROM RAM WHERE " . $where;
//        $vars = $GLOBALS['connexion']->query($query);
//        $ram = array();
//        while ($var = $vars->fetch_assoc()) {
//            $ram[] = $var['RAM_NO'];
//        }
//
//        $query = "SELECT NOF_NO FROM NOTE_FRAIS WHERE " . $where;
//        $vars = $GLOBALS['connexion']->query($query);
//        $note = array();
//        while ($var = $vars->fetch_assoc()) {
//            $note[] = $var['NOF_NO'];
//        }
//        
//        supprimer_ram($ram);
//        supprimer_note_frais($note);
//        supprimer($where, array(
//            'ABSENCE',
//            'ASSIGNATION',
//            'COLLABORATEUR'
//        ));
        
        // Le collaborateur est archivé.
        $query = "UPDATE COLLABORATEUR SET COL_ARCHIVE=1 WHERE " . $where;
        $vars = $GLOBALS['connexion']->query($query);
    }
    
    /**
     * supprime un collaborateur interne
     * @param int $NO l'id à supprimer
     */
    function supprimer_collaborateur_interne($NO) {
        if (is_array($NO)) {
            $where = "COL_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'COL_NO='.$NO;
        }
        
//        supprimer($where, array(
//            'SPECIF_MENSUELLE',
//            'INTERNE'
//        ));
        
        supprimer_collaborateur($NO);
    }
    
    /**
     * supprime un collaborateur externe
     * @param int $NO l'id à supprimer
     */
    function supprimer_collaborateur_externe($NO) {
        if (is_array($NO)) {
            $where = "COL_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'COL_NO='.$NO;
        }
        
//        supprimer($where, array(
//            'EXTERNE'
//        ));
        
        supprimer_collaborateur($NO);
    }
    
    /**
     * supprime un client
     * @param int $NO l'id à supprimer
     */
    function supprimer_client($NO) {
        
        if (is_array($NO)) {
            $where = "CLI_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            //echo $NO;
            $where = 'CLI_NO='.$NO;
        }
        
//        $query = "SELECT PRO_NO FROM PROJET WHERE " . $where;
//        $vars =  $GLOBALS['connexion']->query($query);
//        $projet = array();
//        while ($var = $vars->fetch_assoc()) {
//            $projet[] = $var['PRO_NO'];
//        }
//
//        supprimer_projet($projet);
//        supprimer($where, array(
//            'CONTACT_CLIENT',
//            'CLIENT',
//        ));
        
        // Le client est archivé.
        $query = "UPDATE CLIENT SET CLI_ARCHIVE=1 WHERE " . $where;
        $vars = $GLOBALS['connexion']->query($query);
    }
    
    /**
     * supprime un fournisseur
     * @param int $NO l'id à supprimer
     */
    function supprimer_fournisseur($NO) {
        if (is_array($NO)) {
            $where = "FOU_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'FOU_NO='.$NO;
        }
        
//        $query = "SELECT COL_NO FROM EXTERNE WHERE " . $where;
//        $vars =  $GLOBALS['connexion']->query($query);
//        $collab = array();
//        while ($var = $vars->fetch_assoc()) {
//            $collab[] = $var['COL_NO'];
//        }
//        
//        $query = "SELECT CTF_NO FROM CONTACT_FOURNISSEUR WHERE " . $where;
//        $vars =  $GLOBALS['connexion']->query($query);
//        $contact = array();
//        while ($var = $vars->fetch_assoc()) {
//            $contact[] = $var['CTF_NO'];
//        }
//        
//        supprimer_collaborateur_externe($collab);
//        supprimer_contact_fournisseur($contact);
//        
//        supprimer($where, array('FOURNISSEUR'));
        
        // Le client est archivé.
        $query = "UPDATE FOURNISSEUR SET FOU_ARCHIVE=1 WHERE " . $where;
        $vars = $GLOBALS['connexion']->query($query);
    }
    
    /**
     * supprime un contact client
     * @param int $NO l'id à supprimer
     */
    function supprimer_contact_client($NO) {
        echo $NO;
        if (is_array($NO)) {
            $where = "CTC_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'CTC_NO='.$NO;
        }
        
//        $query = "SELECT PRO_NO FROM PROJET WHERE " . $where;
//        $vars =  $GLOBALS['connexion']->query($query);
//        $projet = array();
//        while ($var = $vars->fetch_assoc()) {
//            $projet[] = $var['PRO_NO'];
//        }
//        
//        supprimer_projet($projet);
//        supprimer($where, array('CONTACT_CLIENT'));
        
        // Le contact est archivé.
        $query = "UPDATE CONTACT_CLIENT SET CTC_ARCHIVE=1 WHERE " . $where;
        $vars = $GLOBALS['connexion']->query($query);
    }
    
    /**
     * supprime un contact fournisseur
     * @param int $NO l'id à supprimer
     */
    function supprimer_contact_fournisseur($NO) {
        if (is_array($NO)) {
            $where = "CTF_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'CTF_NO='.$NO;
        }
        
//        $query = "SELECT PRO_NO FROM PROJET WHERE " . $where;
//        $vars =  $GLOBALS['connexion']->query($query);
//        $projet = array();
//        while ($var = $vars->fetch_assoc()) {
//            $projet[] = $vars['PRO_NO'];
//        }
//        
//        supprimer_projet($projet);
//        supprimer($where, array('CONTACT_FOURNISSEUR'));

        // Le contact est archivé.
        $query = "UPDATE CONTACT_FOURNISSEUR SET CTF_ARCHIVE=1 WHERE " . $where;
        $vars = $GLOBALS['connexion']->query($query);
    }

    /**
     * supprime une fonction
     * @param int $NO l'id à supprimer
     */
    function supprimer_fonction($NO) {
        if (is_array($NO)) {
            $where = "FCT_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'FCT_NO='.$NO;
        }
        
        supprimer($where, array('FONCTION'));
    }
    
    
    function supprimer_entreprise($NO){
        if (is_array($NO)) {
            $where = "ENT_NO IN ('" . implode("', '", $NO) . "')";
        }
        else {
            $where = 'ENT_NO='.$NO;
        }
        supprimer($where, array('ENTREPRISE'));
        //TODO : Suppression d'une entreprise ayant généré des factures, étant relié à des clients, à des fournisseurs, etc...
    }
    
    function supprimer_banque($NO){
        $where = 'BAN_NO='.$NO;
        supprimer($where, array('BANQUE'));
    
    }
    
    function supprimer_modereglement($NO){
        $where = 'MOR_NO='.$NO;
        supprimer($where, array('MODEREGLEMENT'));
    }
   
?>
