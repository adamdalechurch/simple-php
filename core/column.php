<?php
class Column{
    public $name;
    public $type;
    public $length;
    public $primary_key;
    public $auto_increment;
    public $foreign_key;
    public $unique_key;
    public $not_null;
    public $default;

    public function __construct($column){
        $this->name = $column->name;
        $this->type = $column->type;
        $this->length = isset($column->length) ? $column->length : false;
        $this->primary_key = isset($column->primary_key) ? $column->primary_key : false;
        $this->auto_increment = isset($column->auto_increment) ? $column->auto_increment : false;
        $this->foreign_key = isset($column->foreign_key) ? $column->foreign_key : false;
        $this->unique_key = isset($column->unique_key) ? $column->unique_key : false;
        $this->not_null = isset($column->not_null) ? $column->not_null : false;
        $this->default = isset($column->default) ? $column->default : false;
    }
}
?>