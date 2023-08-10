<?php
namespace SimplePHP\Core;

use SimplePHP\Core\DB;

class Migration {
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
        //loop through all classes in the data namespace
        foreach (glob("data/*.php") as $filename) {
            $repo = NAMESPACE_DATA.basename($filename, ".php");
            $repo = new $repo();
            $repo->create_table();
        }
    }

    public function down(){
        foreach ($this->entities as $entity) {
            $repo = NAMESPACE_DATA.basename($filename, ".php");
            $repo = new $repo();
            $repo->drop_table();
        }
    }
}