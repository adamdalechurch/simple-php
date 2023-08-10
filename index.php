<?php
namespace SimplePHP;

use SimplePHP\Core\Migration;

// load constants from .env
require_once('core/config.php');

// load composer
require_once('vendor/autoload.php');

$migration = new Migration();

// load views
include_once('views/example.php');
?>