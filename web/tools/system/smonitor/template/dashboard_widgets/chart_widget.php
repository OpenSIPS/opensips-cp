<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class chart_widget extends widget
{
    public $chart;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 2, $array['widget_title']);
        $this->color = $array['widget_color'];
        $this->has_menu = $array['widget_menu'];
        if ($this->has_menu == "yes")
            $this->sizeY = 3;
        $this->chart = $array['widget_chart'];
    }

    function get_html() {
        if ($this->has_menu == "yes") 
            $menu = '<header><a href=\'dashboard.php?action=edit_widget&panel_id='.$this->panel_id.'&widget_id='.$this->id.'\' onclick="lockPanel()" style=" top:2px; content: url(\'../../../images/sett.png\');"></a></header>';
        $color = 'style="background-color: '.$this->color.';"';
        return '<li type="chart" '.$color.' id='.$this->id.'>'.$menu.'</li>';
    }

    function get_name() {
        return "Chart widget";
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
        consoole_log($content_chart);
        echo ("<div id=".$this->id."_old>".$content_chart."</div>");
    } */

    function echo_content() {
        $wi = $this->id;
        echo ("<div id=".$this->id."_old>");
        $this->show_chart();
        echo ("</div>");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    public static function get_stats_options() {
        require_once(__DIR__."/../../lib/functions.inc.php");
        return get_stats_list();
    }

    function show_chart() {
        require_once(__DIR__."/../../lib/functions.inc.php");
        if (substr($this->chart, 0, 5) == "Group") {
            show_widget_graphs($this->chart);
        } else
            show_graph($this->chart, 0);
    }

    public static function new_form($params = null) {  
        form_generate_input_text("Title", "", "widget_title", null, $params['widget_title'], 20,null);
        form_generate_select("Chart", "", "widget_chart", null,  $params['widget_chart'], self::get_stats_options());
        form_generate_input_text("Has menu", "", "widget_menu", null, $params['widget_menu'], 20,null);
        form_generate_input_text("Color", "", "widget_color", null, $params['widget_color'], 20,null);
        form_generate_input_text("ID", "", "widget_id", null, $params['widget_id'], 20,null);
    }
}