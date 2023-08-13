<?php

namespace SimplePHP\App\Controllers;

use SimplePHP\Core\Infrastructure\Controller;
use SimplePHP\App\Data\Example;

class ExampleController extends Controller{
    public function __construct(){
        parent::__construct(new Example());
    }

    public function example(){
        return $this->json_response($this->_repo->list());
    }
}
?>