<?php
$type = $_POST['type'];
$action = $_POST['action'];

if($type == 'col_interne')
{
    if($action == 'ajout')
    {
        header('Location:ajout.php?type=collaborateur_interne');
    }
    if($action == 'suppression')
    {
        header('Location:suppression.php?type=collaborateur_interne');
    }
    if($action == 'affichage')
    {
        header('Location:recherche.php?type=collaborateur_interne&action=affichage');
    }
    if($action == 'modification')
    {
        header('Location:recherche.php?type=collaborateur_interne&action=modification');
    }
}
if($type == 'col_externe')
{
    if($action == 'ajout')
    {
        header('Location:ajout.php?type=collaborateur_externe');
    }
    if($action == 'suppression')
    {
        header('Location:suppression.php?type=collaborateur_externe');
    }
    if($action == 'affichage')
    {
        header('Location:recherche.php?type=collaborateur_externe&action=affichage');
    }
    if($action == 'modification')
    {
        header('Location:recherche.php?type=collaborateur_externe&action=modification');
    }
}
?>