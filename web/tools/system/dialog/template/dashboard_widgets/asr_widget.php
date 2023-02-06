<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");
require_once(__DIR__."/../../../../system/dashboard/template/dashboard_widgets/gauge_widget.php");

class asr_widget extends gauge_widget
{
	public static $ignore = 1;

    function __construct($array) {
        parent::__construct($array);
        $this->color = 'rgb(198,226,213)';
    }

    function get_name() {
        return "Answer seizure rate widget";
    }

    function echo_content() {
        $processed_dialogs = mi_command("get_statistics", array("statistics" => array("processed_dialogs")), $this->widget_box['mi_conn'], $errors);
        $failed_dialogs = mi_command("get_statistics", array("statistics" => array("failed_dialogs")), $this->widget_box['mi_conn'], $errors);
        $processed_dialogs = $processed_dialogs["dialog:processed_dialogs"];
		$failed_dialogs = $failed_dialogs["dialog:failed_dialogs"];
		$this->display_chart($this->title, $processed_dialogs, $processed_dialogs + $failed_dialogs);
    }

    public static function new_form($params = null) {  
		$boxes_info = self::get_boxes(); 
		if (!isset($params['widget_warning']))
			$params['widget_warning'] = 50;
		if (!isset($params['widget_critical']))
			$params['widget_critical'] = 75;
        if (!$params['widget_title'])
            $params['widget_title'] = "ASR";
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_input_text("Warning threshold", "The percent after which the indicator will display the warning section (yellow)", "widget_warning", "n", $params['widget_warning'], 20,null);
        form_generate_input_text("Critical threshold", "The percent after which the indicator will display the warning section (red)", "widget_critical", "n", $params['widget_critical'], 20,null);
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    }

	static function get_description() {
		return "
A percentage chart showing how many calls were answered vs total number of calls";
	}
}
