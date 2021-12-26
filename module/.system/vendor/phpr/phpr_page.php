<?php

namespace phpr\page\display;

class pages
{
    protected $_file;
    protected $_data = array();

    public function __construct($file = null)
    {
        $this->_file = $file;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    public function render()
    {
        extract($this->_data);
        ob_start();
        include($this->_file);
        return ob_get_clean();
    }
}


function strips($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function Alias($get_path, $alias)
{
    return $GLOBALS["$alias"] = $get_path;
}

function Display($get_path, $file_path)
{
    if ($file_path != false) {
        if ($get_path == false) {
            include $file_path;
            die();
        } elseif (strpos($get_path, '?') !== false) {
            $GetParam = strips($get_path, "?", "=");
            $ParamVal = substr($get_path, strpos($get_path, "=") + 1);
            if (isset($_GET[$GetParam])) {
                if ($_GET[$GetParam] == $ParamVal) {
                    include $file_path;
                    die();
                }
            }
        }
    }
}


// older route system //

function URI($url_path, $file_path)
{
    // server path //
    $SERVERURI = parse_url($_SERVER['REQUEST_URI']);
    $SERVERURI = $SERVERURI['path'];
    $SERVERPOD = $SERVERURI;
    $SERVERPOD = ltrim($SERVERPOD, '/');
    $SERVERPOD = explode("/", $SERVERPOD);
    // print_r($SERVERPOD);
    // url path //
    if ($url_path == false) {
        include $file_path;
        die();
    } else {
        if (urlfolder() != '/') {
            $this_url = str_replace(urlfolder(), '', $SERVERURI);
        } else {
            $this_url = $SERVERURI;
            $url_path = '/' . $url_path;
            $url_path = substr($url_path, 1);
        }
        if (strpos($url_path, '[SLUG]/') !== false) {
            $SLUG = str_replace('[SLUG]/', '', $url_path);
            $SLUG = preg_replace('#' . $SLUG . '#', '', $SERVERURI);
            $SLUG = preg_replace('#' . urlfolder() . '#', '', $SLUG);
            $SLUG = str_replace('/', '', $SLUG);

            $this_url = str_replace($SLUG, '[SLUG]', $SERVERURI);
            if (urlfolder() != '/') {
                $this_url = str_replace(urlfolder(), '', $this_url);
            }
        } elseif (strpos($url_path, '[INT]/') !== false) {
            $INT = str_replace('[INT]/', '', $url_path);
            $INT = preg_replace('#' . $INT . '#', '', $SERVERURI);
            $INT = preg_replace('#' . urlfolder() . '#', '', $INT);
            $INT = str_replace('/', '', $INT);
            $INT = preg_replace('/[^0-9.]+/', '', $INT);

            $this_url = str_replace($INT, '[INT]', $SERVERURI);
            if (urlfolder() != '/') {
                $this_url = str_replace(urlfolder(), '', $this_url);
            }
        }
        switch ($this_url) {
            case $url_path:
                if (strpos($url_path, '[SLUG]/') !== false) {
                    define('SLUG', $SLUG);
                    $GLOBALS['SLUG'] = $SLUG;
                } elseif (strpos($url_path, '[INT]/') !== false) {
                    define('INT', $INT);
                    define('NUM', $INT);
                    $GLOBALS['INT'] = $INT;
                }
                if (file_exists($file_path)) {
                    include $file_path;
                    die();
                } else {
                    include __DIR__ . '/default_page/error_route.html';
                    die();
                }
                die();
                break;
            default:
        }
    }
}

function RunAll($default)
{
    /*
    foreach (glob(__DIR__."/apps/.../urls.php") as $urls)
    {
        include $urls;
    }
    */
    $global_apps_array = globapp();
    foreach ($global_apps_array as $appname => $apppath) {
        if ($appname != $default && $appname != 'phpr-default') {
            include $apppath . 'urls.php';
        }
    }
    foreach ($global_apps_array as $appname => $apppath) {
        if ($appname == $default) {
            include $apppath . 'urls.php';
        }
    }
}

function response($val, $apptorun)
{
    $url_path   = $apptorun[0];
    $file_path  = $apptorun[1];
    if ($val == 'POST') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            URI($url_path, $file_path);
        }
    }
    if ($val == 'GET') {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            URI($url_path, $file_path);
        }
    }
}

function request($array)
{
    foreach ($array as $key => $val) {
        $url_path = $key;
        $file_path = $val;
        URI($url_path, $file_path);
    }
}

function route($array)
{
    $SERVERURI = parse_url($_SERVER['REQUEST_URI']);
    $SERVERURI = $SERVERURI['path'];
    if (urlfolder() != '/') {
        $this_url = str_replace(urlfolder(), '', $SERVERURI);
    } else {
        $this_url = $SERVERURI;
    }
    foreach ($array as $key => $val) {
        $url_path = $key;
        $file_path = $val;
        foreach ($array as $key => $val) {
            $url_path = $key;
            $file_path = $val;
            URI($url_path, $file_path);
        }
    }
}

// newer route system //

function URIV2($arr_path_init, $app_path, $SERVERPOD)
{
    $arr_path = urlfolder() . '/' . $arr_path_init;
    $SERVOPATH = explode("/", $arr_path);

    //server pod re arraged!
    
    foreach ($SERVERPOD as $key => $value) {
        if (is_null($value) || empty($value))
            unset($SERVERPOD[$key]);
    }
    array_splice($SERVERPOD,0,0);
    foreach ($SERVOPATH as $key => $value) {
        if (is_null($value) || empty($value))
            unset($SERVOPATH[$key]);
    }
    array_splice($SERVOPATH,0,0);

    //processing//
    $SERVORENS = [];
    $SERVOOBJT = [];
    $SERVORENS_CHECK = [];
    // echo '<pre>';
    foreach ($SERVOPATH as $key => $val) {
        if (strpos($val, '[:') !== false) {
            // echo 'A. '.$val.'<br>';
            foreach ($SERVERPOD as $key2 => $val2) {
                if ($key == $key2) {
                    // echo 'B. '.$val.'<br>';
                    array_push($SERVORENS, [$key => $val2]);
                    if ($val != $val2) {
                        /** param */
                        $param = substr($val, strpos($val, "]") + 1);
                        $param = substr($param, strpos($param, ":") + 1);
                        /** regex filtering */
                        if(strpos($param, '(') !== false){
                            $regex = substr($param, strpos($param, "(") + 1);
                            $regex = strtok($regex, ')');
                            $param = strtok($param, '(');
                        }
                        /** get real value */
                        $hue = strtok($val, ']');
                        $hue = substr($hue, strpos($hue, ":") + 1);
                        if ($param == 'int') {
                            if (is_numeric($val2)) {
                                // push into the values! //
                                array_push($SERVORENS_CHECK, [$key => $val2]);
                                @$SERVOOBJT[$hue] = $val2;
                                // $render->set($hue, $val2);
                            } else {
                                // pull original data only //
                                array_push($SERVORENS_CHECK, [$key => $val2]);
                                @$SERVOOBJT[$key] = $val;
                            }
                        } elseif ($param == 'secure') {
                            $val2 = preg_replace('@[^a-zA-Z0-9\_\.]+@i', '', $val2);
                            // push into the values! //
                            array_push($SERVORENS_CHECK, [$key => $val2]);
                            // $render->set($hue, $val2);
                            @$SERVOOBJT[$hue] = $val2;
                        } elseif ($param == 'secure_small') {
                            $val2 = preg_replace('@[^a-zA-Z0-9\_\.]+@i', '', $val2);
                            $val2 = strtolower($val2);
                            // push into the values! //
                            array_push($SERVORENS_CHECK, [$key => $val2]);
                            // $render->set($hue, $val2);
                            @$SERVOOBJT[$hue] = $val2;
                        } elseif ($param == 'text') {
                            $val2 = preg_replace('@[^a-zA-Z]+@i', '', $val2);
                            // push into the values! //
                            array_push($SERVORENS_CHECK, [$key => $val2]);
                            // $render->set($hue, $val2);
                            @$SERVOOBJT[$hue] = $val2;
                        } elseif ($param == 'number') {
                            $val2 = preg_replace('@[^0-9]+@i', '', $val2);
                            // push into the values! //
                            array_push($SERVORENS_CHECK, [$key => $val2]);
                            // $render->set($hue, $val2);
                            @$SERVOOBJT[$hue] = $val2;
                        } elseif ($param == 'regex') {
                            $val2 = preg_replace($regex, '', $val2);
                            // push into the values! //
                            array_push($SERVORENS_CHECK, [$key => $val2]);
                            // $render->set($hue, $val2);
                            @$SERVOOBJT[$hue] = $val2;
                        } else {
                            // push into the values! //
                            array_push($SERVORENS_CHECK, [$key => $val2]);
                            // $render->set($hue, $val2);
                            @$SERVOOBJT[$hue] = $val2;
                        }
                    } else {
                        // pull original data only //
                        array_push($SERVORENS_CHECK, [$key => $val2]);
                        @$SERVOOBJT[$key] = $val;
                    }
                }
            }
        } else {
            // echo 'ELSE. '.$val.'<br>';
            array_push($SERVORENS_CHECK, [$key => $val]);
            array_push($SERVORENS, [$key => $val]);
            array_push($SERVOOBJT, [$key => $val]);
        }
    }
    $SERVORENS = array_map(function ($a) {
        return array_pop($a);
    }, $SERVORENS);
    $SERVORENS_CHECK = array_map(function ($a) {
        return array_pop($a);
    }, $SERVORENS_CHECK);

    // recheck again if you see this message future me :)
    // because im not sure it is going to work well or not, ty
    // (2021 Dec answer)
    // f me : i already check it and it's going well, but i need to improve
    // please come back and fix this future me :)

    // array_filter($SERVOPATH, fn ($value) => !is_null($value) && $value !== '');
    // array_filter($SERVERPOD, fn ($value) => !is_null($value) && $value !== '');
    // array_filter($SERVORENS, fn ($value) => !is_null($value) && $value !== '');
    // array_filter($SERVOOBJT, fn ($value) => !is_null($value) && $value !== '');
    
    //removing any empty key
    foreach ($SERVOPATH as $key => $value) {
        if (is_null($value) || empty($value))
            unset($SERVOPATH[$key]);
    }
    array_splice($SERVOPATH,0,0);
    //removing any empty key
    foreach ($SERVERPOD as $key => $value) {
        if (is_null($value) || empty($value))
            unset($SERVERPOD[$key]);
    }
    array_splice($SERVERPOD,0,0);
    //removing any empty key
    foreach ($SERVORENS as $key => $value) {
        if (is_null($value) || empty($value))
            unset($SERVORENS[$key]);
    }
    array_splice($SERVORENS,0,0);
    //removing any empty key
    foreach ($SERVOOBJT as $key => $value) {
        if (is_null($value) || empty($value))
            unset($SERVOOBJT[$key]);
    }
    array_splice($SERVOOBJT,0,0);

    $slug = (object)$SERVOOBJT;
    $jsn_a = json_encode($SERVERPOD);
    $jsn_b = json_encode($SERVORENS);
    $b64_A = base64_encode($jsn_a);
    $b64_B = base64_encode($jsn_b);
    if ($b64_A == $b64_B) {
        if (file_exists($app_path)) {
            $render = new pages($app_path);
            foreach($slug as $slug_key=>$slug_val){
                if(!is_numeric($slug_key)){
                    $render->set($slug_key, $slug_val);
                }
            }
            $render->set('slug', $slug);
            echo $render->render();
            die();
        } else {
            include __DIR__ . '/default_page/error_route.html';
            die();
        }
        die();
    } else {
        return false;
    }
}

function routev2($array)
{
    if(parse_url($_SERVER['REQUEST_URI']) == false){
        include __DIR__ . '/default_page/error_uri.html';
        die();
    }else{
        $SERVERURI = parse_url($_SERVER['REQUEST_URI']);
        $SERVERURI = $SERVERURI['path'];
        $SERVERPOD = $SERVERURI;
        $SERVERPOD = ltrim($SERVERPOD, '/');
        $SERVERPOD = explode("/", $SERVERPOD);
        foreach ($array as $key2 => $val2) {
            $url_path = $key2;
            $file_path = $val2;
            URIV2($url_path, $file_path, $SERVERPOD);
        }
    }
}
