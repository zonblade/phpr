<?php

namespace Processor;

function compress()
{
    /*
    func_get_args()[0] file path
    func_get_args()[1] file size
    func_get_args()[2] quality
    func_get_args()[3] filter
    func_get_args()[4] compression

     POINT
     BOX
     TRIANGLE
     HERMITE
     HANNING
     HAMMING
     BLACKMAN
     GAUSSIAN
     QUADRATIC
     CUBIC
     CATROM
     MITCHELL
     BESSEL
     SINC
     LANCZOS

    JPEG
    UNDEFINED
    NO
    BZIP
    FAX
    GROUP4
    JPEG2000
    LOSSLESSJPEG
    LZW
    RLE
    ZIP
    DXT1
    DXT3
    DXT5
    ZIPS
    PIZ
    PXR24
    B44
    B44A
    LZMA
    JBIG1
    JBIG2
    */

    try {
        $file_path = false;
        if (isset(func_get_args()[0])) {
            if (func_get_args()[0] !== false) {
                $file_path = func_get_args()[0];
            }
        }
        $filter_size        = 512;
        if (isset(func_get_args()[1])) {
            if (func_get_args()[1] !== false) {
                $filter_size = func_get_args()[1];
            }
        }
        $filter_quality     = 50;
        if (isset(func_get_args()[2])) {
            if (func_get_args()[2] !== false) {
                $filter_quality = func_get_args()[2];
            }
        }
        $filter_resize  = \Imagick::FILTER_LANCZOS;
        if (isset(func_get_args()[3])) {
            if (func_get_args()[3] !== false) {
                switch (strtoupper(func_get_args()[3])) {
                    case 'POINT':
                        $filter_resize  = \Imagick::FILTER_POINT;
                        break;
                    case 'BOX':
                        $filter_resize  = \Imagick::FILTER_BOX;
                        break;
                    case 'TRIANGLE':
                        $filter_resize  = \Imagick::FILTER_TRIANGLE;
                        break;
                    case 'HERMITE':
                        $filter_resize  = \Imagick::FILTER_HERMITE;
                        break;
                    case 'HANNING':
                        $filter_resize  = \Imagick::FILTER_HANNING;
                        break;
                    case 'HAMMING':
                        $filter_resize  = \Imagick::FILTER_HAMMING;
                        break;
                    case 'BLACKMAN':
                        $filter_resize  = \Imagick::FILTER_BLACKMAN;
                        break;
                    case 'GAUSSIAN':
                        $filter_resize  = \Imagick::FILTER_GAUSSIAN;
                        break;
                    case 'QUADRATIC':
                        $filter_resize  = \Imagick::FILTER_QUADRATIC;
                        break;
                    case 'CUBIC':
                        $filter_resize  = \Imagick::FILTER_CUBIC;
                        break;
                    case 'CATROM':
                        $filter_resize  = \Imagick::FILTER_CATROM;
                        break;
                    case 'MITCHELL':
                        $filter_resize  = \Imagick::FILTER_MITCHELL;
                        break;
                    case 'BESSEL':
                        $filter_resize  = \Imagick::FILTER_BESSEL;
                        break;
                    case 'SINC':
                        $filter_resize  = \Imagick::FILTER_SINC;
                        break;
                    default:
                        $filter_resize  = \Imagick::FILTER_LANCZOS;
                }
            }
        }

        $filter_compression = \Imagick::COMPRESSION_JPEG;
        if (isset(func_get_args()[4])) {
            if (func_get_args()[4] !== false) {
                switch (strtoupper(func_get_args()[4])) {
                    case 'UNDEFINED':
                        $filter_compression   = \Imagick::COMPRESSION_UNDEFINED;
                        break;
                    case 'NO':
                        $filter_compression   = \Imagick::COMPRESSION_NO;
                        break;
                    case 'BZIP':
                        $filter_compression   = \Imagick::COMPRESSION_BZIP;
                        break;
                    case 'FAX':
                        $filter_compression   = \Imagick::COMPRESSION_FAX;
                        break;
                    case 'GROUP4':
                        $filter_compression   = \Imagick::COMPRESSION_GROUP4;
                        break;
                    case 'JPEG2000':
                        $filter_compression   = \Imagick::COMPRESSION_JPEG2000;
                        break;
                    case 'LOSSLESSJPEG':
                        $filter_compression   = \Imagick::COMPRESSION_LOSSLESSJPEG;
                        break;
                    case 'LZW':
                        $filter_compression   = \Imagick::COMPRESSION_LZW;
                        break;
                    case 'RLE':
                        $filter_compression   = \Imagick::COMPRESSION_RLE;
                        break;
                    case 'ZIP':
                        $filter_compression   = \Imagick::COMPRESSION_ZIP;
                        break;
                    case 'DXT1':
                        $filter_compression   = \Imagick::COMPRESSION_DXT1;
                        break;
                    case 'DXT3':
                        $filter_compression   = \Imagick::COMPRESSION_DXT3;
                        break;
                    case 'DXT5':
                        $filter_compression   = \Imagick::COMPRESSION_DXT5;
                        break;
                    case 'ZIPS':
                        $filter_compression   = \Imagick::COMPRESSION_ZIPS;
                        break;
                    case 'PIZ':
                        $filter_compression   = \Imagick::COMPRESSION_PIZ;
                        break;
                    case 'PXR24':
                        $filter_compression   = \Imagick::COMPRESSION_PXR24;
                        break;
                    case 'B44':
                        $filter_compression   = \Imagick::COMPRESSION_B44;
                        break;
                    case 'B44A':
                        $filter_compression   = \Imagick::COMPRESSION_B44A;
                        break;
                    case 'LZMA':
                        $filter_compression   = \Imagick::COMPRESSION_LZMA;
                        break;
                    case 'JBIG1':
                        $filter_compression   = \Imagick::COMPRESSION_JBIG1;
                        break;
                    case 'JBIG2':
                        $filter_compression   = \Imagick::COMPRESSION_JBIG2;
                        break;
                    default:
                        $filter_compression   = \Imagick::COMPRESSION_JPEG;
                }
            }
        }
        if ($file_path !== false) {
            $gambar = new \Imagick($file_path);

            $lebar  = $gambar->getImageWidth();
            $tinggi = $gambar->getImageHeight();
            if ($lebar > $tinggi) {
                $gambar->resizeImage($filter_size, 0, $filter_resize, 1);
            } else {
                $gambar->resizeImage(0, $filter_size, $filter_resize, 1);
            }
            $gambar->setImageCompression(true);
            $gambar->setCompression($filter_compression);
            $gambar->setCompressionQuality($filter_quality);
            $gambar->writeImage($file_path);
            $gambar->clear();
            $gambar->destroy();
            return [
                'status' => [
                    'success' => true
                ],
                'reason' => 'Image has been modified!'
            ];
        } else {
            return [
                'status' => [
                    'success' => false
                ],
                'reason' => 'No file path!'
            ];
        }
    } catch (\Throwable  $e) {
        $getmsg = $e->getMessage();
        $msg = 'Class "Imagick" not found';
        if ($getmsg == $msg) {
            return [
                'status' => [
                    'success' => false
                ],
                'reason' => 'Imagick Plugin Not Installed!'
            ];
        } else {
            return [
                'status' => [
                    'success' => false
                ],
                'reason' => $getmsg
            ];
        }
    }
}

function watermark($img_path, $watermark_path, $img_quality, $img_filter)
{
    try {
        $filter_resize = \Imagick::FILTER_LANCZOS;
        if ($img_filter !== false) {
            switch (strtoupper($img_filter)) {
                case 'POINT':
                    $filter_resize  = \Imagick::FILTER_POINT;
                    break;
                case 'BOX':
                    $filter_resize  = \Imagick::FILTER_BOX;
                    break;
                case 'TRIANGLE':
                    $filter_resize  = \Imagick::FILTER_TRIANGLE;
                    break;
                case 'HERMITE':
                    $filter_resize  = \Imagick::FILTER_HERMITE;
                    break;
                case 'HANNING':
                    $filter_resize  = \Imagick::FILTER_HANNING;
                    break;
                case 'HAMMING':
                    $filter_resize  = \Imagick::FILTER_HAMMING;
                    break;
                case 'BLACKMAN':
                    $filter_resize  = \Imagick::FILTER_BLACKMAN;
                    break;
                case 'GAUSSIAN':
                    $filter_resize  = \Imagick::FILTER_GAUSSIAN;
                    break;
                case 'QUADRATIC':
                    $filter_resize  = \Imagick::FILTER_QUADRATIC;
                    break;
                case 'CUBIC':
                    $filter_resize  = \Imagick::FILTER_CUBIC;
                    break;
                case 'CATROM':
                    $filter_resize  = \Imagick::FILTER_CATROM;
                    break;
                case 'MITCHELL':
                    $filter_resize  = \Imagick::FILTER_MITCHELL;
                    break;
                case 'BESSEL':
                    $filter_resize  = \Imagick::FILTER_BESSEL;
                    break;
                case 'SINC':
                    $filter_resize  = \Imagick::FILTER_SINC;
                    break;
                default:
                    $filter_resize  = \Imagick::FILTER_LANCZOS;
            }
        }
        if(file_exists($img_path) && file_exists($watermark_path)){
            $quality = 100;
            if($img_quality!==false){
                if(is_int($img_quality)){
                    $quality = $img_quality;
                }
            }
            $gambar = new \Imagick();
            $gambar->readImage($img_path);
            $watermark = new \Imagick();
            $watermark->readImage($watermark_path);
            $lebar = $gambar->getImageWidth();
            $tinggi = $gambar->getImageHeight();
            $wWidth = $watermark->getImageWidth();
            $wHeight = $watermark->getImageHeight();
            if ($tinggi < $wHeight || $lebar < $wWidth) {
                $watermark->scaleImage($lebar, $tinggi);
                $wWidth = $watermark->getImageWidth();
                $wHeight = $watermark->getImageHeight();
            }
            $x = ($lebar - $wWidth) / 2;
            $y = ($tinggi - $wHeight) / 2;
            $gambar->compositeImage($watermark, \imagick::COMPOSITE_OVER, $x, $y);
            $gambar->setImageCompression($filter_resize);
            $gambar->setImageCompressionQuality($quality);
            $gambar->writeImage($img_path);
            $gambar->clear();
            $gambar->destroy();
            return [
                'status' => [
                    'success' => true
                ],
                'reason' => 'Watermark Added!'
            ];
        }else{
            return [
                'status' => [
                    'success' => true
                ],
                'reason' => 'File Not Found!'
            ];
        }
    } catch (\Throwable  $e) {
        $getmsg = $e->getMessage();
        $msg = 'Class "Imagick" not found';
        if ($getmsg == $msg) {
            return [
                'status' => [
                    'success' => false
                ],
                'reason' => 'Imagick Plugin Not Installed!'
            ];
        } else {
            return [
                'status' => [
                    'success' => false
                ],
                'reason' => $getmsg
            ];
        }
    }
}
