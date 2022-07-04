<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class gateways_widget extends widget
{
    public $available = 0;
	public $inactive = 0;
	public $probing = 0;
	public $box_id;
	public $widget_box;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
		$this->box_id = $array['widget_box'];
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$this->widget_box = $box;
		}
		$this->set_gateways();
        $this->color = "rgb(225, 229, 195)";
    }


    function get_name() {
        return "Gateways widget";
    }
    function display_test() {
		echo ('
			<table class="ttable" style="table-layout: fixed;
			width: 110px; height:15px; margin: auto;" cellspacing="0" cellpadding="0" border="0">
			');
		echo ('
			<tr>
			<td class="rowEven">Available: <span style="color:green; font-weight: 900;">'.$this->available.'</span></td></tr>');
		if ($this->inactive > 0)
			echo ('<tr><td class="rowEven">Inactive: <span style="color:red; font-weight: 900;">'.$this->inactive.'</span></td></tr>');
		if ($this->probing > 0)
			echo ('<tr><td class="rowEven">Probing: <span style="color:orange; font-weight: 900;">'.$this->probing.'</span></td>
			</tr>');
		echo('</table>');
	
	}

	function set_gateways() {
		$stat_res = mi_command("dr_gw_status", array(), $this->widget_box['mi_conn'], $errors);
		foreach($stat_res['Gateways'] as $gateway) {
			switch ($gateway['State']) {
				case "Active":
					$this->available ++;
					break;
				case "Inactive": 
					$this->inactive ++;
					break;
				case "probing":
					$this->probing ++;
					break;
				default:
					error_log("Bug");
			}
		}
	}


    function echo_content() {
        $this->display_test();
    }

    public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['id'];
        }
        return $boxes_names;
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