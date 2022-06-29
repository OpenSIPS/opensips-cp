<?php
require_once(__DIR__."/../widget/widget.php");
require_once(__DIR__."/gauge_widget.php");

class multi_gauge_widget extends gauge_widget
{
    public $max;
	public static $ignore = 0;

    function __construct($array) {
        parent::__construct($array);
		$this->max = $array['widget_max'];
    }

    function get_name() {
        return "Percent widget";
    }

    function echo_content() {
		//consoole_log(mi_command("get_statistics", array("statistics" => array("real_used_size")), $_SESSION['boxes'][0]['mi_conn'], $errors));	
		$chart_type = explode(":", $this->chart)[0];
		$chart_type .= ":";
		$stat_res = mi_command("get_statistics", array("statistics" => array($chart_type)), $_SESSION['boxes'][0]['mi_conn'], $errors);
		$this->display_chart($this->title, $stat_res[$this->chart], $this->max);
    }

    public static function get_stats_options() {
        return array("shmem:real_used_size", "load:load", "load:load1m", "load:load10m");
    }

    public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['desc'];
        }
        return $boxes_names;
    }

    public static function new_form($params = null) {
		if (!isset($params['widget_max']))
			$params['widget_max'] = 100;
        form_generate_input_text("Title", "Title to be displayed on widget", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_select("Statistic", "Statistic that widget should display", "widget_chart", null,  $params['widget_chart'], self::get_stats_options());
        form_generate_input_text("Max value", "Max value of statistic", "widget_max", "y", $params['widget_max'], 20,null);
        form_generate_select("Box", "Box to extract data from", "widget_box", null,  $params['widget_box'], self::get_boxes());
    }
}