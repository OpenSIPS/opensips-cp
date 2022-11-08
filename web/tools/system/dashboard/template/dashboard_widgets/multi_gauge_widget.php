<?php
require_once(__DIR__."/../widget/widget.php");
require_once(__DIR__."/gauge_widget.php");

class multi_gauge_widget extends gauge_widget
{
    public $max;
	public static $ignore = 0;

    function __construct($array) {
        parent::__construct($array);
        $this->color = 'rgb(198,226,213)';
		$this->max = $array['widget_max'];
    }

    function get_name() {
        return "Percent widget";
    }

    function echo_content() {
		//consoole_log(mi_command("get_statistics", array("statistics" => array("real_used_size")), $_SESSION['boxes'][0]['mi_conn'], $errors));	
		$chart_type = explode(":", $this->chart)[0];
		$chart_type .= ":";
		$stat_res = mi_command("get_statistics", array("statistics" => array($chart_type)), $this->widget_box['mi_conn'], $errors);
		$this->display_chart($this->title, $stat_res[$this->chart], $this->max);
    }

    public static function get_stats_options() {
        return array("shmem:real_used_size", "load:load", "load:load1m", "load:load10m");
    }

    public static function new_form($params = null) {
		$boxes_info = self::get_boxes();
		if (!isset($params['widget_max']))
			$params['widget_max'] = 100;
		if (!isset($params['widget_warning']))
			$params['widget_warning'] = 50;
		if (!isset($params['widget_critical']))
			$params['widget_critical'] = 75;
        if (!$params['widget_title'])
            $params['widget_title'] = "Percent";
        form_generate_input_text("Title", "Title to be displayed on widget", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_select("Statistic", "Statistic that widget should display", "widget_chart", null,  $params['widget_chart'], self::get_stats_options());
        form_generate_input_text("Max value", "Max value of statistic", "widget_max", "y", $params['widget_max'], 20,null);
        form_generate_input_text("Warning threshold", "The percent after which the indicator will display the warning section (yellow)", "widget_warning", "n", $params['widget_warning'], 20,null);
        form_generate_input_text("Critical threshold", "The percent after which the indicator will display the warning section (red)", "widget_critical", "n", $params['widget_critical'], 20,null);
        form_generate_select("Box", "Box to extract data from", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    }
}