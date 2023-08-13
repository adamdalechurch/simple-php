<?php

namespace SimplePHP\Core\Infrastructure;

use SimplePHP\Core\Infrastructure\Repository;
use SimplePHP\Core\Infrastructure\View;

class Controller{
    private $_repository;
    private $_view;

    public function __construct(Repository $repo, View $view = null){
        $this->_repo = $repo;
        $this->_view = $view;
    }

    protected function json_reponse($json){
        // to do, refine.
        return json_encode($json);
    }
} 

?>
