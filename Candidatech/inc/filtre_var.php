<?php

function fPOST($var){
    $fvar = filter_input(INPUT_POST, $var, FILTER_SANITIZE_SPECIAL_CHARS);
    return $fvar;
}
function fGET($var){
    $fvar = filter_input(INPUT_GET, $var, FILTER_SANITIZE_SPECIAL_CHARS);
    return $fvar;
}
