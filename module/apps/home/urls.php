<?php

use phpr\state          as state;
use phpr\page\display   as display;
use phpr\apps           as apps;
# init
$homeapps = new apps\name('home');
# temporary data
state\setMultiState(
    [
        'key' => 'value'
    ]
);
# visual router
display\routev2(
    [
        '' => $homeapps->app('front'),
    ]
);
