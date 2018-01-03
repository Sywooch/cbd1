<?php



function zeropad($number, $length = 2){
    $after = isset(explode('.', $number)[1]) ? explode('.', $number)[1] : 0;
    $after = str_pad($after, $length, '0');
    $before = explode('.', $number)[0];

    return (int)$before . '.' . $after;
}


function STOP($data){
    var_dump($data);
    die();
}

function DMF($data){
    STOP($data);
}