<?php

namespace phpr\page\render;

class render
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

function http_response($array)
{
    foreach ($array as $key => $val) {
        if ($key == 404) {
            if ($val == true) {
                include __DIR__ . '/default_page/error.html';
                die();
            }
        }
        if ($key == 200) {
            include $val;
            die();
        }
    }
}

function http_uri(){
    $SERVERURI = parse_url($_SERVER['REQUEST_URI']);
    $SERVERURI = $SERVERURI['path'];
    return $SERVERURI;
}

function render_php($file_path, $context)
{
    $render = new render($file_path . '.php');
    if (isset($context)) {
        foreach ($context as $key_cu => $cu) {
            $render->set($key_cu, $cu);
        }
    }
    echo $render->render();
}

function path($path, $alias)
{
    return define($alias, $path);
}
function string($file_path)
{
    return $file_path . '.php';
}
function php($file_path)
{
    include $file_path . '.php';
}
function html($file_path)
{
    header('Content-type: text/html');
    include $file_path . '.html';
}
function css($file_path)
{
    header('Content-type: text/css');
    include $file_path . '.css';
}
function js($file_path)
{
    header('Content-type: text/javascript');
    include $file_path . '.js';
}
function POST($type, $param, $value, $file_path, $context)
{
    if (isset($_POST[$param])) {
        if ($_POST[$param] == $value) {
            if ($type == 'php') {
                render_php($file_path, $context);
                die();
            }
            if ($type == 'html') {
                html($file_path);
                die();
            }
            if ($type == 'css') {
                css($file_path);
                die();
            }
            if ($type == 'js') {
                js($file_path);
                die();
            }
        }
    }
}
function POST_HEADLESS($title,$subtitle,$param, $value, $file_path, $context)
{
    if (isset($_POST[$param])) {
        if ($_POST[$param] == $value) {
            ob_start();
            render_php($file_path, $context);
            $out1 = ob_get_contents();
            ob_end_clean();
            $data = [
                'title'=> $title,
                'info' => $subtitle,
                'url'  => $_SERVER['REQUEST_URI'],
                'html' => $out1
            ];
            echo json_encode($data, JSON_PRETTY_PRINT);
            die();
        }
    }
}
function GET($type, $param, $value, $file_path, $context)
{
    if ($type == 'redirect') {
        header('Location:' . $param);
    } elseif (isset($_GET[$param])) {
        if ($_GET[$param] == $value) {
            if ($type == 'php') {
                render_php($file_path, $context);
                die();
            }
            if ($type == 'html') {
                html($file_path);
                die();
            }
            if ($type == 'css') {
                css($file_path);
                die();
            }
            if ($type == 'js') {
                js($file_path);
                die();
            }
            if ($type == 'img/jpeg') {
                if (file_exists($file_path)) {
                    $fileSize = filesize($file_path);
                    header("Cache-Control: private");
                    header('Content-type: image/jpeg');
                    header("Content-Length: " . $fileSize);
                    // Output file.
                    readfile($file_path);
                    die();
                } else {
                    echo 'not found';
                    die();
                }
            }
            if ($type == 'img/png') {
                if (file_exists($file_path)) {
                    $fileSize = filesize($file_path);
                    header("Cache-Control: private");
                    header("Content-Type: image/png");
                    header("Content-Length: " . $fileSize);
                    // Output file.
                    readfile($file_path);
                    die();
                } else {
                    echo 'not found';
                    die();
                }
            }
        }
    }
}
function GET_FLAWLESS($type, $file_path)
{
    if ($type == 'php') {
        php($file_path);
        die();
    }
    if ($type == 'html') {
        html($file_path);
        die();
    }
    if ($type == 'css') {
        css($file_path);
        die();
    }
    if ($type == 'js') {
        js($file_path);
        die();
    }
    if ($type == 'img/jpeg') {
        if (file_exists($file_path)) {
            $fileSize = filesize($file_path);
            header("Cache-Control: private");
            header('Content-type: image/jpeg');
            header("Content-Length: " . $fileSize);
            // Output file.
            readfile($file_path);
            die();
        } else {
            echo 'not found';
            die();
        }
    }
    if ($type == 'img/png') {
        if (file_exists($file_path)) {
            $fileSize = filesize($file_path);
            header("Cache-Control: private");
            header("Content-Type: image/png");
            header("Content-Length: " . $fileSize);
            // Output file.
            readfile($file_path);
            die();
        } else {
            echo 'not found';
            die();
        }
    }
}

function response($val, $apptorun)
{
    if ($val == 'POST') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type       = '';
            $param      = '';
            $value      = '';
            $file_path  = '';
            $context    = [];

            foreach ($apptorun as $apt => $apv) {
                if ($apt == 'run') {
                    $file_path = $apv;
                }
                if ($apt == 'type') {
                    $type = $apv;
                }
                if ($apt == 'init') {
                    foreach ($apv as $apkey => $apval) {
                        $param = $apkey;
                        $value = $apval;
                    }
                }
                if ($apt == 'context') {
                    $context = $apv;
                }
            }
            POST($type, $param, $value, $file_path, $context);
        }
    }
    if ($val == 'GET') {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $type       = '';
            $param      = '';
            $value      = '';
            $file_path  = '';
            $context    = [];

            foreach ($apptorun as $apt => $apv) {
                if ($apt == 'run') {
                    $file_path = $apv;
                }
                if ($apt == 'type') {
                    $type = $apv;
                }
                if ($apt == 'init') {
                    foreach ($apv as $apkey => $apval) {
                        $param = $apkey;
                        $value = $apval;
                    }
                }
                if ($apt == 'context') {
                    $context = $apv;
                }
            }
            GET($type, $param, $value, $file_path, $context);
        }
    }
    if ($val == 'POST-HEADLESS') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type       = '';
            $param      = '';
            $value      = '';
            $file_path  = '';
            $context    = [];
            $title      = '';
            $subtitle   = '';

            foreach ($apptorun as $apt => $apv) {
                if ($apt == 'run') {
                    $file_path = $apv;
                }
                if ($apt == 'type') {
                    $type = $apv;
                }
                if ($apt == 'title') {
                    $title = $apv;
                }
                if ($apt == 'info') {
                    $subtitle = $apv;
                }
                if ($apt == 'init') {
                    foreach ($apv as $apkey => $apval) {
                        $param = $apkey;
                        $value = $apval;
                    }
                }
                if ($apt == 'context') {
                    $context = $apv;
                }
            }
            POST_HEADLESS($title,$subtitle,$param, $value, $file_path, $context);
        }
    }
    if ($val == 'GET-HEADLESS') {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $type       = '';
            $param      = '';
            $value      = '';
            $file_path  = '';
            $context    = [];

            foreach ($apptorun as $apt => $apv) {
                if ($apt == 'run') {
                    $file_path = $apv;
                }
                if ($apt == 'type') {
                    $type = $apv;
                }
                if ($apt == 'init') {
                    foreach ($apv as $apkey => $apval) {
                        $param = $apkey;
                        $value = $apval;
                    }
                }
                if ($apt == 'context') {
                    $context = $apv;
                }
            }
            GET($type, $param, $value, $file_path, $context);
        }
    }
    if ($val == 'PASS') {
        $file_path = '';
        $context   = '';
        foreach ($apptorun as $apx => $apn) {
            if ($apx == 'run') {
                $file_path = $apn;
            }
            if ($apx == 'context') {
                $context = $apn;
            }
        }
        render_php($file_path, $context);
    }
    if ($val == 'SWITCH') {
        // echo '<pre>';
        $switcher = 0;
        foreach ($apptorun as $swkey => $swval) {
            $slugx = $apptorun['param'];
            if ($swkey == 'switch') {
                foreach ($swval as $svkey => $svval) {
                    if ($svkey == $slugx) {
                        $switcher = 1;
                        $contexts = [];
                        $filetorun = [];
                        foreach ($svval as $salK => $salV) {
                            if ($salK == 'run') {
                                $filetorun = $salV;
                            }
                            if ($salK == 'context') {
                                $contexts = $salV;
                            }
                        }
                        render_php($filetorun, $contexts);
                    }
                }
            }
            if ($swkey == 'default') {
                if ($switcher == 0) {
                    $contexts = [];
                    $filetorun = [];
                    foreach ($swval as $salK => $salV) {
                        if ($salK == 'run') {
                            $filetorun = $salV;
                        }
                        if ($salK == 'context') {
                            $contexts = $salV;
                        }
                    }
                    render_php($filetorun, $contexts);
                }
            }
        }
    }
}

function packer($run, $context)
{
    return ['run' => $run, 'context' => $context];
}

# new system experimented

class _render_{
    private $type;
    
    function I($init=['type'=>'other']){
        $this->init = $init;
        return $this;
    }

    function R($render_type='PASS'){
        $this->type = $render_type;
        return $this;
    }

    function P($path){
        $this->path = $path;
        return $this;
    }

    function X($file_extension='php'){
        $this->extension = $file_extension;
        return $this;
    }

    function C($context=[]){
        $this->context = $context;
        return $this;
    }

    #long version
    
    function _init($init=['type'=>'other']){
        $this->init = $init;
        return $this;
    }

    function _method($render_type='PASS'){
        $this->type = strtoupper($render_type);
        return $this;
    }

    function _path($path){
        $this->path = $path;
        return $this;
    }

    function _filetype($file_extension='php'){
        $this->extension = $file_extension;
        return $this;
    }

    function _context($context=[]){
        $this->context = $context;
        return $this;
    }

    function _headless($title='headless',$info='headless'){
        $this->H_title = $title;
        $this->H_info = $info;
        $this->type = 'headless';
        $this->extension = 'php';
        return $this;
    }

    # non slash version
    
    function init($init=['type'=>'other']){
        $this->init = $init;
        return $this;
    }

    function method($render_type='PASS'){
        $this->type = strtoupper($render_type);
        return $this;
    }

    function path($path){
        $this->path = $path;
        return $this;
    }

    function filetype($file_extension='php'){
        $this->extension = $file_extension;
        return $this;
    }

    function context($context=[]){
        $this->context = $context;
        return $this;
    }

    function headless($title='headless',$info='headless'){
        $this->H_title = $title;
        $this->H_info = $info;
        $this->type = 'headless';
        $this->extension = 'php';
        return $this;
    }

    #render is render lol

    function render(){
        $val = 'PASS';
        if(isset($this->type)){
            $val = $this->type;
        }
        $ext = $this->extension;
        switch([$val,$ext]){
            case ['PASS','php']:
                #$->_method()->->_path()->_filetype()->_context()->render();
                render_php($this->path, $this->context);
                die();
                break;
            case ['POST','php']:
                #$->_init()->_method()->_path()->_filetype()->_context()->render();
                foreach ($this->init as $apkey => $apval) {
                    $param = $apkey;
                    $value = $apval;
                }
                POST($this->extension, $param, $value, $this->path, $this->context);
                die();
                break;
            case ['POST','php']:
                #$->_init()->_method()->_path()->_filetype()->_context()->render();
                foreach ($this->init as $apkey => $apval) {
                    $param = $apkey;
                    $value = $apval;
                }
                GET($this->extension, $param, $value, $this->path, $this->context);
                die();
                break;
            case ['headless','php']:
                #$->_init()->_headless($,$)->_path()->_context()->render();
                foreach ($this->init as $apkey => $apval) {
                    $param = $apkey;
                    $value = $apval;
                }
                POST_HEADLESS($this->H_title,$this->H_info,$param, $value, $this->path, $this->context);
                die();
                break;
            case ['PASS','js']:
                #$->_path()->_context()->render();
                header('Content-type: text/javascript');
                render_php($this->path, $this->context);
                include $file_path . '.js';
                die();
                break;
            case ['POST','js']:
                if($_SERVER['REQUEST_METHOD']=='POST'){
                    #$->_init()->_path()->_context()->render();
                    header('Content-type: text/javascript');
                    render_php($this->path, $this->context);
                    die();
                    break;
                }
            case ['GET','js']:
                if($_SERVER['REQUEST_METHOD']=='GET'){
                    #$->_init()->_path()->_context()->render();
                    header('Content-type: text/javascript');
                    render_php($this->path, $this->context);
                    die();
                    break;
                }
            default:
                render_php($this->path, $this->context);
                die();
                break;
        }
    }
}