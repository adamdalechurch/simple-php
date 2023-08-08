<?php
include_once("../core/repository.php");
class Example extends Repository {
    private $table = "example";
    private $id_name = "id";
    private $cols = '[
        {
            "name": "id",
            "type": "INT",
            "primary_key": true,
            "auto_increment": true
        },
        {
            "name": "name",
            "type": "VARCHAR(255)"
        },
        {
            "name": "created_at",
            "type": "TIMESTAMP",
            "default": "CURRENT_TIMESTAMP"
        },    
    ]';

    public function __construct(){
        parent::__construct($this->cols, $this->table, $this->id_name);
    }
}