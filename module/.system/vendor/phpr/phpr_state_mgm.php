<?php

namespace phpr\state;

function setMultiState($array)
{
    foreach ($array as $key => $val) {
        $GLOBALS[$key] = $val;
    }
}
function setSingleState($name,$value)
{
    $GLOBALS[$name] = $value;
}

function setStates($array)
{
    foreach ($array as $key => $val) {
        $GLOBALS[$key] = $val;
    }
}
function setState($array)
{
    foreach ($array as $key => $val) {
        $GLOBALS[$key] = $val;
    }
}


function getState($name)
{
    return $GLOBALS[$name];
}
