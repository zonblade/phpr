<?php

namespace phpr\qrcode;

function generate($array)
{
    $data = '';
    $size = '200x200';
    $logo = FALSE;
    foreach ($array as $key => $val) {
        if ($key == 'data') {
            $data = $val;
        }
        if ($key == 'size') {
            $size = $val;
        }
        if ($key == 'logo') {
            $logo = $val;
        }
    }

    //header('Content-type: image/png');
    $QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $size . '&chl=' . urlencode($data));
    if ($logo !== FALSE) {
        $logo = imagecreatefromstring(file_get_contents($logo));

        $QR_width = imagesx($QR);
        $QR_height = imagesy($QR);

        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);

        // Scale logo to fit in the QR Code
        $logo_qr_width = $QR_width / 3;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;

        imagecopyresampled($QR, $logo, $QR_width / 3, $QR_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    }
    // Enable output buffering
    ob_start();
    imagepng($QR);
    // Capture the output and clear the output buffer
    $imagedata = ob_get_clean();
    $imdata = base64_encode($imagedata);
    $imgreturn = 'data:image/png;base64,'.$imdata.'"';
    return $imgreturn;
}
