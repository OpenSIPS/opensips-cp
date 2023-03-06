<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class monit_tools_widget extends widget
{
    public $monitored_tools;
	public $box_id;

    function __construct($array) {
	if (isset($array['widget_refresh']) && $array['widget_refresh'] != '')
		$r = intval($array['widget_refresh']) * 1000;
	else
		$r = 60000; # one minute is the default
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name'], $r);
	$this->box_id = $array['widget_box'];
	$this->init_monitored();
	$this->set_monitored();
    }


    function get_name() {
        return "Monit tools";
    }
	
    function display_test() {
		echo ('
			<table style="table-layout: fixed;
			width: 180px; height:20px; margin: auto; font-weight: bolder; text-align: center;" cellspacing="3" cellpadding="2" border="0">
			<tr><td class="rowEven" colspan="3" style="font-size:14px; margin-bottom=10px;"><b>'.$this->monitored_total.' service'.(($this->monitored_total==1)?'':'s').'</b></td></tr><tr>
			');
		echo ('<td class="rowOdd"><div class="tooltip"'.($this->monitored_tools[0] > 0?' style="color: green;"':'').'"><sup>'.$this->monitored_tools[0].'</sup><span style="top:-50px;  pointer-events: none;" class="tooltiptext">Running</span>');

		echo ('<td class="rowEven"><div class="tooltip"'.($this->monitored_tools[1] > 0?' style="color: orange;"':'').'"><sup>'.$this->monitored_tools[1].'</sup><span style="top:-50px;  pointer-events: none;" class="tooltiptext">Not Monitored</span>');
		echo ('<td class="rowOdd"><div class="tooltip"'.($this->monitored_tools[2] > 0?' style="color: red;"':'').'"><sup>'.$this->monitored_tools[2].'</sup><span style="top:-50px;  pointer-events: none;" class="tooltiptext">Failed</span>');
		echo('</tr></table>');
	}

	function init_monitored() {
		$this->monitored_tools = array(0, 0, 0);
		$this->monitored_total = 0;
		$this->set_status(widget::STATUS_CRIT);
	}

	function set_monitored() {
		$this->init_monitored();
		$widget_box = null;
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$widget_box = $box;
		}
		if ($widget_box == null) {
			$errors[] = "did not find box";
			error_log("did not find box ".$this->box_id);
			return false;
		}
		$auth_user = $widget_box['monit_user'];
		$auth_pass = $widget_box['monit_pass'];
		$protocol = "http";
		if ($widget_box['monit_ssl'])
			$protocol = "https";
		$host = $widget_box['monit_conn'];

		$auth = base64_encode($auth_user.":".$auth_pass);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $protocol."://".$host."/_report");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Authorization: Basic '.$auth)                                                                       
		);
		$response = curl_exec($ch);
	
		if($response === false){
			$errors[] = curl_error($ch);
			return false;
		}
	
		$status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
	
		curl_close($ch);

		if ($status>=300) {
			$errors[] = "MI HTTP request failed with ".$status." reply";
			return false;
		}
		
		preg_match_all('/(?<tool_name>[a-z]*)\:.*?(?<tool_number>[0-9]*) \((?<tool_percent>.*?)\)/', $response, $matches);
		foreach($matches['tool_name'] as $i => $value) {
			$nr = (int)$matches['tool_number'][$i];
			$this->monitored_total += $nr;
			switch ($value) {
				case "up":
					$idx = 0;
					break;
				case "unmonitored":
					$idx = 1;
					break;
				case "down":
				default:
					$idx = 2;
					break;
			}
			$this->monitored_tools[$idx] += $nr;
		}
		if ($this->monitored_tools[2] > 0)
			$this->set_status(widget::STATUS_CRIT);
		else if ($this->monitored_tools[1] > 0)
			$this->set_status(widget::STATUS_WARN);
		else
			$this->set_status(widget::STATUS_OK);
	}

    function echo_content() {
        $this->display_test();
    }

    public static function new_form($params = null) {
	$boxes_info = self::get_boxes();
        if (!$params['widget_name'])
            $params['widget_name'] = "Monit";
	form_generate_input_text("Name", null, "widget_name", null, $params['widget_name'], 20,null);
	form_generate_select("Box", null, "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
	form_generate_input_text("Refresh period", "Period (in seconds) when the widget should updated", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
	}

	
	static function get_description() {
		return "Displays information about the monitored services (via Monit) from a certain Box, like the total number of monitored service versus the number of unmonitored or failed services.";
	}

}

?>
