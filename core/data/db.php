<?php

namespace SimplePHP\Core\Data;

use SimplePHP\Core\Traits\Queryable;
use SimplePHP\Core\Traits\Executable;
use SimplePHP\Core\Traits\ImplementsTable;

use \mysqli;

class DB {
    use Queryable, Executable, ImplementsTable;
    private $_db;
    private $debug;

    public function __construct($debug = false){
        $this->connect();
        $this->debug = $debug;
    }

    public function __destruct(){
        $this->_db->close();
    }

    public function set_debug($debug){
        $this->debug = $debug;
    }

    private function get_column_names_str($columns, $for_select = true){
        $cols = "";
        if($columns && is_array($columns) && count($columns) > 0){
            // $cols = implode(", ", array_map(function($column){
            foreach ($columns as $column) {
                if($for_select || !$column->auto_increment) // auto_increment columns cannot be inserted into
                    $cols .= $column->name . ", ";
            }
            $cols = rtrim($cols, ", ");
        } else {
            $cols = "*";
        }

        return $cols;
    }

    private function get_insert_values_str($records, $columns){
        $values = "";
        $values .= "(";
        if($columns && is_array($columns) && count($columns) > 0){
            foreach ($columns as $column) {
                if(!$column->auto_increment)
                    $values .= "'".$this->_db->real_escape_string($records[$column->name])."', ";
            }
        } else {
            foreach ($records as $key => $value) {
                $values .= "'".$this->_db->real_escape_string($value)."', ";
            }
        }
        $values = rtrim($values, ", ");
        $values .= "), ";
        $values = rtrim($values, ", ");
        return $values;
    }

    public function connect(){
        $this->_db = new mysqli(DBHOST, DBUSER, DBPASS);
        if ($this->_db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
    }

    private function debug($sql){
        if($this->debug){
            echo $sql;
            exit();
        }
    }
}
