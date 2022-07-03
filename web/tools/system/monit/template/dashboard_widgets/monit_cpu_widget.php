<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class monit_cpu_widget extends widget
{
    public $cpu_res;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
        $this->set_cpu();
        $this->color = "rgb(207, 207, 207)";
    }


    function get_name() {
        return "Monit CPU";
    }
    function display_test() {
		echo ('
			<table class="ttable" style="table-layout: fixed;
			width: 110px; height:15px; margin: auto;" cellspacing="0" cellpadding="0" border="0">
			');
		echo ('
			<tr><td class="rowEven"><span style="color:black;">USR: '.$this->cpu_res['usr'].'%</span></td></tr>
			<tr><td class="rowEven"><span style="color:black;">SYS: '.$this->cpu_res['sys'].'%</span></td></tr>
			</tr>  ');
		echo('</table>');
	
	}

	function set_cpu() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:2812/_status?service=localhost.localdomain");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Authorization: Basic YWRtaW46bW9uaXRh')                                                                       
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
        form_generate_input_text("Name", "", "widget_name", null, $params['widget_name'], 20,null);
    }

}

?>