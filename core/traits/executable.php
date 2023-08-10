<?php
namespace SimplePHP\Core\Traits;

trait Executable{
    public function insert($table, $records, $columns){
       
        $mysqli = $this->_db;

        $sql = "INSERT INTO ".DBNAME.".$table (". $this->get_column_names_str($columns, false);
        $sql .= ") VALUES (". $this->get_insert_values_str($records, $columns, false);
        $sql .= ")";

        $this->debug($sql);

        $mysqli->query($sql);
        return $mysqli->insert_id;       
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
    
    public function execute($sql){
        $mysqli = $this->_db;
        $this->debug($sql);
        return $mysqli->query($sql);
    }
}
?>