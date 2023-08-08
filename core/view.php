<?php
require_once("core/api.php");

class View {
    private $repo;
    private $items;
    private $RepoClass;
    private $api;

    public function __construct($RepoClass){
        $this->RepoClass = $RepoClass;
        require_once("data/".strtolower($RepoClass).".php");
        $this->repo = new $RepoClass();
        $this->api = new Api($this->repo);
    }

    public function create_form(){
        $columns = $this->repo->get_columns();
        $form = "<form method='POST'>";
        foreach ($columns as $column) {
            if($column->auto_increment)
                continue;
            $form .= "<label for='$column->name'>$column->name</label>";
            $form .= "<input type='text' name='$column->name' id='$column->name' />";
        }
        $form .= "<input type='submit' value='Create' />";
        $form .= "</form>";
        return $form;
    }

    public function update_form($id){
        $columns = $this->repo->get_columns();
        $form = "<form method='PUT'>";
        foreach ($columns as $column) {
            $form .= "<label for='$column->name'>$column->name</label>";

            if($column->auto_increment)
                $form .= "<input type='hidden' name='$column->name' id='$column->name' />";
            else 
                $form .= "<input type='text' name='$column->name' id='$column->name' />";
        }
        $form .= "<input type='submit' value='Update' />";
        $form .= "</form>";
        return $form;
    }

    public function list(){
        $columns = $this->repo->get_columns();
        $this->items = $this->repo->list();
        $table = "<table id='$this->RepoClass-list-table'>";
        $table .= "<thead>";
        $table .= "<tr>";
        foreach ($columns as $column) {
            $table .= "<th>$column->name</th>";
        }
        $table .= "<th>Actions</th>";
        $table .= "</tr>";
        $table .= "</thead>";
        $table .= "<tbody>";
        foreach ($this->items as $item) {
            $table .= "<tr>";
            foreach ($columns as $column) {
                $table .= "<td>".$item[$column->name]."</td>";
            }
            $table .= "<td>";
            $table .= "<a href='?action=update&id=$item[id]'>Update</a>";
            $table .= "<a href='?action=delete&id=$item[id]'>Delete</a>";
            $table .= "</td>";
            $table .= "</tr>";
        }
        $table .= "</tbody>";
        $table .= "</table>";
        return $table;
    }
}

?>

