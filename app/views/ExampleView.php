<?php
namespace SimplePHP\App\Views;

use SimplePHP\Core\Infrastructure\View;
use SimplePHP\App\Data\Example;

class ExampleView extends View{
    public function __construct( Example $example ){
        parent::__construct( $example );
        echo $this->get_page();
    }   
}