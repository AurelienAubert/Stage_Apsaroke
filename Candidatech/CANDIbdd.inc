<?php
function getBdd(){
    $bdd = new PDO("mysql:host=localhost; dbname=candidatech; charset=utf8;","root","");
    return$bdd;
}
