<?php
namespace phpr\fetch\native;

function get($param){
    return $_GET[$param];
}

function post($param){
    return $_POST[$param];
}

function exist($param){
    if(isset($_POST[$param])){
        return true;
    }else{
        return false;
    }
}
function post_exist($param){
    if(isset($_POST[$param])){
        if(!empty($_POST[$param])){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
function get_exist($param){
    if(isset($_GET[$param])){
        return true;
    }else{
        return false;
    }
}

function path($param){
    $SERVERURI  = parse_url($_SERVER['REQUEST_URI']);
    $PATHURI    = $SERVERURI['path'];
    $QUERYURI   = $SERVERURI['query'];
    switch($param){
        case 'query':
            return $QUERYURI;
            break;
        case 'path':
            return $PATHURI;
            break;
        default:
            return $PATHURI;
    }
}