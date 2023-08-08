<?php
class DB {
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

    public function insert($table, $records, $columns){
       
        $mysqli = $this->_db;


        $sql = "INSERT INTO ".DBNAME.".$table (". $this->get_column_names_str($columns, false);
        $sql .= ") VALUES (". $this->get_insert_values($records, $columns, false);
        $sql .= ")";
        $this->debug = true;

        $this->debug($sql);

        $mysqli->query($sql);
        return $mysqli->insert_id;       
    }

    public function select($table, $columns = [], $where = [], $group_by = [], $order_by = [], $limit = []){
        $mysqli = $this->_db;

        $sql = "SELECT ";

        $sql .= $this->get_column_names_str($columns);

        $sql .= " FROM ".DBNAME.".$table";

        if($where && is_array($where) && count($where) > 0){
            $sql .= " WHERE ";
            foreach ($where as $key => $value) {
                $sql .= "$key = '".$mysqli->real_escape_string($value)."' AND ";
            }
        } 

        if($group_by && is_array($group_by) && count($group_by) > 0){
            $sql .= " GROUP BY ";
            foreach ($group_by as $key => $value) {
                $sql .= "$value, ";
            }
            $sql = rtrim($sql, ", ");
        }

        if($order_by && is_array($order_by) && count($order_by) > 0){
            $sql .= " ORDER BY ";
            foreach ($order_by as $key => $value) {
                $sql .= "$key $value, ";
            }
            $sql = rtrim($sql, ", ");
        }

        if($limit && is_array($limit) && count($limit) > 0){
            $sql .= " LIMIT ";
            foreach ($limit as $key => $value) {
                $sql .= "$value, ";
            }
            $sql = rtrim($sql, ", ");
        }
       
        $sql = rtrim($sql, " AND ");
        $result = $mysqli->query($sql);
        $rows = array();

        $this->debug($sql);

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }


        return $rows;
    }

    public function update($table, $columns, $where){
        $mysqli = $this->_db;

        $sql = "UPDATE ".DBNAME.".$table SET ";
        foreach ($columns as $key => $value) {
            $sql .= "$key = '$value', ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= " WHERE ";
        foreach ($where as $key => $value) {
            $sql .= "$key = '".$mysqli->real_escape_string($value)." AND ";
        }
        $sql = rtrim($sql, " AND ");
        $mysqli->query($sql);
    }

    public function delete($table, $where){
        $mysqli = $this->_db;
        $sql = "DELETE FROM ".DBNAME.".$table WHERE ";
        foreach ($where as $key => $value) {
            $sql .= "$key = '$value' AND ";
        }
        $sql = rtrim($sql, " AND ");
        $mysqli->query($sql);
    }

    public function query($sql){
        $mysqli = $this->_db;
        $result = $mysqli->query($sql);
        $rows = array();

        $this->debug($sql);

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function execute($sql){
        $mysqli = $this->_db;
        return $mysqli->query($sql);
    }

    // foreign keys, unique keys, and primary keys will be properties on columns array now
    public function create_table($table, $columns, $auto_increment = true, $other_constraints = []){
        $mysqli = $this->_db;

        $sql = "CREATE TABLE ".DBNAME.".$table (";
        foreach ($columns as $key => $column) {
            $sql .= "$column->name $column->type". ($column->length ? "($column->length)" : "");

            if($column->primary_key){
                $sql .= " PRIMARY KEY NOT NULL".($column->auto_increment ?  " AUTO_INCREMENT" : "" );
            } else if ($column->not_null){
                $sql .= " NOT NULL";
            }

            $sql .= $column->default ? " DEFAULT $column->default" : "";

            $sql .= ", ";
        }

        // foreign keys, unique keys, and primary keys will be properties on columns array now
        foreach ($columns as $key => $column) {
            if($column->foreign_key){
                $sql .= "FOREIGN KEY ($column->name) REFERENCES $column->foreign_key, ";
            }
            if($column->unique_key){
                $sql .= "UNIQUE KEY ($column->name), ";
            }
        }

        $sql = rtrim($sql, ", ");
        $sql .= ")";

        $sql .= " ENGINE=".DBENGINE." DEFAULT CHARSET=".DBCHARSET;

        if(count($other_constraints) > 0)
            $sql .= implode(";\n ", $other_constraints);

        $this->debug($sql);

        $mysqli->query($sql);
        
    }

    public function drop_table($table){
        $mysqli = $this->_db;
        $sql = "DROP TABLE ".DBNAME.".$table";
        $mysqli->query($sql);
    }

    public function truncate_table($table){
        $mysqli = $this->_db;
        $sql = "TRUNCATE TABLE ".DBNAME.".$table";
        $mysqli->query($sql);
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
        foreach ($records as $record) {
            $values .= "(";
            foreach ($columns as $column) {
                if($for_select || !$column->auto_increment) // auto_increment columns cannot be inserted into
                    $values .= "'".$this->_db->real_escape_string($record[$column->name])."', ";
            }
            $values = rtrim($values, ", ");
            $values .= "), ";
        }
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
