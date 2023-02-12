<?php
require_once(__DIR__."/../widget/widget.php");
require_once(__DIR__."/gauge_widget.php");

class load_widget extends gauge_widget
{
    public $chart;
	public static $ignore = 1;

    function __construct($array) {
        parent::__construct($array);
    }

    function get_name() {
        return "Load widget";
    }

    function echo_content() {
		//consoole_log(mi_command("get_statistics", array("statistics" => array("real_used_size")), $_SESSION['boxes'][0]['mi_conn'], $errors));
        $load = mi_command("get_statistics", array("statistics" => array($this->chart)), $_SESSION['boxes'][0]['mi_conn'], $errors);
        $load_value = $load["load:".$this->chart];
		$this->display_chart($this->id, $this->title, $load_value);
    }

    public static function get_stats_options() {
        return array("load", "load1m", "load10m");
    }

    public static function new_form($params = null) {
		$boxes_info = self::get_boxes();
        if (!$params['widget_title'])
            $params['widget_title'] = "Load";
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_select("Chart", "", "widget_chart", null,  $params['widget_chart'], self::get_stats_options());
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    }
}
