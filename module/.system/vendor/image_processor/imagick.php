<?php

namespace image\imagick;

function compress_n_place($quality,$size,$path){
    $im = new \Imagick($path);
    $imwidth = $im->getImageWidth();
    $imheight = $im->getImageHeight();
    if ($imwidth > $imheight)
        $im->resizeImage($size, 0, \Imagick::FILTER_LANCZOS, 1);
    else
        $im->resizeImage(0, $size, \Imagick::FILTER_LANCZOS, 1);

    $im->setImageCompression(true);
    $im->setCompression(\Imagick::COMPRESSION_JPEG);
    $im->setCompressionQuality($quality);

    $im->writeImage($path);
    $im->clear();
    $im->destroy();
}

function compress_output($imgpath){

}

function place_watermark($imgpath,$wmpath){
    
}