<?php

namespace phpr\parse;

function toString($item, $sparator = ',')
{
    if (is_array($item)===true) {
        return implode($sparator, $item);
    } else {
        return (string)$item;
    }
}

function toArray($item, $sparator = ','){
    if(json_decode($item)===null){
        return json_decode($item);
    }else{
        return explode($sparator,$item);
    }
}

function toJson($array, $pretty=true){
    if($pretty===true){
        return json_encode($array,JSON_PRETTY_PRINT);
    }else{
        return json_encode($array);
    }
}

function toInt($string){
    return (int)$string;
}

function toFloat($string){
    return (float)$string;
}