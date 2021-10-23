<?php

namespace phpr\file\control;

use function phpr\fetch\native\exist;

function require_file($dir,$filename='urls.php') {
    // require all php files
    $scan = glob("$dir".DIRECTORY_SEPARATOR."*");
    foreach ($scan as $path) {
        if (preg_match('/'.$filename.'$/', $path)) {
            require_once $path;
        }
        elseif (is_dir($path)) {
            require_file($path);
        }
    }
}

function check($param, $allowed_ext){
    if(isset($_FILES[$param])){
        $fileName   = $_FILES[$param]["name"]; // The file name
        // $fileType = $_FILES[$param]["type"]; // The type of file it is
        // $fileSize = $_FILES[$param]["size"]; // File size in bytes
        // $fileErrorMsg = $_FILES[$param]["error"]; // 0 for false... and 1 for true
        $ext = explode('.',$fileName);
        $ext = array_reverse($ext);
        $ext = strtolower($ext[0]);
        if(in_array($ext,$allowed_ext)){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function place($param, $dir, $new_name, $allowed_ext, $custom_ext)
{
    $fileName   = $_FILES[$param]["name"]; // The file name
    $fileTmpLoc = $_FILES[$param]["tmp_name"]; // File in the PHP tmp folder
    // $fileType = $_FILES[$param]["type"]; // The type of file it is
    // $fileSize = $_FILES[$param]["size"]; // File size in bytes
    // $fileErrorMsg = $_FILES[$param]["error"]; // 0 for false... and 1 for true
    $ext = explode('.',$fileName);
    $ext = array_reverse($ext);
    $ext = strtolower($ext[0]);
    if(in_array($ext,$allowed_ext)){
        if($custom_ext !== false){
            $ext = $custom_ext;
        }
        if(in_array($ext,$allowed_ext)){
            if (!$fileTmpLoc) { // if file not chosen
                return [
                    'code'=>2,
                    'reason'=>'no files',
                    'data'=>$_FILES[$param]
                ];
            }else{
                if (move_uploaded_file($fileTmpLoc, "$dir/$new_name.$ext")) {
                    return [
                        'code'=>1,
                        'reason' => 'upload success',
                        'filename' => $new_name.'.'.$ext
                    ];
                } else {
                    return [
                        'code'=>0,
                        'reason'=>'upload failed'
                    ];
                }
            }
        }else{
            return [
                'code'=>0,
                'reason'=>'files extension not allowed'
            ];
        }
    }else{
        return [
            'code'=>0,
            'reason'=>'files extension not allowed'
        ];
    }
}

function del_file($file_path)
{
    if (file_exists($file_path)) {
        unlink($file_path);
        return ['success' => true];
    } else {
        return ['success' => false];
    }
}