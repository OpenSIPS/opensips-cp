<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class dispatching_widget extends widget
{
    public $active = 0;
	public $inactive = 0;
	public $probing = 0;
	public $box_id;
	public $widget_box;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
		$this->box_id = $array['widget_box'];
		$this->set_warning(1);
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$this->widget_box = $box;
		}
        $this->set_dispatching();
    }


    function get_name() {
        return "Dispatching destinations";
    }
    function display_test() {
		echo ('
			<table style="table-layout: fixed;
				width: 180px; height:20px; margin: auto; margin-left: 30px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
			');
		echo ('
			<tr><td class="rowEven">Available: </td><td><span style="color:green; font-weight: 900;">'.$this->active.'</span></td></tr>');
		if ($this->inactive >0) {
			echo ('<tr><td class="rowEven">Inactive: </td><td><span style="color:red; font-weight: 900;">'.$this->inactive.'</span></td></tr>');
			$this->set_warning(3);
		}
		if ($this->probing > 0)
			echo ('<tr><td class="rowEven">Probing: </td><td><span style="color:orange; font-weight: 900;">'.$this->probing.'</span></td>
			</tr>');
		echo('</table>');
	
	}

	function set_dispatching() {
		$stat_res = mi_command("ds_list", array(), $this->widget_box['mi_conn'], $errors);
		
		foreach($stat_res["PARTITIONS"] as $key => $partition) {
			foreach($partition["SETS"] as $key => $set) {
				foreach($set['Destinations'] as $destination) {
					switch ($destination['state']) {
						case "Active":
							$this->active ++;
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
        if (!$params['widget_name'])
            $params['widget_name'] = "Dispatching widget";
        form_generate_input_text("Name", "", "widget_name", null, $params['widget_name'], 20,null);
    	form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
	}

	static function get_description() {
		return "
Shows the number of available destinations vs probing/inactive ones";
	}

}

?>