<?php
require_once(__DIR__."/../widget/widget.php");

class pkg_widget extends widget
{
    public $cdr_entries;
	public $top_loaded_info;
	public $top_fragmented_info;
	public $box_id;
	public $widget_box;
	public $warning;
	public $critical;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 2, 3, $array['widget_title']);
		$this->box_id = $array['widget_box'];
		$this->set_warning(1);
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$this->widget_box = $box;
		}
		$this->critical = $array['widget_critical'];
		$this->warning = $array['widget_warning'];
        $this->compute_info();
    }

	static function get_description() {
		return "
A tool that aggregates the pkg memory from all processes and performs some statistics about which process is the most loaded, what is the highest fragmentation etc.
";
	}

    function get_name() {
        return "PKG memory widget";
    }
    function display_test() {
		echo('<script> function show_tip(key) {
			var tip = document.getElementById("widget_tip".concat(key));
			tip.style.display = "block";
		} 
		function hide_tip(key) {
			var tip = document.getElementById("widget_tip".concat(key));
			tip.style.display = "none";
		}
		</script>');
		echo ('
		<table style="table-layout: fixed;
		width: 143px; height:20px; margin: auto;" cellspacing="2" cellpadding="2" border="0">
		<tr align="center">
		<th class="listTitle"  style="text-shadow: 0px 0px 0px #000;">PID</th>
		<th class="listTitle" style="text-shadow: 0px 0px 0px #000;">Usage</th>
		</tr>');
		foreach ($this->top_loaded_info as $key => $info) {
			$style = "";
			if ($info['value'] > $this->critical) {
				$style = "color : red; ";
				$this->set_warning(3);
			}
			else if ($info['value'] > $this->warning) {
				$style = "color : orange; ";
				$this->set_warning(2);
			}
			else $style = "color : green;";
			$style .= "font-weight: 900; ";
			echo ('
			<tr>
			<td class="rowEven">
			<div class="tooltip" ><sup style="font-weight:900;">
			&nbsp;'.$info['PID'].'</sup>
			<span style="left:-10px; top:-50px;  pointer-events: none;" class="tooltiptext">'.$info["desc"].'</span>
			</div></td>
			<td class="rowEven" style="'.$style.'" >&nbsp;'.$info['value'].'%</td>
			</tr>  ');
		}

		echo('</table>');
	
    }



    function echo_content() {
        $this->display_test();
    }

    function compute_info() {
		$pkg = mi_command("get_statistics", array("statistics" => array("pkmem:")), $this->widget_box['mi_conn'], $errors);
		$top_fragmented = $this->get_top_fragmented($pkg);
		$this->top_fragmented_info = $this->get_proc_infos($top_fragmented);
		$top_loaded = $this->get_top_loaded($pkg);
		$this->top_loaded_info = $this->get_proc_infos($top_loaded);

    }

	function get_proc_infos($top_procs) {
		$procs = mi_command("ps", array(), $this->widget_box['mi_conn'], $errors);
		foreach($top_procs as $key => $top_proc) {
			$found = false;
			foreach($procs['Processes'] as $proc) {
				if ($proc['ID'] == $top_proc['id']) {
					$top_procs[$key]['PID'] = $proc['PID'];
					$top_procs[$key]['desc'] = $proc['Type'];
					$found = true;
					break;
				}
				if ($found) break;
			}
		}
		return $top_procs;
	}

	function get_top_fragmented($pkg) {
		$top = [];
		foreach ($pkg as $id=>$value) {
			if (strpos($id, "fragments")) {
				preg_match('/(pkmem:(?<id>\d+)-fragments)/', $id, $matches);
				$entry['id'] = $matches['id'];
				$entry['value'] = $value;
				$top[] = $entry;
			}
		}
		usort($top, function($a, $b) {
			return $b['value'] - $a['value'];
		});
		return array_slice($top, 0 , 4);
	}
	

	function get_top_loaded($pkg) {
		
		$top = [];
		foreach ($pkg as $id=>$value) {
			$entry = [];
			if (strpos($id, "fragments")) {
				preg_match('/(pkmem:(?<id>\d+)-fragments)/', $id, $matches);
				$entry['id'] = $matches['id'];
				if ($pkg['pkmem:'.$entry['id'].'-max_used_size'] == 0 ) continue;
				$entry['value'] = $pkg['pkmem:'.$entry['id'].'-used_size'] / $pkg['pkmem:'.$entry['id'].'-max_used_size'];
				$entry['value'] = round($entry['value'] * 100, 2);
				$top[] = $entry;
			}
		}
		usort($top, function($a, $b) {
			return $b['value'] * 100 - $a['value'] * 100;
		});
		return array_slice($top, 0 , 4);
	}

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) {
		$boxes_info = self::get_boxes();
		if (!isset($params['widget_warning']))
			$params['widget_warning'] = 50;
		if (!isset($params['widget_critical']))
			$params['widget_critical'] = 75;
        if (!$params['widget_title'])
            $params['widget_title'] = "PKG";
        form_generate_input_text("Title", "", "widget_title", null, $params['widget_title'], 20,null);
		form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
		form_generate_input_text("Warning threshold", "The percent after which the indicator will display the warning section (yellow)", "widget_warning", "n", $params['widget_warning'], 20,null);
        form_generate_input_text("Critical threshold", "The percent after which the indicator will display the warning section (red)", "widget_critical", "n", $params['widget_critical'], 20,null);
    }
}

?>