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

    public static function get_stats_options($box_id = null) {
        require_once(__DIR__."/../../lib/functions.inc.php");
        if ($box_id)
            return get_stats_list($box_id);
        else return get_stats_list_all_boxes();
    }
    
    function show_chart() {
        require_once(__DIR__."/../../lib/functions.inc.php");
        if (substr($this->chart, 0, 5) == "Group") {
            show_widget_graphs($this->chart);
        } else
            show_graph($this->chart, 0);
    }

    public static function chart_box_selection($stats_list) {
        $slist = json_encode($stats_list);
        echo ('
            <script>
            var slist = '.$slist.';
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_options();}, false);
            function update_options() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var chart_select = document.getElementById("widget_chart"); 
                if (slist[selected_box])  {
                    chart_select.options.length = 0;
                    var newHtml = "";
                    slist[selected_box].forEach(element => {
                        var opt = document.createElement("option");
                        opt.value = element;
                        opt.textContent = element;
                        chart_select.appendChild(opt);
                    });
                } else {
                    chart_select.options.length = 0;
                }
            }
            </script>
        ');
    }

    public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['id'];
        }
        return $boxes_names;
    }

    public static function new_form($params = null) {
        $box_id = 0;
        $stats_list = self::get_stats_options();
        form_generate_input_text("Title", "", "widget_title", null, $params['widget_title'], 20,null);
        form_generate_select("Chart", "", "widget_chart", null,  $params['widget_chart'], $stats_list[0]);
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], self::get_boxes());
        self::chart_box_selection($stats_list);
        form_generate_input_text("Has menu", "", "widget_menu", null, $params['widget_menu'], 20,null);
        form_generate_input_text("Color", "", "widget_color", null, $params['widget_color'], 20,null);
        form_generate_input_text("ID", "", "widget_id", null, $params['widget_id'], 20,null);
    }
}