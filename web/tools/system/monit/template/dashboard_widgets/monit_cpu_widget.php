<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class monit_cpu_widget extends widget
{
    public $cpu_res;
	public $box_id;
	public $service;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 1,2, $array['widget_name']);
		$this->box_id = $array['widget_box'];
		$this->service = $array['widget_service'];
        $this->set_cpu();
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
			<tr><td class="rowEven"><span style="color:black;">USR: </td><td>'.$this->cpu_res['usr'].'%</span></td></tr>
			<tr><td class="rowEven"><span style="color:black;">SYS: </td><td>'.$this->cpu_res['sys'].'%</span></td></tr>
			</tr>  ');
		echo('</table>');
	
	}

	function set_cpu() {
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$widget_box = $box;
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
			return NULL;
		}

		preg_match('/(cpu[\s\r\n]*(?<cpu_info>.*?)[\s\r\n]*memory)/s', $response, $matches);
		$cpu_info = utf8_encode($matches['cpu_info']);
		preg_match_all('/(?<cpu_info>.*?) /', $cpu_info, $matches);
		foreach($matches['cpu_info'] as $key => $entry) {
			preg_match('/(?<value>([0-9]*\.[0-9])*)\%(?<name>.*)/', $entry, $new_matches);
			$this->cpu_res[$new_matches['name']] = utf8_encode($new_matches['value']);
		}
	}

    function echo_content() {
        $this->display_test();
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) {
		$boxes_info = self::get_boxes();
		if (is_null($params)) {
			$params['widget_service'] = "localhost.localdomain";
		}
        if (!$params['widget_name'])
            $params['widget_name'] = "CPU";
        form_generate_input_text("Title", "", "widget_name", null, $params['widget_name'], 5,null);
		form_generate_input_text("Service", "", "widget_service", null, $params['widget_service'], 20,null);
		form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
	}

	static function get_description() {
		return "
Gathers the CPU being used by the system and/or a specific monitored program";
	}
}

?>