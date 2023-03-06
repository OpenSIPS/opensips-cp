<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class chart_widget extends widget
{
    public $chart;
    public $chart_box;
    public $chart_size;
    public $chart_refresh=null;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 5, $array['widget_title']);
        $this->color = 'rgb(198,226,213)';
        $this->chart = $array['widget_chart'];
	$this->chart_box = $array['widget_box'];
        $this->chart_size = isset($array['widget_chart_size'])?$array['widget_chart_size']:1;
	if (isset($array['widget_chart_refresh']) && $array['widget_chart_refresh'] != '')
		$this->chart_refresh = intval($array['widget_chart_refresh']) * 1000;
        
        require_once(__DIR__."/../../../../../common/cfg_comm.php");
        session_load_from_tool("smonitor");
    }


    function get_name() {
        return "Statistic Chart widget";
    }

    function echo_content() {
        $this->show_chart();
    }

    public static function get_stats_options() {
        require_once(__DIR__."/../../lib/functions.inc.php");
        return get_stats_list_all_boxes();
    }
    
    function show_chart() {
	$_SESSION['dashboard_active'] = 1;
        $_SESSION['widget_chart_size'] = $this->chart_size;
        $_SESSION['widget_chart_refresh'] = $this->chart_refresh;
        require_once(__DIR__."/../../lib/functions.inc.php");
	if (substr($this->chart, 0, 5) == "Group") {
		show_widget_graphs($this->id, substr($this->chart, 7), $this->chart_refresh);
	} else
		show_graph($this->id, $this->chart, $this->chart_box, $this->chart_refresh);
	$_SESSION['dashboard_active'] = 0;
    }

    public static function chart_box_selection($stats_list, $init) {
        $slist = json_encode($stats_list);
        echo ('
            <script>
            var slist = '.$slist.';
			var init = '.$init.'
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_options();}, false);
            function update_options() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var chart_select = document.getElementById("widget_chart"); 
				chart_select.options.length = 0;
				slist["Group"].forEach(element => { 
					var opt = document.createElement("option");
					opt.value = element;
					opt.textContent = element;
					chart_select.appendChild(opt);
				});
                if (slist[selected_box])  {
                    slist[selected_box].forEach(element => {
                        var opt = document.createElement("option");
                        opt.value = element;
                        opt.textContent = element;
                        chart_select.appendChild(opt);
                    });
                }
            } if (init == 1) update_options();
            </script>
        ');
    }

    public static function new_form($params = null) {
		$boxes_info = self::get_boxes();
        if (is_null($params))
			$init = 1;
		else $init = 0;
        if (!$params['widget_title'])
            $params['widget_title'] = "Chart";
        if (!$params['widget_chart_size'])
            $params['widget_chart_size'] = 1;
        $stats_list = self::get_stats_options();
		$options = (!$init)?$stats_list[$params['widget_box']]:$stats_list[0];
		$options = array_merge($stats_list["Group"], $options);
        form_generate_input_text("Title", "Widget name", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_select("Box", "Widget box", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
        form_generate_select("Chart", "Statistic to view", "widget_chart", null,  $params['widget_chart'], $options);
        form_generate_input_text("Chart size", "Chart timespan in hours (defaults to 1)", "widget_chart_size", "n", $params['widget_chart_size'], 20,null);
        form_generate_input_text("Chart refresh period", "Period (in seconds) when the chart should be refreshed", "widget_chart_refresh", "y", $params['widget_chart_refresh'], 20, '^([0-9]\+)$');
        self::chart_box_selection($stats_list, $init);
    }

    static function get_description() {
        return "Charts a certain statistic (simple or grouped) from a given OpenSIPS Box. Note that you can display here only the statistics which are already enabled for charting in the Statiscs Monitor tool!.";
    }

}
