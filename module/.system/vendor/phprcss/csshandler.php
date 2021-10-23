<?php
namespace phprcss\handler;

function INIT(){
    return header('Content-type: text/css');
}
function Wrapped($obj){
    return '<style>'.$obj.'</style>';
}
function Naked($obj){
    return $obj;
}