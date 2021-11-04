<?php
/*///////////////////////////////////////////////////


        All the things around this
        framework can be found at our
        official repository

        https://github.com/zonblade/phpr

        We advice you not to change index.php 
        default settings, if you're not sure
        what to do.


///////////////////////////////////////////////////*/
/*Start Session      :: */ session_start();
/*Activate Reporting :: */ error_reporting(E_ALL);
/*Error Display      :: */ ini_set('display_errors', 'On');
/*Limit Header to    :: */ header('X-Frame-Options: SAMEORIGIN');
/*Root Folder        :: */ define("ROOT_FOLDER",__DIR__);
/*Model Folder       :: */ define("MODL_FOLDER",__DIR__.'/module');
/*/////////////////////////////////////////////////*/

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (0 === error_reporting()) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});
define("APPS",__DIR__.'/module/apps'       );
define("URLS",__DIR__.'/module/urls.php'   );
include __DIR__.'/module/.system/system.php';
try{
    include __DIR__.'/module/urls.php';
}catch (\Throwable $e){
    include __DIR__.'/module/.system/error.php';
}catch(\ErrorException  $e){
    include __DIR__.'/module/.system/error.php';}
