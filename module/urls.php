<?php
use phpr\page\display as display;
include __DIR__.'/root.php';
/*
=== env ===

script or anything
before apps,
goes to root.php

===========
*/
display\RunAll('home');
display\Display(false,__DIR__.'/.system/vendor/phpr/default_page/error.html');
