<?php
/*
    system supported from PHP7.X PHP8.X
    please activate [
        symlink, (default deisable, please enable)
        mysqli,  (please re-check)
        mongodb  (if any)
    ] if you are using shared/cloud hosting
    Getting settings files
*/
$env_file = file_get_contents(MODL_FOLDER . "/settings.json");
$e = json_decode($env_file);
$mod_file = file_get_contents(MODL_FOLDER . "/module.json");
$m = json_decode($mod_file);
/*
    Setting up timezone for global system
*/
date_default_timezone_set($e->time_zone);
/*
    Getting application lists based on app installed
    the proccess of fetching apps is not automated anymore
    since it's using some read resource on the mem and cpu,
    not ideal for huge traffic so we changed it.
    on module.json
*/
$GLOBALS['installed_apps'] = (array)$m->apps;
/*
    setting up urls from settings.json
*/
$GLOBALS['global_url']  = $e->url;
$GLOBALS['uri_folder']  = $e->folder;
$base_folder            = $e->folder;
/*
    setting up databases from settings.json
*/
$GLOBALS['database_setup'] = false;
$GLOBALS['mongodb'] = false;
$mongo_database  = [];
$global_database = [];
if(!is_array($e->setup->database))
    {if($e->setup->database != false)
        {$engine = $e->setup->database->engine;
        if($engine == 'mongo')
            {foreach($e->setup->database->settings as $k=>$v)
                {$mongo_database    = $mongo_database + [$v->instance => $v];}
                $GLOBALS['mongodb'] = $mongo_database;}
        elseif($engine == 'mysql')
            {foreach($e->setup->database->settings as $k=>$v){
                $global_database            = $global_database + [$v->instance => $v];}
                $GLOBALS['database_setup']  = $global_database;}}}
else
    {foreach($e->setup->database as $k=>$v)
        {$engine = $v->engine;
        if($engine == 'mongo')
            {foreach($v->settings as $k=>$v)
                {$mongo_database = $mongo_database + [$v->instance => $v];}}
        elseif($engine == 'mysql')
            {foreach($v->settings as $k=>$v)
                {$global_database = $global_database + [$v->instance => $v];}}}
    $GLOBALS['mongodb']        = $mongo_database;
    $GLOBALS['database_setup'] = $global_database;}

if($e->setup->database == false)
    {$GLOBALS['database_setup']  = false;}

function globalurl()
    {return $GLOBALS['global_url'] . $GLOBALS['uri_folder'];}
function urlfolder()
    {return $GLOBALS['uri_folder'];}
function dbsetup()
    {return $GLOBALS['database_setup'];}
function globapp()
    {return $GLOBALS['installed_apps'];}
function dbmongo()
    {return $GLOBALS['mongodb'];}
#DO NOT CHANGE
#DO NOT CHANGE
function htaccessCHANGE($base_folder)
{
    $ht_updates = fopen(ROOT_FOLDER . '/.htaccess', "w") or die("Permission problem please set to 755 and chwon www-data!");
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
/*
    removing bad fun,
    please delete .htaccess in root folder (NOT INSIDE MODULE)
    after re initializing the path or domain
*/
if (!file_exists(ROOT_FOLDER . '/.htaccess')) {
    htaccessCHANGE($base_folder);
}

#DO NOT CHANGE
include __DIR__ . '/log.php';
include __DIR__ . '/system-sub.php';
include __DIR__ . '/autoload.php';