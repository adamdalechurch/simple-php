<?php

namespace SimplePHP\App\Controllers;

class DefaultController{
    public function index(){
        echo 'Hello World!';
    }

    public function not_found(){
        echo '404 Not Found';
    }

    public function method_not_allowed(){
		echo '405 Method Not Allowed';
	}
}