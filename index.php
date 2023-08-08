<?php
// load constants from .env
DEFINE('DB_HOST', getenv('DB_HOST'));
DEFINE('DB_USER', getenv('DB_USER'));
DEFINE('DB_PASS', getenv('DB_PASS'));
DEFINE('DB_NAME', getenv('DB_NAME'));

// run migrations
require_once('migrate.php');

// load views
// require_once('views.php');
include_once('views/example.php');
?>