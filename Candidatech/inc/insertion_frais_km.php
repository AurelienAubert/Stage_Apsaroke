<?php
include_once 'suppression_donnees.php';
include_once 'connection.php';

//$collab = $_POST['col_id'];
$collab = null;
if (isset($_POST['col_id'])) {
    $collab = $_POST['col_id'];
} else {
    $collab = $_SESSION['col_id'];
}
$nsequentiel = $_POST['nsequentiel'];
$LigneMail = array();
$type_frais = " kilom�triques ";

//creation OU r�cup�ration du N� sequentiel
$query_getMneCol = "SELECT COL_MNEMONIC AS MNE FROM COLLABORATEUR WHERE COL_NO =" . $collab;
$result_getMneCol = $connexion->query ($query_getMneCol);
$row_Mne = $result_getMneCol->fetch_assoc ();

$query_getNseq = "SELECT MAX(SUBSTRING(NOF_NSEQUENTIEL,10,6)) AS NSEQ FROM NOTE_FRAIS";
$result_getNseq = $connexion->query ($query_getNseq);
$row_Nseq = $result_getNseq->fetch_assoc ();

if (!$nsequentiel)
{
    if ($row_Nseq['NSEQ'] == null)
    {
        $num = 3000;
        echo "ecrire : " . $num;
    }
    else
    {
        $num = (int)$row_Nseq['NSEQ'];
        $num++;
    }
    $nsequentiel = 'NF' . $_POST['annee'] . $row_Mne['MNE'] . $num;
}

//suppression de l'ancienne note de frais si elle existe
$query = "SELECT NOF_NO FROM NOTE_FRAIS WHERE NOF_ANNEE = " . $_POST['annee'] . " AND NOF_MOIS = " . $_POST['mois'] . " AND COL_NO = " . $collab." AND TYF_NO = 4";
$result = $connexion->query ($query);
if (mysqli_num_rows ($result) > 0)
{
    $NO = $result->fetch_assoc ();
    supprimer_note_frais ($NO);
}

//creation d'une nouvelle note de frais avec Id du col, ann�e, mois
$query = "INSERT INTO NOTE_FRAIS (NOF_ANNEE, NOF_MOIS, COL_NO, TYF_NO, NOF_NSEQUENTIEL) VALUES (" . $_POST['annee'] . "," . $_POST['mois'] . "," . $collab . ", 4, '" . $nsequentiel . "')";
$result = $connexion->query ($query);
$LigneMail[0] = '<p>Une demande de frais kilom�triques a �t� cr��e par ' . $_SESSION['nom'] . ' ' . $_SESSION['prenom'] . '</b> pour <b>' . nomMois($_POST['mois']) . ' ' . $_POST['annee'] . '</b><br/><br/>D�tails :</p>';

//creation de chaque ligne avec objet et le montant a l'unit�
$id = mysqli_insert_id ($connexion);
for ($i = 1; $i <= ($_POST['nbligne']); $i++)
{
    $totalkm = $_POST['km' . $i] * $_POST['taux' . $i];
    $query = "INSERT INTO LIGNE_FRAIS (LIF_JOUR, LIF_CLIENT, LIF_VILLE, LIF_KM, LIF_TAUX_KM, LIF_TOTAL_KM, NOF_NO) VALUES ('" . $_POST['date' . $i] . "', '" . $_POST['client' . $i] . "', '" . $_POST['ville' . $i] . "', '" . $_POST['km' . $i] . "', '" . $_POST['taux' . $i] . "', '" . $totalkm . "', " . $id . ")";
    $result = $connexion->query ($query);
    $LigneMail[$i] = '<p>le ' . $_POST['date' . $i] . ' <b>' . $_POST['client' . $i] . '</b> ' . $_POST['ville' . $i] . ' : ' . $_POST['km' . $i] . 'km x ' . $_POST['taux' . $i] . '&euro; = ' . number_format($totalkm, 2) . '&euro; </p>';
} 

// Envoi d'un mail de notification (si saisie collab)
if ($_POST['mode'] != 'voir' && $_POST['mode'] != 'modif') {
    include 'envoi_email_frais.php';
}
?>
<html>
    <div align='center' class=''>
        <font color='green'>Note de frais kilom�triques enregistr�e</font>
    </div>
</html>