<?php

namespace phpr\redir;


function location($location='.',$delay=false,$second=0){
    if($delay){
        sleep($second);
        header("Location: ".$location);
        die();
    }else{
        header("Location: ".$location);
        die();
    }
}

function refresh($location='.',$delay=false,$second=0){
    if($delay){
        header("refresh:".$second.";url=".$location);
    }else{
        header("refresh:0;url=".$location);
    }
}