<?php

use phpr\page\render as render;
use phpr\apps\run    as apps;

# passing variables.
$context = [
    /* defining $content */
    'content'  => __DIR__.'/content.php',
    /* defininf $contoh_1 */
    'contoh_1' => 'hallo!'
];

# render the page.
render\response(
    'PASS',
    [
        'run'     => apps\AppPath('home','template/html'),
        'context' => $context
    ]
);