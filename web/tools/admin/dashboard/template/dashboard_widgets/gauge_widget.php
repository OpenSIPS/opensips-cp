<?php
require_once(__DIR__."/../widget/widget.php");

class gauge_widget extends widget
{
    public $chart;
	public static $ignore = 1;
	
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 5, $array['widget_title']);
        $this->color = 'rgb(219,255,244)';
        $this->chart = $array['widget_chart'];
    }

    function display_chart($title, $value, $valueMax = 100) {
        $_SESSION['gauge_id'] = $id;
        $_SESSION['gauge_value'] = $value;
		$_SESSION['gauge_max'] = $valueMax;
        require(__DIR__."/../../lib/percent_d3js.php");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }


}