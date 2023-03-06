<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class monit_cpu_widget extends widget
{
    public $cpu_res;
    public $box_id;
    public $service;
    public $warning_threshold = 50;
    public $critical_threshold = 70;

    function __construct($array) {
	if (isset($array['widget_refresh']) && $array['widget_refresh'] != '')
		$r = intval($array['widget_refresh']) * 1000;
	else
		$r = 60000; # one minute is the default
        parent::__construct($array['panel_id'], $array['widget_name'], 1,2, $array['widget_name'], $r);
	$this->box_id = $array['widget_box'];
	$this->service = $array['widget_service'];
	if (isset($array['widget_critical']))
		$this->critical_threshold = floatval($array['widget_critical']);
	if (isset($array['widget_warning']))
		$this->warning_threshold = floatval($array['widget_warning']);
	// makes no sense to have warning larger than critical
	if ($this->warning_threshold > $this->critical_threshold)
		$this->warning_threshold = $this->critical_threshold;
	$this->set_cpu();
    }

    function set_cpu_val(&$cpu, $val) {
	    if ($val == "") {
		    $cpu = array('n\a', widget::STATUS_CRIT);
		    return;
	    }
	    $val = floatval($val);
	    if ($val >= $this->critical_threshold)
		    $status = widget::STATUS_CRIT;
	    elseif ($val >= $this->warning_threshold)
		    $status = widget::STATUS_WARN;
	    else
		    $status = widget::STATUS_OK;
	    $cpu = array($val, $status);
    }

    function set_cpu_status() {
	    # biggest status of both
	    $this->set_status(max($this->cpu_usr[1], $this->cpu_sys[1]));
    }

    function get_cpu_val($cpu) {
	    return $cpu[0];
    }

    function get_cpu_color($cpu) {
	    switch ($cpu[1]) {
	    case widget::STATUS_WARN:
		    return "orange";
	    case widget::STATUS_CRIT:
		    return "red";
	    case widget::STATUS_OK:
	    default:
		    return "black";
	    }
    }

    function init_cpu() {
	$this->set_cpu_val($this->cpu_usr, 'n\a');
	$this->set_cpu_val($this->cpu_sys, 'n\a');
    }

    function get_name() {
        return "CPU monit widget";
    }
    function display_test() {
	    echo ('
			<table style="table-layout: fixed;
			width: 90px; height:20px; margin: auto; margin-left: 1px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
			');
		echo ('
			<tr><td class="rowEven">USR: </td><td><span style="color:'.$this->get_cpu_color($this->cpu_usr).';">'.$this->get_cpu_val($this->cpu_usr).'%</span></td></tr>
			<tr><td class="rowOdd">SYS: </td><td><span style="color:'.$this->get_cpu_color($this->cpu_sys).';">'.$this->get_cpu_val($this->cpu_sys).'%</span></td></tr>
			</tr>  ');
		echo('</table>');

	}

	function set_cpu() {
		$this->init_cpu();
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
		curl_setopt($ch, CURLOPT_URL, $protocol."://".$host."/_status?service=".$this->service);
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

		preg_match('/(cpu[\s\r\n]*(.\[[0-9];[0-9]+m)?(?<cpu_info>.*?)[\s\r\n]*memory)/s', $response, $matches);
		$cpu_info = utf8_encode($matches['cpu_info']);
		preg_match('/([0-9]*\.[0-9]*)\%usr? /', $cpu_info, $cpu_matches);
		if (isset($cpu_matches[1]))
			$this->set_cpu_val($this->cpu_usr, $cpu_matches[1]);

		preg_match('/ ([0-9]*\.[0-9]*)\%sys? /', $cpu_info, $cpu_matches);
		if (isset($cpu_matches[1]))
			$this->set_cpu_val($this->cpu_sys, $cpu_matches[1]);
		$this->set_cpu_status();
	}

    function echo_content() {
        $this->display_test();
    }

    public static function new_form($params = null) {
	$boxes_info = self::get_boxes();
	if (is_null($params)) {
		$params['widget_service'] = gethostname();
	}
        if (!$params['widget_name'])
            $params['widget_name'] = "CPU";
	form_generate_input_text("Title", null, "widget_name", null, $params['widget_name'], 5,null);
	form_generate_input_text("Service", "The name of the monit 'service' name, usually the hostname", "widget_service", null, $params['widget_service'], 20,null);
	form_generate_select("Box", null, "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
        form_generate_input_text("Warning threshold", "The percent after which the indicator will display the warning section (orange)", "widget_warning", "n", $params['widget_warning'], 20, "^[0-9]\+(\\\.[0-9]\+)?$");
        form_generate_input_text("Critical threshold", "The percent after which the indicator will display the warning section (red)", "widget_critical", "n", $params['widget_critical'], 20, "^[0-9]\+(\\\.[0-9]\+)?$");
	form_generate_input_text("Refresh period", "Period (in seconds) when the widget should updated", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
	}

	static function get_description() {
		return "Gathers the CPU usage info (via Monit) from a certain Box and/or a specific monitored application/service. Thresholds may be defined for reporting purposes only.";
	}
}

?>
