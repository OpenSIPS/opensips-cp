<?php
require_once(__DIR__."/../widget/widget.php");
require_once(__DIR__."/gauge_widget.php");

class shmem_widget extends gauge_widget
{
    public static $ignore = 0;
    public $status_module = "smonitor";

    function __construct($array) {
        parent::__construct($array);
        $this->color = 'rgb(198,226,213)';
	$this->sizeX = 2;
	$this->get_data();
	if ($this->value == null || ($this->value/$this->total * 100) > $this->critical)
		$this->set_status(widget::STATUS_CRIT);
	else if (($this->value/$this->total * 100) > $this->warning)
		$this->set_status(widget::STATUS_WARN);
	else
		$this->set_status(widget::STATUS_OK);
    }

    function get_name() {
        return "Shared memory widget";
    }

    function echo_content() {
	$this->display_chart($this->title, $this->value, $this->total, $this->maximum);
    }

    function get_data() {
	if ($this->widget_box != null) {
            require_once("../../../common/mi_comm.php");
            $shmem = mi_command("get_statistics", array("statistics" => array("shmem:")), $this->widget_box['mi_conn'], $errors);
            $this->value = $shmem["shmem:real_used_size"];
    	    $this->total = $shmem["shmem:total_size"];
    	    $this->maximum = $shmem["shmem:max_used_size"];
	} else {
            $this->value = $this->total = $this->maximum = null;
	}
	return array($this->value, $this->total, $this->maximum);
    }

    function display_chart($title, $value, $valueMax = 100, $maxEver) {
        $_SESSION['gauge_id'] = $this->id;
        $_SESSION['gauge_value'] = $value;
	$_SESSION['gauge_max'] = $valueMax;
	$_SESSION['warning'] = $this->warning;
	$_SESSION['critical'] = $this->critical;
	$_SESSION['max_ever'] = $maxEver;
	$_SESSION['refreshInterval'] = $this->refresh;
        require(__DIR__."/../../../../../common/charting/percent_shmemd3js.php");
    }

    public static function new_form($params = null) { 
	$boxes_info = self::get_boxes();
	if (!isset($params['widget_warning']))
		$params['widget_warning'] = 50;
	if (!isset($params['widget_critical']))
		$params['widget_critical'] = 75;
        if (!isset($params['widget_title']))
            $params['widget_title'] = "Shared memory";
        if (!isset($params['widget_refresh']))
            $params['widget_refresh'] = 60;
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
        form_generate_input_text("Warning threshold", "The percent after which the indicator will display the warning section (yellow)", "widget_warning", "n", $params['widget_warning'], 20,null);
        form_generate_input_text("Critical threshold", "The percent after which the indicator will display the warning section (red)", "widget_critical", "n", $params['widget_critical'], 20,null);
    	form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
    }

	static function get_description() {
		return "
A clock-like chart that displays the percentage of the shared memory of a ceratin OpenSIPS/Box. The information is retrieved via MI.";
	}

}
