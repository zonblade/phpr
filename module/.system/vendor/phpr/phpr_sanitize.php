<?php
namespace phpr\sanitize;

function filter($param,$val,$regex){
    if($regex==false){
        $regex='';
    }
    if ($param == 'int') {
        if (is_numeric($val)) {
            return $val;
        } else {
            return false;
        }
    } elseif ($param == 'secure') {
        $val = preg_replace('@[^a-zA-Z0-9\._\w\ ]+@i', '', $val);
        return $val;
    } elseif ($param == 'secure_under') {
        $val = preg_replace('@[^a-z\._\w\ ]+@i', '', $val);
        return $val;
    } elseif ($param == 'secure_under_no_space') {
        $val = preg_replace('@[^a-z\._]+@i', '', $val);
        return $val;
    } elseif ($param == 'dates') {
        $val = preg_replace('@[^A-Z0-9\.,:-_\w\ ]+@i', '', $val);
        return $val;
    } elseif ($param == 'email') {
        $val = preg_replace('@[^a-zA-Z0-9\@\.-_]+@i', '', $val);
        return $val;
    } elseif ($param == 'text') {
        $val = preg_replace('@[^a-zA-Z]+@i', '', $val);
        return $val;
    } elseif ($param == 'number') {
        $vavall2 = preg_replace('@[^0-9]+@i', '', $val);
        return $val;
    } elseif ($param == 'regex') {
        $val = preg_replace($regex, '', $val);
        return $val;
    } else {
        return $val;
    }
}

function lowcase($string){
    return \strtolower($string);
}