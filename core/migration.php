<?php
include_once("db.php");

class Migration {
    private $entities = ['Example'];
    private $db;
    
    public function __construct(){
        $this->db = new DB();
        if($this->create_db());
            $this->up();
    }

    private function create_db(){
        $sql = "CREATE DATABASE IF NOT EXISTS ".DBNAME;        
        return $this->db->execute($sql);
    }

    private function up(){
        foreach ($this->entities as $entity) {
            require_once("data/$entity.php");
            $entity = new $entity();
            $entity->create_table();
        }
    }

    public function down(){
        foreach ($this->entities as $entity) {
            require_once("data/$entity.php");
            $entity = new $entity();
            $entity->drop_table();
        }
    }
}