<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class monit_tools_widget extends widget
{
    public $monitored_tools;
	public $box_id;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
		$this->box_id = $array['widget_box'];
		$this->set_monitored();
        $this->color = "rgb(207, 207, 207)";
    }


    function get_name() {
        return "Monit tools";
    }
	
    function display_test() {
		echo ('
			<table class="ttable" style="table-layout: fixed;
			width: 110px; height:15px; margin: auto;" cellspacing="0" cellpadding="0" border="0">
			');
		foreach($this->monitored_tools as $name => $tool) {
			if ((int) $tool['number'] > 0) {
				echo ('
					<tr><td class="rowEven">'.$name.': '.(($name=="down")?'<span style="color:red; font-weight: 900;">':'<span style="font-weight: 900;">').$tool['number'].'</span></td></tr>
				');
			}
		}
		echo('</table>');
	}

	function set_monitored() {
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$widget_box = $box;
		}
		$auth_user = $box['monit_user'];
		$auth_pass = $box['monit_pass'];
		$auth = base64_encode($auth_user.":".$auth_pass);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:2812/_report");
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
		
		preg_match_all('/(?<tool_name>[a-z]*)\:.*?(?<tool_number>[0-9]*) \((?<tool_percent>.*?)\)/', $response, $matches);
		foreach($matches['tool_name'] as $i => $value) {
			$this->monitored_tools[$value] = [];
			$this->monitored_tools[$value]['percent'] = $matches['tool_percent'][$i];
			$this->monitored_tools[$value]['number'] = $matches['tool_number'][$i];
		}
	}

    public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['id'];
        }
        return $boxes_names;
    }

    function echo_content() {
        $this->display_test();
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) {  
        form_generate_input_text("Name", "", "widget_name", null, $params['widget_name'], 20,null);
    	form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], self::get_boxes());
	}

}

?>