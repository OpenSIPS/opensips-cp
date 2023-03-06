<?php
require_once(__DIR__."/../widget/widget.php");
require_once(__DIR__."/gauge_widget.php");

class stats_gauge_widget extends gauge_widget
{
    public $max;
    public $stat_val;
    public $stat_max;
    public static $ignore = 0;
    public $status_module = "smonitor";

    function __construct($array) {
        parent::__construct($array);
        $this->color = 'rgb(198,226,213)';
	$this->stat_val = $array['widget_stat'];
	$this->stat_max = $array['widget_stat_max'];
	if ($this->stat_max == 'Static') {
		$this->stat_max = null;
		$this->total = $array['widget_max'];
	}
	$this->get_data();
	if ($this->value == null || ($this->value/$this->total * 100) > $this->critical)
		$this->set_status(widget::STATUS_CRIT);
	else if (($this->value/$this->total * 100) > $this->warning)
		$this->set_status(widget::STATUS_WARN);
	else
		$this->set_status(widget::STATUS_OK);
    }

    function get_data() {
	if ($this->widget_box != null) {
	    $stats = array(explode(":", $this->stat_val)[1]);
	    if ($this->stat_max != null)
	        $stats[] = explode(":", $this->stat_max)[1];
            require_once("../../../common/mi_comm.php");
            $stats = mi_command("get_statistics", array("statistics" => $stats), $this->widget_box['mi_conn'], $errors);
	    $this->value = $stats[$this->stat_val];
	    if ($this->stat_max != null)
	    	$this->total = $stats[$this->stat_max];
	} else {
            $this->value = $this->total = null;
	}
	return array($this->value, $this->total);
    }

    function get_name() {
        return "Statistic Gauge widget";
    }

    function echo_content() {
	$this->display_chart($this->title, $this->value, $this->total);
    }

    public static function fetch_box_stats($params) {
	    if (!isset($params['box']))
		    return array();
	    return self::get_stats_options($params['box']);
    }

    public static function get_stats_options($box) {
	$mi_box = null;
	foreach ($_SESSION['boxes'] as $b) {
		if ($b['id'] == $box) {
			$mi_box = $b;
			break;
		}
	}
	if ($mi_box == null)
		return array();
	$errors = [];
        require_once("../../../common/mi_comm.php");
        $all = mi_command("get_statistics", array("statistics" => array("all")), $mi_box['mi_conn'], $errors);
	if (count($errors) == 0)
		return array_keys($all);
	else
		return array();
    }
    
    public static function stats_box_selection() {
        echo ('
            <script>
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_options();}, false);
            function update_options() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var stat_select = document.getElementById("widget_stat"); 
		stat_select.options.length = 0;
                var stat_max_select = document.getElementById("widget_stat_max"); 
		stat_max_select.options.length = 1;
		fetch_widget_info("'.get_class($this).'", "fetch_box_stats", "box="+selected_box)
		.then(data => {
		  data.forEach(element => {
		    var opt = document.createElement("option");
		    opt.value = element;
		    opt.textContent = element;
		    stat_select.appendChild(opt);
		    opt = opt.cloneNode(true);
		    stat_max_select.appendChild(opt);
		  });
		  stat_select.selectedIndex = 0;
		  stat_max_select.selectedIndex = 0;
		  update_max_input();
		}).catch(update_max_input());
            };
            </script>
        ');
    }

    public static function max_stat_selection() {
        echo ('
            <script>
            var stat_select = document.getElementById("widget_stat_max");
            stat_select.addEventListener("change", function(){update_max_input();}, false);
            function update_max_input() {
                var stat_select = document.getElementById("widget_stat_max");
                var selected_stat = stat_select.value;
                var table = document.getElementById("widget_table");
                var input_row = table.rows[table.rows.length - 1];
		if (input_row.id == "buttons_row")
                    input_row = table.rows[table.rows.length - 2];
                if (selected_stat == "Static") {
			input_row.style.display = null;
		} else {
			input_row.style.display = "none";
		}
            };
            update_max_input();
            </script>
        ');
    }

    public static function new_form($params = null) {
	$boxes_info = self::get_boxes();
	if (!isset($params['widget_max']))
		$params['widget_max'] = 100;
	if (!isset($params['widget_warning']))
		$params['widget_warning'] = 50;
	if (!isset($params['widget_critical']))
		$params['widget_critical'] = 75;
        if (!$params['widget_title'])
            $params['widget_title'] = "Statistic Gauge";
        if (!$params['widget_refresh'])
            $params['widget_refresh'] = 60;
	if (!isset($params['widget_box']))
	    $params['widget_box'] = $boxes_info[0][0];

        $options = self::get_stats_options($params['widget_box']);
	if (!$params['widget_stat'])
		$params['widget_stat'] = $options[0];
	if (!$params['widget_stat_max'])
		$params['widget_stat_max'] = 'Static';
        form_generate_input_text("Title", "Title to be displayed on widget", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_input_text("Warning threshold", "The percent after which the indicator will display the warning section (yellow)", "widget_warning", "n", $params['widget_warning'], 20,null);
        form_generate_input_text("Critical threshold", "The percent after which the indicator will display the warning section (red)", "widget_critical", "n", $params['widget_critical'], 20,null);
    	form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
        form_generate_select("Box", "Box to extract data from", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
        form_generate_select("Statistic", "Statistic value of the widget", "widget_stat", null,  $params['widget_stat'], $options);
	array_unshift($options, 'Static');
        form_generate_select("Statistic Max value", "Max value of statistic; if 'static' is chosen, the max is expressed as a static value", "widget_stat_max", null,  $params['widget_stat_max'], $options);
        form_generate_input_text("Max value", "Static Max value of statistic", "widget_max", "y", $params['widget_max'], 20, "^[0-9]*$");
        self::stats_box_selection();
        self::max_stat_selection();
    }

    public static function get_description() {
	    return "Displays a gauge graph of a particular statistic in OpenSIPS. Such graph suits only statistics that also have a max value - this can be either a static maximum value or another OpenSIPS statistic (like usaged shared memory versus total shared memory).";
    }
}
