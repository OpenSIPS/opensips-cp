<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class monit_tools_widget extends widget
{
    public $monitored_tools;
	public $box_id;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
		$this->box_id = $array['widget_box'];
		$this->set_warning(1);
		$this->set_monitored();
    }


    function get_name() {
        return "Monit tools";
    }
	
    function display_test() {
		$total = 0;
		foreach($this->monitored_tools as $name => $tool) {
			$total += $tool['number'];
		}
		echo ('
			<table style="table-layout: fixed;
			width: 180px; height:20px; margin: auto; margin-left: 10px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
			<tr><td class="rowEven">Total: </td><td style="position: absolute; margin-left: 10px;">'.$total.' service'.(($total==1)?'':'s').'</span></td></tr>
			');
		$rows = 1;
		foreach($this->monitored_tools as $name => $tool) {
			if ((int) $tool['number'] > 0 && $name !="initialising" ) { $rows++;
				switch ($name) {
					case "up":
						$name = "Running";
						break;
					case "unmonitored":
						$name = "Unmonitored";
						break;
					case "down":
						$name = "Failed";
						$this->set_warning(3);
						break;
					default:
						break;
				}
				echo ('
					<tr><td class="rowEven">'.$name.': </td><td style="margin-left:10px; position:absolute;">'.(($name=="Failed")?'<span style="color:red; font-weight: 900;">':'<span style="font-weight: 900;">').$tool['number'].'</span></td></tr>
				');
			}
		}
		echo('</table>');
		if ($rows > 3)
			$this->sizeY = 3;
	}

	function set_monitored() {
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
			return NULL;
		}
		
		preg_match_all('/(?<tool_name>[a-z]*)\:.*?(?<tool_number>[0-9]*) \((?<tool_percent>.*?)\)/', $response, $matches);
		foreach($matches['tool_name'] as $i => $value) {
			$this->monitored_tools[$value] = [];
			$this->monitored_tools[$value]['percent'] = $matches['tool_percent'][$i];
			$this->monitored_tools[$value]['number'] = $matches['tool_number'][$i];
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
        form_generate_input_text("Name", "", "widget_name", null, $params['widget_name'], 20,null);
    	form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
	}

	
	static function get_description() {
		return "
Displays several number of tools:<br>
		Total Monitored<br>
		Total unmonitored<br>
		Failed
		";
	}

}

?>