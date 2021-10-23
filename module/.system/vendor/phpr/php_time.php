<?php

namespace phpr\time;

function all()
{
    $sparator = (object)[
        'date'=>'-',
        'time'=>':',
    ];
    if(isset(func_get_args()[0])){
        $sparator = (object)func_get_args()[0];
    }
    $t = microtime(true);
    $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
    $d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));
    $main = $d->format("Y-m-d/H:i:s/u");
    $main = explode('/',$main);
    $year = explode('-',$main[0]);
    $time = explode(':',$main[1]);
    $micr = $main[2];
    $formatted = $year[0].'-'.$year[1].'-'.$year[2].'T'.$time[0].':'.$time[1].':'.$time[2];
    $build = (object)[
        'epoch'=>strtotime(date('Y-m-d')),
        'format'=>(object)[
            'ymd'=>date('y'.$sparator->date.'m'.$sparator->date.'d',strtotime($formatted)),
            'Ymd'=>date('Y'.$sparator->date.'m'.$sparator->date.'d',strtotime($formatted)),
            'Ymd'=>date('Y'.$sparator->date.'m'.$sparator->date.'d',strtotime($formatted)),
            'YMd'=>date('Y'.$sparator->date.'M'.$sparator->date.'d',strtotime($formatted)),
            'YMD'=>date('Y'.$sparator->date.'M'.$sparator->date.'D',strtotime($formatted)),
            'dmy'=>date('d'.$sparator->date.'m'.$sparator->date.'y',strtotime($formatted)),
            'dmY'=>date('d'.$sparator->date.'m'.$sparator->date.'Y',strtotime($formatted)),
            'dMY'=>date('d'.$sparator->date.'M'.$sparator->date.'Y',strtotime($formatted)),
            'DMY'=>date('D'.$sparator->date.'M'.$sparator->date.'Y',strtotime($formatted)),
            'losless'=>$year[0].$year[1].$year[2].$time[0].$time[1].$time[2].$micr
        ],
        'date'=>(object)[
            'y'=>$year[0],
            'm'=>$year[1],
            'd'=>$year[2]
        ],
        'time'=>(object)[
            'h'=>$time[0],
            'i'=>$time[1],
            's'=>$time[2],
            'u'=>$micr
        ]
    ];
    return (object)[
        'DateTime'=>$d,
        'Build'=>$build
    ];
}