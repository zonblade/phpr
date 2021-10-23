<?php

namespace phpr\image\control;

/** NON API */
function size_calc($base64){
    return strlen(base64_decode($base64));
}

function set_image($base64, $path, $name)
{
    // base64 image code
    $base64_code = $base64;
    $img_type = strtok($base64, ';');
    $img_type = substr($img_type, strpos($img_type, "/") + 1);
    // create an image file
    $fp = fopen($path . $name . ".$img_type", "w+");
    // write the data in image file
    fwrite($fp, base64_decode($base64_code));
    // close an open file pointer
    fclose($fp);
    return [
        'name'      => $name,
        'suffix'    => $img_type, 
        'fullname'  => $name    . ".$img_type"  , 
        'filepath'  => $path    . $name         . ".$img_type"
    ];
}
/** FOR API */
function set_api_image($base64, $path, $name)
{
    // base64 image code
    $base64_code = $base64;
    $img_type = strtok($base64, ';');
    $img_type = substr($img_type, strpos($img_type, "/") + 1);
    // create an image file
    $fp = fopen($path . $name . ".$img_type", "w+");
    // write the data in image file
    fwrite($fp, base64_decode($base64_code));
    // close an open file pointer
    fclose($fp);
    return [
        'api'       => json_encode(['fullname'=>$name . ".$img_type"],JSON_PRETTY_PRINT),
        'name'      => $name,
        'suffix'    => $img_type, 
        'fullname'  => $name    . ".$img_type"  , 
        'filepath'  => $path    . $name         . ".$img_type"
    ];
}

function set_image_custom($base64, $path, $name, $ext)
{
    // base64 image code
    $base64_code = $base64;
    $img_type = $ext;
    // create an image file
    $fp = fopen($path . $name . ".$img_type", "w+");
    // write the data in image file
    $data = explode( ',', $base64_code );
    fwrite($fp, base64_decode($data[1]));
    // close an open file pointer
    fclose($fp);
    return [
        'name'      => $name,
        'suffix'    => $img_type, 
        'fullname'  => $name    . ".$img_type"  , 
        'filepath'  => $path    . $name         . ".$img_type"
    ];
}
function del_image($file_path)
{
    if (file_exists($file_path)) {
        unlink($file_path);
        return ['success' => true];
    } else {
        return ['success' => false];
    }
}

