<?php
require_once("core/repository.php");

class Example extends Repository {
    private $table = "example";
    private $id_name = "id";
    private $cols = '[
        {
            "name": "id",
            "type": "int",
            "length": 11,
            "primary_key": true,
            "auto_increment": true
        },
        {
            "name": "name",
            "type": "varchar",
            "length": 255,
            "unique_key": true
        }
    ]';

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}