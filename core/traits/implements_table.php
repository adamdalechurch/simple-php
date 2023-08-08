<?php
trait ImplementsTable {
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
    
}