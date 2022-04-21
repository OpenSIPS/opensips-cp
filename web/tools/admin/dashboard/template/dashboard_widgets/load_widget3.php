<?php
require_once(__DIR__."/../widget/widget.php");

class load_widget3 extends widget
{
    public $chart;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 2, $array['widget_title']);
        $this->color = $array['widget_color'];
        $this->chart = $array['widget_chart'];
    }

    function get_html() {
        $color = 'style="background-color: '.$this->color.';"';
        return '<li  '.$color.' id='.$this->id.'></li>';
    }

    function get_name() {
        return "Load widget3";
    }

    function echo_content() {
        $wi = $this->id;
        echo ("<div id=".$this->id."_old>");
        require(__DIR__."/../../lib/percent3_d3js.php");
        echo ("</div>");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    public static function get_stats_options() {
        return array("load", "load1m", "load10m");
    }

    public static function new_form($params = null) {  
        form_generate_select("Chart", "", "widget_chart", null,  $params['widget_chart'], self::get_stats_options());
        form_generate_input_text("Color", "", "widget_color", null, $params['widget_color'], 20,null);
        form_generate_input_text("ID", "", "widget_id", null, $params['widget_id'], 20,null);
    }
}