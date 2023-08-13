<?php
namespace SimplePHP\Core\Infrastructure;

use SimplePHP\Core\Data\DB;
use SimplePHP\Core\Models\Column;

abstract class Repository {
    private $_db;
    private $_table;
    private $_columns;
    private $_id_name;
    private $_auto_increment;
    private $_other_constraints;

    public function __construct($columns, $table, $id_name, 
    $auto_increment = true, $other_constraints = [], $debug = false){
        $this->_db = new DB($debug);
        $this->_table = $table;
        $this->_columns = $columns;
        $this->_id_name = $id_name;
        $this->_auto_increment = $auto_increment;
        $this->_other_constraints = $other_constraints;
    }

    public function set_debug($debug){
        $this->_db->set_debug($debug);
    }

    public function get_by_id($id){
        $where = $this->_id_name . " = " . $id;
        $result = $this->_db->select($this->_table, $this->_columns, $where);
        return $result[0];
    }

    private function bind_unique_key_getters(){
        $getters = [];
        foreach($columns as $column){
            if($column->unique_key){
                $getter = "get_by_" . $column->name;
                Closure::bind(function($val) use ($column){
                    $where = $column->name . " = " . $val;
                    $result = $this->_db->select($this->_table, $this->_columns, $where);
                    return $result[0];
                }, $this, $getter);
            }
        } 
    }

    private function bind_foreign_key_getters($foreign_keys){
        $getters = [];
        foreach($columns as $column){
            if($column->foreign_key){
                $getter = "get_" . $this->get_foreign_key_table_name($column->foreign_key);
                Closure::bind(function() use ($foreign_key){
                    $where = $foreign_key . " = " . $this->$foreign_key;
                    $result = $this->_db->select($this->_table, $this->_columns, $where);
                    return $result[0];
                }, $this, $getter);
            }
        } 
    }

    private function get_foreign_key_table_name($foreign_key){
        $foreign_key = explode("(", $foreign_key)[0];
        $foreign_key = explode(".", $foreign_key)[0];
        return $foreign_key;
    }

    // add paging params
    public function list($where = null, $order_by = null, $limit = null, $offset = null){
        return $this->_db->select($this->_table, $this->_columns, $where, [], $order_by, $limit, $offset);
    }

    public function insert($records){
       return $this->_db->insert($this->_table, $records, $this->decode_columns($this->_columns));
    }

    public function select($where){
        return $this->_db->select($this->_table, $this->pluck_column_names($this->_columns), $where);
    }

    public function update($columns, $where){
        $this->_db->update($this->_table, $this->pluck_column_names($this->_columns), $where);
    }

    public function delete($where){
        $this->_db->delete($this->_table, $where);
    }

    public function create_table(){
        $this->_db->create_table($this->_table, $this->decode_columns($this->_columns), $this->_auto_increment, $this->_other_constraints);
    }

    public function get_id_name(){
        return $this->_id_name;
    }

    public function get_columns(){
        return $this->decode_columns($this->_columns);
    }

    function decode_columns($columns){
        $columns = json_decode($columns);
        $column_objects = [];
        foreach ($columns as $column) {
            $column_objects[] = new Column($column);
        }
        return $column_objects;
    }

    private function pluck_column_names($columns){
        $columns = $this->decode_columns($columns);
        $column_names = [];
        foreach ($columns as $column) {
            $column_names[] = $column->name;
        }
        return $column_names;
    }
}
