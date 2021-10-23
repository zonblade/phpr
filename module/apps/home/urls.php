<?php

use phpr\state          as state;
use phpr\page\display   as display;
use phpr\Apps\Run       as apps;
# init
$homeapps = new apps\_apps_('home');
# temporary data
state\setMultiState(
    [
        'key' => 'value'
    ]
);
# visual router
display\routev2(
    [
        '' => $apps->app('front'),
    ]
);
