<?php
require_once(__DIR__."/../widget/widget.php");
require_once(__DIR__."/gauge_widget.php");

class shmem_widget extends gauge_widget
{
	public static $ignore = 0;

    function __construct($array) {
        parent::__construct($array);
    }

    function get_name() {
        return "Shared memory widget";
    }

    function echo_content() {
        $shmem = mi_command("get_statistics", array("statistics" => array("shmem:")), $_SESSION['boxes'][0]['mi_conn'], $errors);
        $shmem_value = $shmem["shmem:used_size"];
		$shmem_max = $shmem["shmem:max_used_size"];
		$this->display_chart($this->id, $this->title, $shmem_value, $shmem_max);
    }

    public static function new_form($params = null) {  
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
    }
}