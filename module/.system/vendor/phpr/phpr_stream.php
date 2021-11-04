<?php

namespace phpr\stream;

function file($path_to_content,$mime)
{
    header("Content-Type: ".$mime);
    header("Content-Length: " . filesize($path_to_content));
    readfile($path_to_content);
    die();
}

function read($path_to_content,$mime)
{
    header("Content-Type: ".$mime);
    header("Content-Length: " . filesize($path_to_content));
    readfile($path_to_content);
    die();
}

function get($path_to_content,$mime)
{
    header("Content-Type: ".$mime);
    header("Content-Length: " . filesize($path_to_content));
    echo file_get_contents($path_to_content);
    die();
}

function e($content="hello world",$header=false)
{
    if($header===false){
        echo $content;
    }else{
        header("Content-type:".$header);
        echo $content;
    }
}

function json($json,$pretty=true){
    header("Content-type: application/json");
    if($pretty===true){
        echo json_encode($json,JSON_PRETTY_PRINT);
    }else{
        echo json_encode($json);
    }
}

class data{

    function file($file){
        $this->file = $file;
        return $this;
    }

    function charset($charset='UTF-8'){
        $this->charset = $charset;
        return $this;
    }

    function method($method='get'){
        $this->method = $method;
        return $this;
    }

    function live(){
        $arr_type = [
            'text/css',
            'application/javascript',
            'text/html',
            'text/plain'
        ];
        if($this->method==='echo'){ 
            header("Content-type:".$this->header."; charset: ".$this->charset.";");
            echo $this->content;
        }else{

        }
    }
}