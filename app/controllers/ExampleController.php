<?php

namespace SimplePHP\App\Controllers;

use SimplePHP\Core\Infrastructure\Controller;
use SimplePHP\App\Data\Example;
use SimplePHP\App\Views\ExampleView;

class ExampleController extends Controller{
    public function __construct(){
        parent::__construct(new Example());
    }

    public function example(){
        return new ExampleView($this->_repo);
    }
}
?>