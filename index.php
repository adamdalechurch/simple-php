<?php
// load constants from .env
require_once('.env');

// run migrations
require_once('core/migration.php');

$migration = new Migration();

// load views
include_once('views/example.php');
?>