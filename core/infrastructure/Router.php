<?php

namespace SimplePHP\Core\Infrastructure;

use SimplePHP\Core\Models\Route;

class Router{
    public $routes = array();
    public $route = false;
    public $params = array();
    // to do, make configurable...
    private $default_routes = [
        // new Route('home', 'DefaultController', 'index'),
        // new Route('404', 'DefaultController', 'not_found'),
        // new Route('example', 'ExampleController', 'example')
    ];

    public function __construct($routes = [], $url = null){
        if(!empty($routes)){
            foreach($routes as $route){
                $this->add_route($route);
            }
        }

        $this->default_routes[] = new Route('home', 'DefaultController', 'index', '/');
        $this->default_routes[] = new Route('404', 'DefaultController', 'not_found', '/404');


        foreach($this->default_routes as $route){
            $this->add_route($route);
        }

        if($url){
            $this->dispatch($url);
        }
    }

    // use typed properties when PHP 7.4 is available
    public function add_route(Route $route){
        $this->routes[] = $route;
    }

    public function dispatch(){
        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $url = $this->remove_query_string($url);
        $url = $this->remove_trailing_slash($url);
        $route = $this->get_route_from_url($url);

        // validate request_method:
        if($route && isset($route->request_method) && $route->method != 'ANY'){
			if($route->method != $_SERVER['REQUEST_METHOD']){
				// call method not allowed:
                $this->call_controller_method($this->get_route_from_url('/405'));
			}
		}
    
        if($route){
            $this->call_controller_method($route);
        } else {
            $this->call_controller_method($this->get_route_from_url('/404'));
        }
    }

    private function call_controller_method($route){
        $controller = "\\SimplePHP\\App\\Controllers\\" . $route->controller;
        $controller = new $controller();
        $action = $route->action;
        $controller->$action();
    }

    private function remove_query_string($url){
        if(strpos($url, '?') !== false){
            $url = substr($url, 0, strpos($url, '?'));
        }
        return $url;
    }

    private function remove_trailing_slash($url){
        if(substr($url, -1) == '/'){
            $url = substr($url, 0, -1);
        }
        return $url;
    }

    private function get_route_from_url($url){
        foreach($this->routes as $route){
            if($route->url == $url){
                return $route;
            }
        }
        return false;
    }

}
?>