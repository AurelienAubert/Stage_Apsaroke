<?php session_start(); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head>
        <?php include 'head.php'; ?>
        <title>Migration</title>
    </head>
    <body>
        <!-- Barre de menu-->
        <?php include ("menu/menu_global.php"); ?>
        <div class="">
            <?php
                // entrer les parametres pour la connexion
                echo "<p>début de la migration</p>";
                $dbUser     = "dbo511978299";
				$dbPass     = "Vendredi13";
				$dbHote     = "db511978299.db.1and1.com";
                $dbName     = "db511978299";
                $connexion  = mysqli_connect($dbHote, $dbUser, $dbPass, "$dbName") or die("Error " . mysqli_error($connexion));
                echo "<p>connecté à l'ancienne base</p>";
                $dbUserSortie = "dbo512037460";
                $dbPassSortie = "Vendredi13";
                $dbHoteSortie = "db512037460.db.1and1.com";
                $dbNameSortie = "db512037460";
                $connexion_sortie   = mysqli_connect($dbHoteSortie, $dbUserSortie, $dbPassSortie, "$dbNameSortie") or die("Error " . mysqli_error($connexion_sortie));
                echo "<p>connecté à la nouvelle base</p>";
                $queries = array(
                    'TYPE_AUTORISATION' => "SELECT * FROM type_auto",
                    'COLLABORATEUR'     => "SELECT * FROM collaborateur",
                    'TYPE_ABSENCE'      => "SELECT * FROM type_abs",
                    'ABSENCE'           => "SELECT * FROM absence",
                    'DEMANDE'           => "SELECT * FROM demande_abs",
                    'COMMENTAIRE'       => "SELECT * FROM commentaire",
                    'RAM'               => "SELECT * FROM ram",
                );

                $values = array();
                foreach ($queries as $type => $query) {
                    $result = $connexion->query($query);
                    while ($row = $result->fetch_assoc()) {
                        switch($type) {
                            case 'TYPE_AUTORISATION':
                                $values[$type][] = array(
                                    'TAU_LIBELLE'   => $row['NOM_TYPE'],
                                    'TAU_NO'        => $row['ID_TYPE']
                                );
                                break;
                            case 'COLLABORATEUR':
                                $values[$type][] = array(
                                    'COL_NO'            => $row['ID_COL'],
                                    'COL_MNEMONIC'      => $row['MNE_COL'],
                                    'COL_NOM'           => $row['NOM_COL'],
                                    'COL_PRENOM'        => $row['PRE_COL'],
                                    'COL_PASSWORD'      => $row['PASSWORD'],
                                    'COL_PASS_ALL'      => $row['PASSWORD_ALL'],
                                    'TAU_NO'            => $row['ID_TYPE'],
                                    'COL_ETAT'          => 1,
                                    'COL_CIVILITE'      => 'M.',
                                    'COL_NOMJEUNEFILLE' => '',
                                );

                                if (in_array($row['ID_COL'], array(16, 17, 18, 19, 23))) {
                                    $values['EXTERNE'][] = array(
                                        'COL_NO'    =>  $row['ID_COL'],
                                    );
                                }
                                else {
                                    //TODO : on aura peut être un soucis ici, va peut être falloir mettre autre chose par défaut...
                                    $values['INTERNE'][] = array(
                                        'INT_DTNAISSANCE'   => '',
                                        'INT_NSS'           => '',
                                        'INT_LIEUNAISSANCE' => '',
                                        'INT_NATIONALITE'   => '',
                                        'INT_ADRESSE'       => '',
                                        'INT_ADRESSE2'      => '',
                                        'INT_CP'            => '',
                                        'INT_VILLE'         => '',
                                        'INT_STATUT'        => '',
                                        'INT_COEFF'         => '',
                                        'INT_TYPECONTRAT'   => '',
                                        'INT_TYPEHORAIRE'   => '',
                                        'INT_DTENTREE'      => '',
                                        'INT_DTDEPART'      => '',
                                        'INT_PERIODEESSAI'  => '',
                                        'INT_PPE'           => '',
                                        'INT_REMUNFIXE'     => '',
                                        'INT_REMUNVAR'      => '',
                                        //'INT_CDGUICHET'     => '',
                                        'INT_NOMBANQUE'     => '',
                                        //'INT_CDBANQUE'      => '',
                                        //'INT_NUMCOMPTE'     => '',
                                        //'INT_CLERIB'        => '',
                                        'INT_TR'            => '',
                                        'INT_FACTURABLE'    => '',
                                        'INT_GSM'           => '',
                                        'INT_PEE'           => '',
                                        'INT_TREIZIEME'     => '',
                                        'INT_PRIME_ANCI'    => '',
                                        'INT_PART_VARI'     => '',
                                        'COL_NO'            => $row['ID_COL'],
                                        'INT_IBAN'          => '',
                                        'INT_BIC'           => '',
                                    );
                                }
                                break;
                            case 'ABSENCE':
                            case 'DEMANDE':
                                $table = substr($type, 0, 3);
                                $values['ABSENCE'][] = array(
                                    'ABS_JOUR'      => $row['JOUR_' . $table],
                                    'ABS_MOIS'      => $row['MOIS_' . $table],
                                    'ABS_ANNEE'     => $row['ANNEE_' . $table],
                                    'ABS_NBH'       => $row['NBH_' . $table],
                                    'ABS_VALIDATION'=> ($type=='ABSENCE'?1:0),
                                    'COL_NO'        => $row['ID_COL'],
                                    'TYA_NO'        => $row['ID_TYABS']
                                );
                                break;
                            case 'TYPE_ABSENCE':
                                $values[$type][] = array(
                                    'TYA_LIBELLE'       => $row['NOM_TYABS'],
                                    'TYA_COULEUR'       => $row['COULEUR_TYABS'],
                                    'TYA_COULEUR_DEM'   => $row['COULEUR_DEM_TYABS'],
                                    'TYA_NO'            => $row['ID_TYABS']
                                );
                                break;
                            case 'RAM':
                                $values[$type][] = array(
                                    'RAM_ANNEE'     => $row['ANNEE_RAM'],
                                    'RAM_MOIS'      => $row['MOIS_RAM'],
                                    'RAM_JOUR'      => $row['JOUR_RAM'],
                                    'RAM_NBH'       => $row['NBH_RAM'],
                                    'RAM_CLIENT'    => $row['CLIENT_RAM'],
                                    'RAM_VALIDATION'=> $row['validation'],
                                    'COL_NO'        => $row['ID_COL'],
                                    'COM_NO_APSA'   => $row['ID_COM_APSA'],
                                    'COM_NO_CLI'    => $row['ID_COM_CLI'],
                                );
                                break;
                            case 'COMMENTAIRE':
                                $values[$type][] = array(
                                    'COM_TEXTE' => addslashes($row['COM']),
                                    'COM_NO'    => $row['ID_COM']
                                );
                                break;
                        }
                    }
                }

                echo "<p>Anciennes valeurs récupérées</p>";
                foreach($values as $table => $value) {
                    $insert = "INSERT INTO " . $table . " (`" . implode("`, `", array_keys($value[0])) . "`) VALUES ";
                    foreach($value as $ligne) {
                        $insert .= "('" . implode("', '", $ligne) . "'), ";
                    }
                    $connexion_sortie->query(substr($insert, 0, -2)) or die(mysqli_error($connexion_sortie));
                }
                echo "<p>Ajout des données effectué</p>";

                /*
                DELETE FROM ABSENCE;
                DELETE FROM ARCHIVE;
                DELETE FROM ASSIGNER;
                DELETE FROM CONTACT_CLIENT;
                DELETE FROM CLIENT;
                DELETE FROM INTERNE;
                DELETE FROM EXTERNE;
                DELETE FROM RAM;
                DELETE FROM COLLABORATEUR;
                DELETE FROM COMMENTAIRE;
                DELETE FROM CONTACT_FOURNISSEUR;
                DELETE FROM CONTRAT;
                DELETE FROM FOURNISSEUR;
                DELETE FROM LIGNE_FRAIS;
                DELETE FROM MODE_REGLEMENT;
                DELETE FROM NOTE_FRAIS;
                DELETE FROM PROJET;
                DELETE FROM SPECIF_MENSUELLE;
                DELETE FROM TYPE_ABSENCE;
                DELETE FROM TYPE_AUTORISATION;
                DELETE FROM TYPE_FRAIS;
                */
            ?>
        </div>
    </body>
</html>