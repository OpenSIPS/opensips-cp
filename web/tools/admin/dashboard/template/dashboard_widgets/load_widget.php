<?php
require_once(__DIR__."/../widget/widget.php");

class load_widget extends widget
{
    public $chart;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 5, 2, $array['widget_title']);
        $this->color = 'rgb(219,255,244)';
        $this->chart = $array['widget_chart'];
    }

    function get_name() {
        return "Load widget";
    }

    function echo_content() {
        $wi = $this->id;
        $_SESSION['load_widget_id'] = $wi;
        $load = mi_command("get_statistics", array("statistics" => array($this->chart)), $_SESSION['boxes'][0]['mi_conn'], $errors);
        $load_value = $load["load:".$this->chart];
        $load_value = 75; //DE STERS LINIA ASTA
        $_SESSION['load_widget_value'] = $load_value;
        echo ("<div id=".$wi."_old>");
        require(__DIR__."/../../lib/percent_d3js.php");
        echo ("</div>");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    public static function get_stats_options() {
        return array("load", "load1m", "load10m");
    }

    public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['desc'];
        }
        return $boxes_names;
    }

    public static function new_form($params = null) {  
        form_generate_select("Chart", "", "widget_chart", null,  $params['widget_chart'], self::get_stats_options());
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], self::get_boxes());
    }
}