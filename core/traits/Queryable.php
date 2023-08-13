<?php
namespace SimplePHP\Core\Traits;

trait Queryable{
    public function select($table, $columns = [], $where = [], $group_by = [], $order_by = [], $offset = null, $limit = []){
        $mysqli = $this->_db;

        $sql = "SELECT " . $this->get_column_names_str($columns);
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

        if($offset){
            $sql .= " OFFSET $offset";
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

    
}
