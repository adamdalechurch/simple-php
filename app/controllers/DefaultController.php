<?php

namespace SimplePHP\App\Controllers;

class DefaultController{
    public function index(){
        echo 'Hello World!';
    }

    public function not_found(){
        echo '404';
    }
}