<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class chart_widget extends widget
{
    public $chart;
    function __construct($chart, $name, $title=null) {
        parent::__construct($name, 4, 2, $title);
        $this->chart = $chart;
    }

    function get_html() {
        return '<li type="chart" id='.$this->id.'></li>';
    }

    function get_name() {
        return "Chart widget";
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    public static function new_form() {  
        form_generate_input_text("Title", "", "widget_title", null, null, 20,null);
        form_generate_input_text("Chart", "", "widget_chart", null, null, 20,null);
        form_generate_input_text("ID", "", "widget_id", null, null, 20,null);
        form_generate_input_text("SizeX", "", "widget_sizex", null, null, 20,null);
        form_generate_input_text("SizeY", "", "widget_sizey", null, null, 20,null);
    }
}