<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");
require_once(__DIR__."/../../../../admin/dashboard/template/dashboard_widgets/gauge_widget.php");

class asr_widget extends gauge_widget
{
	public static $ignore = 0;

    function __construct($array) {
        parent::__construct($array);
    }

    function get_name() {
        return "Answer seizure rate widget";
    }

    function echo_content() {
        $processed_dialogs = mi_command("get_statistics", array("statistics" => array("processed_dialogs")), $_SESSION['boxes'][0]['mi_conn'], $errors);
        $failed_dialogs = mi_command("get_statistics", array("statistics" => array("failed_dialogs")), $_SESSION['boxes'][0]['mi_conn'], $errors);
        $processed_dialogs = $processed_dialogs["dialog:processed_dialogs"];
		$failed_dialogs = $failed_dialogs["dialog:failed_dialogs"];
		$this->display_chart($this->title, $processed_dialogs, $processed_dialogs + $failed_dialogs);
    }

    public static function new_form($params = null) {  
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
    }
}