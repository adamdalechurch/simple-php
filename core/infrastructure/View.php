<?php
namespace SimplePHP\Core\Infrastructure;

class View {
    private $_items;
    private $_repo;
    private $_styles = [
        "https://classless.de/classless.css",
        "https://classless.de/addons/themes.css"
    ];

    private $_scripts = [
        "https://code.jquery.com/jquery-3.3.1.js",
        "https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"
    ];

    public function __construct($repo)
    {
        $this->_repo = $repo;
        $this->push_default_scripts();
        $this->push_default_styles();
    }

    public function create_form(){
        $columns = $this->_repo->get_columns();
        $form = "<form method='POST'>";
        foreach ($columns as $column) {
            if($column->auto_increment)
                continue;
            $form .= "<label for='$column->name'>$column->name</label>";
            $form .= "<input type='text' name='$column->name' id='$column->name' />";
        }
        $form .= $this->button("void(0)", "Create", "submit");
        $form .= "</form>";
        return $form;
    }

    public function update_form($id){
        $columns = $this->_repo->get_columns();
        $form = "<form method='PUT'>";
        foreach ($columns as $column) {
            $form .= "<label for='$column->name'>$column->name</label>";

            if($column->auto_increment)
                $form .= "<input type='hidden' name='$column->name' id='$column->name' />";
            else 
                $form .= "<input type='text' name='$column->name' id='$column->name' />";
        }
        $form .= $this->button("void(0)", "Update", "submit");
        $form .= "</form>";
        return $form;
    }

    public function list(){
        $columns = $this->_repo->get_columns();
        $this->_items = $this->_repo->list();
        $table = "<table id='".$this->get_class_name()."-list-table'>";
        $table .= "<thead>";
        $table .= "<tr>";
        foreach ($columns as $column) {
            $table .= "<th style='width:".$this->column_width($columns, $column)."%'>$column->name</th>";
        }
        $table .= "<th>Actions</th>";
        $table .= "</tr>";
        $table .= "</thead>";
        $table .= "<tbody>";
        foreach ($this->_items as $item) {
            $table .= "<tr>";
            foreach ($columns as $column) {
                $table .= "<td style='width:".$this->column_width($columns, $column)."%'>".$item[$column->name]."</td>";
            }
            $table .= "<td>";
            $table .= $this->button("window.location.href='?action=update&id=$item[id]'", "Update");
            $table .= $this->button("window.location.href='?action=delete&id=$item[id]'", "Delete");
            $table .= "</td>";
            $table .= "</tr>";
        }
        $table .= "</tbody>";
        $table .= "</table>";
        return $table;
    }

    public function head(){
        $head = "<head>";
        $head .= "<title>".$this->get_class_name()."</title>";
        foreach ($this->_styles as $style) {
            $head .= "<link rel='stylesheet' href='$style' />";
        }
        
        $head .= "</head>";
        return $head;
    }

    public function footer(){
        // with scripts:
        $footer = "";  
        foreach ($this->_scripts as $script) {
            $footer .= "<script src='$script'></script>";
        }
    }

    private function push_default_scripts(){
        foreach (scandir("core/js") as $file) {
            if($file == "." || $file == "..")
                continue;
            $this->_scripts[] = "core/js/$file";
        }
    }

    private function push_default_styles(){
        foreach (scandir("core/style") as $file) {
            if($file == "." || $file == "..")
                continue;
            $this->_styles[] = "core/style/$file";
        }
    }

    public function button($onclick, $text, $type = "button"){
        return "<button type='$type' onclick='$onclick'>$text</button>";
    }

    public function column_width($columns, $column){
        // calculate the width of a column based on the following
        // 1. INTS receive a column value mulitplier of 2
        // 2. VARCHARS receive a column value multiplier of 4
        // 3. TINYINTS receive a column value multiplier of 1
        $width_multiplier = 0;
        switch ($column->type) {
            case 'int':
                $width_multiplier = 2;
                break;
            case 'varchar':
                $width_multiplier = 4;
                break;
            case 'tinyint':
                $width_multiplier = 1;
                break;
            default:
                $width_multiplier = 4;
                break;
        }
        
        // base value should = .25
        return (100 / count($columns)) * ($width_multiplier * .25) ;
    }

    private function get_class_name(){
       return str_replace('Repository', '', get_class( $this->_repo ));
    }

    protected function get_page(){
        $page = "<html>";
        $page .= $this->head();
        $page .= "<body>";
        $page .= $this->create_form();
        $page .= "<div style='overflow-x:auto'>";
        $page .= $this->list();
        $page .= "</div>";
        $page .= $this->footer();
        $page .= "</body>";
        $page .= "</html>";
        return $page;
    }
}

?>

