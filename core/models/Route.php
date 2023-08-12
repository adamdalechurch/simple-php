<?php

namespace SimplePHP\Core\Models;

// windows path to composer:
class Route{
    public $name;
    public $controller;
    public $action;
    public $params;
    public $method;
    public $url;

    public function __construct($name, $controller, $action, $url, $params = null, $method = 'GET'){
        $this->name = $name;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
        $this->method = $method;
        $this->url = $url;
    }
}
?>