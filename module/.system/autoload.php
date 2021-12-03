<?php
function loadModule(){
    $package = file_get_contents(MODL_FOLDER . "/module.json");
    $package = json_decode($package);
    return $package->system;
}
$autoload = loadModule();

foreach (glob(__DIR__."/vendor/*/*.php") as $filename)
{
    $include = false;
    foreach($autoload as $package){if(strpos($filename, $package) !== false):$include == true;endif;}
    if($include == true):include $filename;endif;
}
foreach (glob(__DIR__."/vendor/*/*/*.php") as $filename)
{
    $include = false;
    foreach($autoload as $package){if(strpos($filename, $package) !== false):$include == true;endif;}
    if($include == true):include $filename;endif;
}