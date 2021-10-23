<?php
namespace phprjs\jq\ajax;

class jaAjax {
    private $api;

    function __construct($api){
        $this->api = $api;
    }

    function Requests($method,$array){
        /*
        [
            'jq'=>[
                'element'   =>'.dom',
                'type'      =>'click',
                'param'     =>'',
            ],
            'ajax'=>[
                'data'      =>[
                    '1'=>'code',
                    '2'=>'code',
                ],
                'after'   =>'alert(result);'
            ]
        ]
        */
        $api    = $this->api;
        $dom    = '';
        $type   = '';
        $param  = '';
        $success= '';
        $data   = '';
        foreach($array as $key1 => $val1){
            if($key1=='jq'){
                foreach($val1 as $keyJQ => $valJQ ){
                    if($keyJQ == 'element'){
                        $dom    = $valJQ;
                    }
                    if($keyJQ == 'type'){
                        $type    = $valJQ;
                    }
                    if($keyJQ == 'param'){
                        $param    = $valJQ;
                    }
                }
            }
            if($key1=='ajax'){
                foreach($val1 as $keyAJ => $valAJ){
                    if($keyAJ == 'data'){
                        foreach($valAJ as $keyDT => $valDT){
                            $data .= $keyDT.':'.$valDT.',';
                        }
                    }
                    if($keyAJ == 'after'){
                        $success = $valAJ;
                    }
                }
            }
        }
        $data = substr(trim($data), 0, -1);
        $code   = "
        $('$dom').on('$type', function(){
            $param
            $.ajax({
                url:'$api',
                method:'$method',
                data:{
                    $data
                },
                success: function(result){
                    $success
                }
            });
        });
        ";
        return $code;
    }
}

function INIT(){
    $code = '<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>';
    return $code;
}
function POST($api,$array){
    $a = new jaAjax($api);
    return $a->Requests('POST',$array);
}

function GET($api,$array){
    $a = new jaAjax($api);
    return $a->Requests('GET',$array);
}