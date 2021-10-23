<?php

namespace phpr\codec;

class hash{
    function internal($string){
        $result = str_replace('a','c',strtolower($string));
        $result = str_replace('e','d',$result);
        $result = str_replace('f','o',$result);
        $result = str_replace('m','8',$result);
        $result = str_replace('3','7',$result);
        $result = str_replace('7','3',$result);
    } 
}