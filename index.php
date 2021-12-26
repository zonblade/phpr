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
/*Root Folder        :: */ define("ROOT_FOLDER" ,__DIR__                    ,true);
/*Model Folder       :: */ define("MODL_FOLDER" ,__DIR__.'/module'          ,true);
/*APPS Folder        :: */ define("APPS"        ,__DIR__.'/module/apps'     ,true);
/*URLS Folder        :: */ define("URLS"        ,__DIR__.'/module/urls.php' ,true);
/*Include System     :: */ include __DIR__.'/module/.system/system.php';
/*/////////////////////////////////////////////////*/

set_error_handler(function ($errno, $errstr, $errfile, $errline) 
    {if (0 === error_reporting()) {return false;}
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);});
    
try   {include __DIR__.'/module/urls.php';}
catch (\Throwable $e){ logSystem($e->getMessage());}
catch (\ErrorException $e){ logSystem($e->getMessage());}
