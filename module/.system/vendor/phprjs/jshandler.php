<?php
namespace phprjs\handler;

function INIT(){
    return header('Content-type: text/javascript');
}
function Wrapped($obj){
    return '<script type="text/javascript">'.$obj.'</script>';
}
function Naked($obj){
    return $obj;
}
function Import($content){
    return file_get_contents($content.'.js');
}
function Group($obj){
    $returned = '';
    $i=1;
    foreach($obj as $key){
        $returned .= "\n"."let func_".$i++."='imported';"."\n".$key."\n";
    }
    return $returned;
}
function GroupWrapped($obj){
    $returned = '';
    $i=1;
    foreach($obj as $key){
        $returned .= "\n"."let func_".$i++."='imported';"."\n".$key."\n";
    }
    return '<script type="text/javascript">'."\n"."\n".$returned."\n"."\n".'</script>';
}