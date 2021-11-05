<?php
$env_file = file_get_contents(MODL_FOLDER . "/settings.env");
$e = json_decode($env_file);
#TIMEZONE
date_default_timezone_set($e->time_zone);
#APP INSIDE APPS FODLER!
$global_apps_array = array();
foreach (glob(APPS . "/*") as $urls) {
    $last_word_start = strrpos($urls, '/') + 1;
    $last_word = substr($urls, $last_word_start);
    $global_apps_array = $global_apps_array + [$last_word=>APPS . '/' . $last_word . '/'];
}
$GLOBALS['installed_apps'] = $global_apps_array;
#YOUR MAIN URLS
$GLOBALS['global_url']  = $e->url;
$GLOBALS['uri_folder']  = $e->folder;
$base_folder            = $e->folder;
#DATABASE SETUP
$GLOBALS['database_setup'] = false;
$GLOBALS['mongodb'] = false;
$mongo_database  = [];
$global_database = [];
// echo '<pre>';
if(!is_array($e->setup->database)){
    if($e->setup->database != false){
        $engine = $e->setup->database->engine;
        if($engine == 'mongo'){
            foreach($e->setup->database->settings as $k=>$v){
                $mongo_database = $mongo_database + [$v->instance => $v];
            }
            $GLOBALS['mongodb'] = $mongo_database;
        }elseif($engine == 'mysql'){
            foreach($e->setup->database->settings as $k=>$v){
                $global_database = $global_database + [$v->instance => $v];
            }
            $GLOBALS['database_setup']  = $global_database;
        }
    }
}else{
    foreach($e->setup->database as $k=>$v){
        $engine = $v->engine;
        if($engine == 'mongo'){
            foreach($v->settings as $k=>$v){
                $mongo_database = $mongo_database + [$v->instance => $v];
            }
        }elseif($engine == 'mysql'){
            foreach($v->settings as $k=>$v){
                $global_database = $global_database + [$v->instance => $v];
            }
        }
    }
    $GLOBALS['mongodb']         = $mongo_database;
    $GLOBALS['database_setup']  = $global_database;
}
// print_r($GLOBALS['installed_apps']);
// die();
if($e->setup->database == false){
    $GLOBALS['database_setup']  = false;
}

function globalurl(){
    return $GLOBALS['global_url'] . $GLOBALS['uri_folder'];
}
function urlfolder(){
    return $GLOBALS['uri_folder'];
}
function dbsetup(){
    return $GLOBALS['database_setup'];
}
function globapp(){
    return $GLOBALS['installed_apps'];
}
function dbmongo(){
    return $GLOBALS['mongodb'];
}
#DO NOT CHANGE
#DO NOT CHANGE
function htaccessCHANGE($base_folder)
{
    $ht_updates = fopen(ROOT_FOLDER . '/.htaccess', "w") or die("Unable to open file!");
    $ht_new     = "
    php_value upload_max_filesize 64M
    php_value post_max_size 64M
    
    <IfModule mod_rewrite.c>
    DirectoryIndex index.php

    # set your rewrite base
    RewriteEngine on
    #EDSTART#
    # Edit this in your init method too if you script lives in a subfolder
    RewriteBase " . $base_folder . "
    
    # Deliver the folder or file directly if it exists on the server
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
     
    # Push every request to index.php
    RewriteRule ^(.*)$ index.php [QSA]
    </IfModule>
      ";
    $ht_text    = $ht_new;
    fwrite($ht_updates, $ht_text);
    fclose($ht_updates);
}
if (!file_exists(ROOT_FOLDER . '/.htaccess')) {
    htaccessCHANGE($base_folder);
} elseif (file_exists(ROOT_FOLDER . '/.htaccess') && strtotime(filemtime(ROOT_FOLDER . '/.htaccess')) <= strtotime('-5 Days', strtotime(date('d-m-Y')))) {
    htaccessCHANGE($base_folder);
}


#DO NOT CHANGE
include __DIR__ . '/system-sub.php';
include __DIR__ . '/autoload.php';
