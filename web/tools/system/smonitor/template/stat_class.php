<?php

abstract class custom_statistic
{
    public $name;
    public $id;
    public $input;
    public $tool;

    public function __construct($name, $id, $tool, $input) {
        $this->id = $id;
        $this->name = $name;
        $this->input = $input;
        $this->tool = $tool;
    }

   

    public static function get_name() {        
    }

    function get_id() {
        return $this->id;
    }

    function get_input() {
        return $this->input;
    }

    function get_statistics() {
    }

    function set_id($id) {
        $this->id = $id;
    }

}
?>