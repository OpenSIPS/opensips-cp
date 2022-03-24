<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class chart_widget extends widget
{
    public $chart;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 2, $array['widget_title']);
        $this->chart = $chart;
    }

    function get_html() {
        return '<li type="chart" id='.$this->id.'></li>';
    }

    function get_name() {
        return "Chart widget";
    }

    function echo_content() {
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    public static function new_form($params = null) {  
        form_generate_input_text("Title", "", "widget_title", null, $params['widget_title'], 20,null);
        form_generate_input_text("Chart", "", "widget_chart", null, $params['widget_chart'], 20,null);
        form_generate_input_text("ID", "", "widget_id", null, $params['widget_id'], 20,null);
        form_generate_input_text("SizeX", "", "widget_sizex", null, $params['widget_sizex'], 20,null);
        form_generate_input_text("SizeY", "", "widget_sizey", null, $params['widget_sizey'], 20,null);
    }
}