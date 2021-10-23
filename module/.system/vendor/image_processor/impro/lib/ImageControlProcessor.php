<?php

namespace Image;

class placer
{
    private $working_dir;

    function __construct($working_dir)
    {
        $this->dir = $working_dir;
    }

    function post()
    {
        /* mendefinisikan nama parameter */
        /* 
        cheat seet param :
        post($input_name,$filename,$file_extention)
        $input_name         = func_get_args()[0]
        $filename           = func_get_args()[1]
        $file_extention     = func_get_args()[2]
        $allowed_extention  = func_get_args()[3] (array)
        $upload_limit       = func_get_args()[4]
        $replace_file       = func_get_args()[5]
        */
        $allowed_extention = [];
        $upload_limit      = 0;
        if (isset(func_get_args()[3])) {
            if (func_get_args()[3] !== false) {
                $allowed_extention = func_get_args()[3];
            }
        }
        if (isset(func_get_args()[4])) {
            if (func_get_args()[4] !== false) {
                $upload_limit      = (float)func_get_args()[4];
            }
        }
        $replace_file = true;
        if (isset(func_get_args()[5])) {
            if (func_get_args()[5] !== false) {
                $replace_file = func_get_args()[5];
            }
        }

        $FILES = $_FILES['image_upload'];
        if (isset(func_get_args()[0])) {
            if (func_get_args()[0] !== false) {
                $FILES = $_FILES[func_get_args()[0]];
            }
        }
        /* mendefinisikan basis jika tidak ada args */
        if (strpos($FILES['type'], 'image') !== false) {
            $file_dir = $this->dir;
            $filename = explode('.', $FILES['name']);
            $filename = array_reverse($filename);
            $file_ext = $filename[0];
            $filename = str_replace('.' . $file_ext, '', $filename);
            /* nama file */
            $can_upload = true;
            if (!empty($allowed_extention)) {
                if (!in_array($file_ext, $allowed_extention)) {
                    $can_upload = false;
                }
            }
            if ($upload_limit !== 0) {
                if ($FILES['size'] >= $upload_limit) {
                    $can_upload = false;
                }
            }
            if ($can_upload) {
                if (isset(func_get_args()[1])) {
                    if (func_get_args()[1] !== false) {
                        $filename = func_get_args()[1];
                    }
                }
                /* ekstensi file */
                if (isset(func_get_args()[2])) {
                    if (func_get_args()[2] !== false) {
                        $file_ext = func_get_args()[2];
                    }
                }
                /* menghilangkan double slash */
                $file_path = $file_dir . '/' . $filename . '.' . $file_ext;
                $file_path = str_replace('//', '/', $file_path);
                if (file_exists($file_dir)) {
                    if ($replace_file) {
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                        move_uploaded_file($FILES["tmp_name"], $file_path);
                        return [
                            'status' => [
                                'success' => true
                            ],
                            'image' => [
                                'name' => $filename . '.' . $file_ext,
                                'path' => $file_path
                            ],
                            'reason' => 'Image successfully uploaded!'
                        ];
                    } else {
                        return [
                            'status' => [
                                'success' => false
                            ],
                            'reason' => 'Image not meant to be replaced!'
                        ];
                    }
                } else {
                    return [
                        'status' => [
                            'success' => false
                        ],
                        'reason' => 'Directory not found!'
                    ];
                }
            } else {
                return [
                    'status' => [
                        'success' => false
                    ],
                    'reason' => 'Upload Denied!'
                ];
            }
        } else {
            return [
                'status' => [
                    'success' => false
                ],
                'reason' => 'Not an Image!'
            ];
        }
    }

    function base64()
    { // base64 image code
        /*
        func_get_args()[0] = file base 64 wajib!
        func_get_args()[1] = nama file tanpa ekstensi! bisa di false kan
        func_get_args()[2] = ekstensi file, bisa di false kan
        func_get_args()[3] = size limit, bisa di false kan
        func_get_args()[4] = apakan recrusive atau gakboleh replace?, bisa di false kan
        */
        if (isset(func_get_args()[0])) {
            $file_dir = $this->dir;
            $base64 = func_get_args()[0];
            $name   = 'image-uploaded-D_' . date('D-m-y') . '_' . date('H-i-s');
            if (isset(func_get_args()[1])) {
                if (func_get_args()[1] !== false) {
                    $name   = func_get_args()[1];
                }
            }
            $img_size_usr = 0;
            if (isset(func_get_args()[3])) {
                if (func_get_args()[3] !== false) {
                    $img_size_usr   = func_get_args()[3];
                }
            }
            $upload_n_replace = false;
            if (isset(func_get_args()[4])) {
                if (func_get_args()[4] !== false) {
                    $upload_n_replace   = func_get_args()[4];
                }
            }
            $img_size = strlen(base64_decode($base64));
            $can_upload = true;
            if ($img_size_usr > 0) {
                if ($img_size >= $img_size_usr) {
                    $can_upload = false;
                }
            }
            $data = explode(',', $base64);
            $data_info = $data[0];
            $data_exts = strtok($data_info, ';');
            $data_exts = substr($data_info, strpos($data_info, "/") + 1);
            if (isset(func_get_args()[2])) {
                if (func_get_args()[2] !== false) {
                    $data_exts = func_get_args()[2];
                }
            }
            $data_imgs = $data[1];
            $file_path = $file_dir . $name . ".$data_exts";
            if ($upload_n_replace) {
                if (file_exists($file_path)) {
                    $can_upload = false;
                }
            }
            if ($can_upload) {
                $fp = fopen($file_path, "w+");
                fwrite($fp, base64_decode($data_imgs));
                fclose($fp);
                return [
                    'status' => [
                        'success' => true
                    ],
                    'image' => [
                        'name' => $name . ".$data_exts",
                        'path' => $file_path
                    ],
                    'reason' => 'Image successfully uploaded!'
                ];
            } else {
                return [
                    'status' => [
                        'success' => false
                    ],
                    'reason' => 'Upload Denied!'
                ];
            }
        } else {
            return [
                'status' => [
                    'success' => false
                ],
                'reason' => 'No Base64 detected!'
            ];
        }
    }
}