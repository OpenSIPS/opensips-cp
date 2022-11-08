<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class bar_chart_widget extends widget
{
    public $chart;
	public static $ignore = 1;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 2, $array['widget_title']);
        $this->color = $array['widget_color'];
        $this->chart = $chart;
    }

    function get_html() {
        $color = 'style="background-color: '.$this->color.';"';
        return '<li type="bar_chart" '.$color.' id='.$this->id.'></li>';
    }

    function get_name() {
        return "Bar-chart widget";
    }
/*
    function echo_content() {
        ob_start();
        $original_get = $_GET;
        $_GET = [];
        $file = "dashboard3.php";
        require_once($file);
        $_GET = $original_get;
        $content_chart .= ob_get_contents();
        ob_clean();
        echo ("<div id=".$this->id."_old>".$content_chart."</div>");
    } */

    function echo_content() {
        $wi = $this->id;
        require(__DIR__."/../../lib/bar_d3js.php");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    public static function new_form($params = null) {  
        if (!$params['widget_title'])
            $params['widget_title'] = "Bar";
        form_generate_input_text("Title", "", "widget_title", null, $params['widget_title'], 20,null);
        form_generate_input_text("Chart", "", "widget_chart", null, $params['widget_chart'], 20,null);
        form_generate_input_text("Color", "", "widget_color", null, $params['widget_color'], 20,null);
        form_generate_input_text("ID", "", "widget_id", null, $params['widget_id'], 20,null);
    }
}