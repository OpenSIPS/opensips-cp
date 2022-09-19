<?php
require_once(__DIR__."/../widget/widget.php");
require_once(__DIR__."/gauge_widget.php");

class shmem_widget extends gauge_widget
{
	public static $ignore = 0;

    function __construct($array) {
        parent::__construct($array);
		$this->set_warning(1);
        $this->color = 'rgb(198,226,213)';
		$this->sizeX = 2;
    }

    function get_name() {
        return "Shared memory widget";
    }

    function echo_content() {
        $shmem = mi_command("get_statistics", array("statistics" => array("shmem:")), $this->widget_box['mi_conn'], $errors);
        $shmem_value = $shmem["shmem:real_used_size"];
		$shmem_total = $shmem["shmem:total_size"];
		$shmem_max = $shmem["shmem:max_used_size"];
		$this->display_chart($this->title, $shmem_value, $shmem_total, $shmem_max);
    }

	function display_chart($title, $value, $valueMax = 100, $maxEver) {
        $_SESSION['gauge_id'] = $this->id;
        $_SESSION['gauge_value'] = $value;
		$_SESSION['gauge_max'] = $valueMax;
		$_SESSION['warning'] = $this->warning;
		$_SESSION['critical'] = $this->critical;
		$_SESSION['max_ever'] = $maxEver;
		if ($value/$valueMax * 100 > $this->critical)
			$this->set_warning(3);
		else if ($value/$valueMax * 100 > $this->warning)
			$this->set_warning(2);
        require(__DIR__."/../../../../../common/charting/percent_shmemd3js.php");
    }

    public static function new_form($params = null) { 
		$boxes_info = self::get_boxes();
		if (!isset($params['widget_warning']))
			$params['widget_warning'] = 50;
		if (!isset($params['widget_critical']))
			$params['widget_critical'] = 75;
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_input_text("Warning threshold", "The percent after which the indicator will display the warning section (yellow)", "widget_warning", "n", $params['widget_warning'], 20,null);
        form_generate_input_text("Critical threshold", "The percent after which the indicator will display the warning section (red)", "widget_critical", "n", $params['widget_critical'], 20,null);
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    }

	
	static function get_description() {
		return "
A clock-like chart that displays the percentage of the shared memory";
	}

}