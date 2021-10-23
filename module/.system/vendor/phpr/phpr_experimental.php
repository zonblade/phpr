<?php

namespace phpr\experimental;

class level1 {
    public $syntax1;
    public $syntax2;

    function __construct(){

        $this->syntax1 = '$properties in class1';
        $this->syntax2 = new level2(); // instance of class2

    }
}

class level2 {

    public $syntax3;

    function __construct(){

        $this->syntax3 = '$cart in class2';

    }

}
